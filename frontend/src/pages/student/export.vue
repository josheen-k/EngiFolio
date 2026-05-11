<script setup>
    import { ref, onMounted, watch } from 'vue';
    import { useRoute } from 'vue-router'
    import Navbar from '@/components/Navbar.vue'
    import api from "@/services/api";

    const route = useRoute();

    const profileSelected = ref(false);
    const certificationsSelected = ref(false);
    const competenciesSelected = ref(false);
    const networkingContactsSelected = ref(false);
    const goalsSelected = ref(false);
    const allDataSelected = ref(false);

    // Data values
    const profile = ref(null);
    const userCompetencies = ref(null);
    const contacts = ref(null);
    const plan = ref(null);

    // Keeps track of whether values are selected or not
    watch(allDataSelected, (newValue) => {
        profileSelected.value = newValue;
        certificationsSelected.value = newValue;
        competenciesSelected.value = newValue;
        networkingContactsSelected.value = newValue;
        goalsSelected.value = newValue;
    });


    const exportToPdf = async () => {
      // Check that at lease one category is selected
      if (!profileSelected.value && !certificationsSelected.value && !competenciesSelected.value && !networkingContactsSelected.value && !goalsSelected.value) {
        alert("You must select at least one category to export");
        return;
      }

      try {
        const response = await api.post(`/profile/${route.params.id}/export-pdf`, {
          selections: {
            profile: profileSelected.value,
            certifications: certificationsSelected.value,
            competencies: competenciesSelected.value,
            networking: networkingContactsSelected.value,
            goals: goalsSelected.value
          }
        }, { 
          responseType: 'blob',
          headers: { 'Accept': 'application/pdf' } 
        });

        const blob = new Blob([response.data], { type: 'application/pdf' });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `portfolio_export_${route.params.id}.pdf`);
        
        document.body.appendChild(link);
        link.click();
      
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
        
        alert("PDF Downloaded successfully");
      } catch (error) {
        console.error(error);
        alert("Error generating the PDF.");
      }

      // Reset values after
      profileSelected.value = false;
      certificationsSelected.value = false;
      competenciesSelected.value = false;
      networkingContactsSelected.value = false;
      goalsSelected.value = false;
      allDataSelected.value = false;
    };

    // Fetches the profile and adds the contents to the file
    const addProfile = async () => { 
      try {
        const response = await api.get(`/profile/${route.params.id}`);
        profile.value = response.data;
      } catch (error) {
        console.error("Error while fetching profile:", error);
      } finally {

        const firstName = profile.value.user?.first_name || '';
        const lastName = profile.value.user?.last_name || '';

        const formattedProfile = [
          '"----- Profile -----"',
          `"Name:","${firstName}","${lastName}"`,
          `"Preferred name:","${profile.value.preferred_name || firstName}"`,
          `"Degree:","${profile.value.degree_title}"`,
          `"Specialisation:","${profile.value.specialisation}"`,
          `"Personal Intro:","${profile.value.personal_intro}"`
        ]

        // Add all links for this profile
        if (profile.value.links && profile.value.links.length > 0) {
          profile.value.links.forEach(link => {
            formattedProfile.push(`"${link.link_label}:","${link.link_url}"`);
          });
        }

        formattedProfile.push('\n\n');
        return formattedProfile.join('\n');
      }
    };

    const addCertifications = async () => {
      try {
        const response = await api.get(`/profile/${route.params.id}`);
        const profileData = response.data;

        const formattedCerts = ['"----- Certificates -----"']

        if (profileData.achievement_certs.length > 0) {
          formattedCerts.push('"-- Achievement Certificates --"')
          formattedCerts.push('"Title","Details","Issued Date"')
          profileData.achievement_certs.forEach(cert => {
            formattedCerts.push(`"${cert.title}","${cert.body || ''}","${cert.issued_date || ''}"`)
          })
        }

        if (profileData.attainment_certs.length > 0) {
          formattedCerts.push('"-- Attainment Certificates --"')
          formattedCerts.push('"Title","Details","Issued Date","Expiry Date"')
          profileData.attainment_certs.forEach(cert => {
            formattedCerts.push(`"${cert.title}","${cert.body || ''}","${cert.issued_date || ''}","${cert.expiry_date || ''}"`)
          })
        }

        formattedCerts.push('\n\n');
        return formattedCerts.join('\n');
      } catch (error) {
        console.error("Error while fetching certifications:", error);
        return '';
      }
    };

    // Fetches the competencies and adds the contents to the file
    const addCompetencies = async () => { 
      try {
        const response = await api.get(`/competency-entries/${route.params.id}`);
        userCompetencies.value = response.data;

        const formattedComp = ['"----- Competencies -----"']
        
        const compHeader = [
          '"Competency Code"',
          '"EA Competency"',
          '"Competency Description"',
          '"Competency Link"',
          '"Experience Title"',
          '"Associated Year"',
          '"Experience Tasks"',
          '"Key Learnings"',
          '"Future Applications"',
          '"Level"',
          '"Status"',
          '"Start Date"',
          '"End Date"'
        ].join(",");

        formattedComp.push(compHeader);
          if (userCompetencies.value?.length > 0) {
            userCompetencies.value.forEach(comp => {
              const row = [
                `"${comp.indicator.display_id}"`,
                `"${comp.indicator.indicator_description}"`,
                `"${comp.indicator.description}"`,
                `"${comp.indicator.indicator_link || ''}"`,
                `"${comp.experience_title}"`,
                `"${comp.associated_year}"`,
                `"${comp.experience_tasks}"`,
                `"${comp.key_learnings || ''}"`,
                `"${comp.future_applications || ''}"`,
                `"${comp.entry_level.competency_level || ''}"`,
                `"${comp.entry_status.entry_status || ''}"`,
                `"${comp.start_date}"`,
                `"${comp.end_date || ''}"`
              ].join(",");
              formattedComp.push(row);
            });
          }


        formattedComp.push('\n\n');
        return formattedComp.join('\n');
        
      } catch (error) {
        console.error("Error while fetching user competencies:", error);
      }
    };

    // Fetches the networking contacts and adds the contents to the file
    const addNetworkingContacts = async () => { 
      try {
        const response = await api.get(`/users/${route.params.id}/industry-contacts`);
        contacts.value = response.data;
      } catch (error) {
        console.error("Error while fetching user contacts:", error);
      } finally {
        const formattedNet = ['"----- Networking Contacts -----"']
        
        const netHeader = [
          `"Name"`,
          `"Company"`,
          `"Progress Notes"`,
          `"Date Met"`,
        ].join(",");

        formattedNet.push(netHeader);

        if (contacts.value && contacts.value.length > 0) {
          contacts.value.forEach(contact => {
            const row = [
              `"${contact.contact_name}"`,
              `"${contact.company || ''}"`,
              `"${contact.progress_notes || ''}"`,
              `"${contact.date_met || ''}"`,
            ].join(",");
            formattedNet.push(row);
          });

        }

        formattedNet.push('\n\n');
        return formattedNet.join('\n');
      }    
    };

    // Fetches the goals and adds the contents to the file
    const addGoals = async () => { 
      try {
        const response = await api.get(`/career-plans/${route.params.id}`);
        const data = response.data;
        plan.value = Array.isArray(data) ? data[0] : data;

        const formattedGoals = ['"----- Goals -----"']
        
        const goalsHeader = [
          `"Goal Description"`,
          `"Timeline"`,
          `"Progress Notes"`,
          `"Learnings"`,
          `"Start Date"`,
          `"End Date"`,
          `"Completion Date"`,
          `"Completion Notes"`,
          `"Steps"`,
        ].join(",");

        formattedGoals.push(goalsHeader);

        if (plan.value.smart_goals && plan.value.smart_goals.length > 0) {
          plan.value.smart_goals.forEach(goal => {
            const row = [
              `"${goal.goal_description}"`,
              `"${goal.timeline || ''}"`,
              `"${goal.progress_notes || ''}"`,
              `"${goal.learnings || ''}"`,
              `"${goal.start_date || ''}"`,
              `"${goal.end_date || ''}"`,
              `"${goal.completion_date || ''}"`,
              `"${goal.completion_notes || ''}"`,
              `"${goal.status.status || ''}"`,
            ].join(",");

            formattedGoals.push(row);

            if (goal.action_steps && goal.action_steps.length > 0) {
              goal.action_steps.forEach(step => {
              formattedGoals.push(`"","","","","","","","","${step.step_order}. ${step.step_description}"`);
          });
        }
          });

        }

        formattedGoals.push('\n\n');
        return formattedGoals.join('\n');
      } catch (error) {
        console.error("Error while fetching user goals:", error);
      } 
    };


    const exportData = async () => {
        if (!profileSelected.value && !certificationsSelected.value && !competenciesSelected.value && !networkingContactsSelected.value && !goalsSelected.value) {
            alert("You must select at least one category to export");
            return;
        }

        // Add only selected fields
        const exportCSV = [];
        if (profileSelected.value) {
          const profileData = await addProfile();
          exportCSV.push(profileData);
        };

        if (certificationsSelected.value) {
          exportCSV.push(await addCertifications());
        }

        if (competenciesSelected.value) {
          const competencyData = await addCompetencies();
          exportCSV.push(competencyData);
        }

        if (networkingContactsSelected.value) {
          const networkingData = await addNetworkingContacts();
          exportCSV.push(networkingData);
        }
        
        if (goalsSelected.value) {
          const goalsData = await addGoals();
          exportCSV.push(goalsData);
        }

        const exportContent = exportCSV.map(title => `${title}`).join("\n");

        
        const blob = new Blob(["\ufeff", exportContent], { type: 'text/csv;charset=utf-8;' });
        
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);
        
        link.setAttribute("href", url);
        link.setAttribute("download", "engifolio_export.csv");
        link.style.visibility = 'hidden';
        
        // Create download link, click it then remove download link
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        alert("Downloading exported data");

        // Reset values after
        profileSelected.value = false;
        certificationsSelected.value = false;
        competenciesSelected.value = false;
        networkingContactsSelected.value = false;
        goalsSelected.value = false;
        allDataSelected.value = false;
    };

  // Reset all ticked boxes on reload
  onMounted(() => {
      profileSelected.value = false;
      certificationsSelected.value = false;
      competenciesSelected.value = false;
      networkingContactsSelected.value = false;
      goalsSelected.value = false;
      allDataSelected.value = false;
  });
