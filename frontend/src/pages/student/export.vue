<script setup>
    import { ref, onMounted, watch } from 'vue';
    import { useRoute } from 'vue-router'
    import axios from 'axios';
    import Navbar from '@/components/Navbar.vue'
    import Footer from '@/components/Footer.vue'

    const route = useRoute();

    const profileSelected = ref(false);
    const competenciesSelected = ref(false);
    const networkingContactsSelected = ref(false);
    const goalsSelected = ref(false);
    const allDataSelected = ref(false);

    // Data values
    const profile = ref(null);
    const userCompetencies = ref(null);
    const contacts = ref(null);
    const goals = ref(null);

    // Keeps track of whether values are selected or not
    watch(allDataSelected, (newValue) => {
        profileSelected.value = newValue;
        competenciesSelected.value = newValue;
        networkingContactsSelected.value = newValue;
        goalsSelected.value = newValue;
    });

    // Fetches the profile and adds the contents to the file
    const addProfile = async () => { 
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/profile/${route.params.id}`);
        profile.value = response.data.profile || response.data;
      } catch (error) {
        console.error("Error while fetching profile:", error);
      } finally {
        const formattedProfile = [
          '"----- Profile -----"',
          `"Name:","${profile.value.first_name}","${profile.value.last_name}"`,
          `"Preferred name:","${profile.value.preferred_name || profile.value.first_name}"`,
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

    // Fetches the competencies and adds the contents to the file
    const addCompetencies = async () => { 
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/competency-entries/${route.params.id}`);
        userCompetencies.value = response.data;
      } catch (error) {
        console.error("Error while fetching user competencies:", error);
      } finally {
        const formattedComp = ['"----- Competencies -----"']
        
        const compHeader = [
          `"Competency Code"`,
          `"EA Competency"`,
          `"Competency Description"`,
          `"Competency Link"`,
          `"Experience Title"`,
          `"Associated Year"`,
          `"Experience Tasks"`,
          `"Key Learnings"`,
          `"Future Applications"`,
          `"Level"`,
          `"Status"`,
          `"Start Date"`,
          `"End Date"`
        ].join(",");

        formattedComp.push(compHeader);

        if (userCompetencies.value.length > 0) {
          userCompetencies.value.forEach(comp => {
            const row = [
              `"${comp.indicator.display_id}"`,
              `"${comp.indicator.indicator_name}"`,
              `"${comp.indicator.description}"`,
              `"${comp.indicator.indicator_link || ''}"`,
              `"${comp.experience_title}"`,
              `"${comp.associated_year}"`,
              `"${comp.experience_tasks}"`,
              `"${comp.key_learnings || ''}"`,
              `"${comp.future_applications || ''}"`,
              `"${comp.level}"`,
              `"${comp.status}"`,
              `"${comp.start_date}"`,
              `"${comp.end_date || ''}"`
            ].join(",");
            formattedComp.push(row);
          });

        }

        formattedComp.push('\n\n');
        return formattedComp.join('\n');
      }
    };

    // Fetches the networking contacts and adds the contents to the file
    const addNetworkingContacts = async () => { 
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/users/${route.params.id}/industry-contacts`);
        contacts.value = response.data;
      } catch (error) {
        console.error("Error while fetching user contacts:", error);
      } finally {
        const formattedNet = ['----- Networking Contacts -----']
        
        const netHeader = [
          `"Name"`,
          `"Company"`,
          `"Progress Notes"`,
          `"Date Met"`,
        ].join(",");

        formattedNet.push(netHeader);

        if (contacts.value.length > 0) {
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
        const response = await axios.get(`http://127.0.0.1:8000/api/smart-goals/${route.params.id}`);
        goals.value = response.data;
      } catch (error) {
        console.error("Error while fetching user goals:", error);
      } finally {
        const formattedGoals = ['----- SMART Goals -----']
        
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

        if (goals.value && goals.value.length > 0) {
          goals.value.forEach(goal => {
            const row = [
              `"${goal.goal_description}"`,
              `"${goal.timeline || ''}"`,
              `"${goal.progress_notes || ''}"`,
              `"${goal.learnings || ''}"`,
              `"${goal.start_date || ''}"`,
              `"${goal.end_date || ''}"`,
              `"${goal.completion_date || ''}"`,
              `"${goal.completion_notes || ''}"`,
              `"${goal.status || ''}"`,
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
      }    
    };


    const exportData = async () => {
        if (!profileSelected.value && !competenciesSelected.value && !networkingContactsSelected.value && !goalsSelected.value) {
            alert("You must select at least one category to export");
            return;
        }

        // Add only selected fields
        const exportCSV = [];
        if (profileSelected.value) {
          const profileData = await addProfile();
          exportCSV.push(profileData);
        };
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
        competenciesSelected.value = false;
        networkingContactsSelected.value = false;
        goalsSelected.value = false;
        allDataSelected.value = false;
    };

  // Reset all ticked boxes on reload
  onMounted(() => {
      profileSelected.value = false;
      competenciesSelected.value = false;
      networkingContactsSelected.value = false;
      goalsSelected.value = false;
      allDataSelected.value = false;
  });
</script>

<template>
  <Navbar />

  <main class="container-xl py-5 px-4">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h1 class="sec-title mb-4">Export Your Portfolio</h1>

        <div class="card card-dark border-0 p-4 mb-4">
          <h2 class="sec-title mb-4" style="font-size: 1.25rem;">
            Tick the boxes of the data you would like to be included:
          </h2>

          <div class="focus-table d-flex flex-column gap-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="profileSelected" id="checkProfile">
              <label class="form-check-label" for="checkProfile">Profile</label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="competenciesSelected" id="checkComp">
              <label class="form-check-label" for="checkComp">Competencies</label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="networkingContactsSelected" id="checkNet">
              <label class="form-check-label" for="checkNet">Networking Contacts</label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="goalsSelected" id="checkGoals">
              <label class="form-check-label" for="checkGoals">Smart Goals</label>
            </div>

            <hr class="my-2" />

            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="allDataSelected" id="checkAll">
              <label class="form-check-label fw-bold" for="checkAll">All data</label>
            </div>
          </div>
        </div>

        <div class="d-flex gap-3 align-items-center">
          <button class="btn btn-ql rounded-pill px-5" @click="exportData">Export Data</button>
          <router-link :to="`/student/export/${$route.params.id}`" class="btn btn-link text-muted btn-sm text-decoration-none">Cancel</router-link>
        </div>
      </div>
    </div>
  </main>

  <Footer />
</template>

<style scoped>
  .sec-title {
    font-family: 'Martel', serif;
    font-size: 2.0rem;
    color: #303030c5;
  }

  .card-dark {
    background: #f1f1f1;
    border-radius: 8px;
  }

  .focus-table {
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.95rem;
  }

  .btn-ql {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 1rem;
    color: #ffffff;
    background: #555555;
    padding: 0.5rem 1rem;
  }

  .btn-ql:hover {
    color: #ffffff;
    background: #333333;
  }
</style>