</script>

<template>
  <Navbar />

  <main class="container-xl py-5">
    <div class="row justify-content-center">
      <div class="col-xl-8">
        <h1 class="sec-title text-center mb-5">Export Your Portfolio</h1>

        <div class="card stat-card card-dark p-4 mb-5">
          <div class="card-body">
            <h2 class="stat-title mb-4 text-center">
              Select Data Categories
            </h2>

            <div class="focus-table d-flex flex-column gap-2">
              <div class="selection-row d-flex align-items-center rounded-4">
                <label class="form-check-label d-flex p-3 w-100" for="checkProfile">
                  <input class="form-check-input me-3" type="checkbox" v-model="profileSelected" id="checkProfile">
                  <div>
                    <div class="d-block fw-bold">Profile</div>
                    <small>Bio, degree details, and professional links</small>
                  </div>
                </label>
              </div>

              <div class="selection-row d-flex align-items-center rounded-4">
                <label class="form-check-label d-flex p-3 w-100" for="checkCerts">
                  <input class="form-check-input me-3" type="checkbox" v-model="certificationsSelected" id="checkCerts">
                  <div>
                    <div class="d-block fw-bold">Certificates</div>
                    <small>Achievement and Attainment Certificates</small>
                  </div>
                </label>
              </div>

              <div class="selection-row d-flex align-items-center rounded-4">
                <label class="form-check-label d-flex p-3 w-100" for="checkComp">
                  <input class="form-check-input me-3" type="checkbox" v-model="competenciesSelected" id="checkComp">
                  <div>
                    <div class="d-block fw-bold">Competencies</div>
                    <small>Reflection entries and EA competency levels</small>
                  </div>
                </label>
              </div>

              <div class="selection-row d-flex align-items-center rounded-4">
                <label class="form-check-label d-flex p-3 w-100" for="checkNet">
                  <input class="form-check-input me-3" type="checkbox" v-model="networkingContactsSelected" id="checkNet">
                  <div>
                    <div class="d-block fw-bold">Networking Contacts</div>
                    <small>Industry connections and progress notes</small>
                  </div>
                </label>
              </div>

              <div class="selection-row d-flex align-items-center rounded-4">
                <label class="form-check-label d-flex p-3 w-100" for="checkGoals">
                  <input class="form-check-input me-3" type="checkbox" v-model="goalsSelected" id="checkGoals">
                  <div>
                    <div class="d-block fw-bold">Smart Goals</div>
                    <small>Career planning and action steps</small>
                  </div>
                </label>
              </div>
              <hr class="my-6"/>

              <div class="selection-row selection-all d-flex align-items-center rounded-4">
                <label class="form-check-label fw-bold p-3 w-100">
                  <input class="form-check-input me-3" type="checkbox" v-model="allDataSelected" id="checkAll">
                  <span class="fw-bold">{{ allDataSelected ? 'Unselect All Data' : 'Select All Data' }}</span>
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex flex-wrap gap-3 justify-content-center">
          <button class="btn btn-csv rounded-pill px-5" @click="exportData">Export CSV</button>
          <button class="btn btn-pdf rounded-pill px-5" @click="exportToPdf">Export PDF Document</button>
        </div>
      </div>
    </div>
  </main>
</template>

<style scoped>
  .sec-title {
    font-family: 'Martel', serif;
    font-size: 2rem;
    color: #2b2b2bc5;
    font-weight: lighter;
    margin-bottom: 2rem;
  }

  .stat-title {
    color: #1d1d1dc5;
  }

  .stat-card {
    background: #f7f7f7;
    border: 1px solid #cccccc;
    border-radius: 2rem;
    padding: 1.5rem 1.75rem;
    transition: box-shadow 0.2s ease;
  }

  .card-dark {
    background: #f1f1f1;
  }

  .stat-title {
    font-family: 'Martel', serif;
    font-size: 1.3rem;
    font-weight: 200;
    color: #6d6d6d;
  }

  .selection-row {
    cursor: pointer;
  }

  .selection-row:hover {
    background: rgba(255, 255, 255, 0.5);
  }

  .form-check-input {
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 0.5rem;
    border: 2px solid #bababa;
    cursor: pointer;
  }

  .form-check-input:checked {
    background-color: #333;
    border-color: #333;
  }

  .form-check-label {
    font-family: 'Maven Pro', sans-serif;
    cursor: pointer;
    color: #444;
  }

  .btn-csv {
		font-family: 'Montserrat Alternates', sans-serif;
		border-radius: 1.5rem;
		background: #bdbdbd;
	}

  .btn-csv:hover {
		background: #979797;
	}

	.btn-pdf {
		font-family: 'Montserrat Alternates', sans-serif;
		font-size: 1rem;
		color: #ffffff;
		background: #555555;
		padding: 0.5rem 1rem;
	}

	.btn-pdf:hover {
		color: #ffffff;
		background: #222222;
	}

  .selection-all {
		background: #ffffff;
  }

  
</style>