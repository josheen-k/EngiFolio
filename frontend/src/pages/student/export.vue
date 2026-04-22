<script setup>
    import { ref, onMounted, watch } from 'vue';
    import { useRoute } from 'vue-router'
    import axios from 'axios';
    import Navbar from '@/components/Navbar.vue'
    import Footer from '@/components/Footer.vue'

    const route = useRoute();

    const profileSelected = ref(false);
    const competenciesSelected = ref(false);
    const networkingSelected = ref(false);
    const careerPlanSelected = ref(false);
    const allDataSelected = ref(false);

    // Data values
    const profile = ref(null);
    const userCompetencies = ref(null);

    // Keeps track of whether values are selected or not
    watch(allDataSelected, (newValue) => {
        profileSelected.value = newValue;
        competenciesSelected.value = newValue;
        networkingSelected.value = newValue;
        careerPlanSelected.value = newValue;
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

        if (userCompetencies.value.length > 0) {
          userCompetencies.value.forEach(comp => {
            const row = `"${comp.indicator_id}","${comp.experience_title}","${comp.level}","${comp.status}","${comp.associated_year}"`;
            formattedComp.push(row);
          });

        }
        return formattedComp.join('\n');
      }
    };

    // Fetches the networking information and adds the contents to the file
    const addNetworking = async () => { 
      const formattedNet = ['----- Networking -----\n\n']  
      return formattedNet.join('\n');
    };

    // Fetches the career plans and adds the contents to the file
    const addCareerPlan = async () => { 
      const formattedNet = ['----- Career Plan -----\n\n']  
      return formattedNet.join('\n');
    };


    const exportData = async () => {
        if (!profileSelected.value && !competenciesSelected.value && !networkingSelected.value && !careerPlanSelected.value) {
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
        if (networkingSelected.value) {
          const networkingData = await addNetworking();
          exportCSV.push(networkingData);
        }
        
        if (careerPlanSelected.value) {
          const careerPlanData = await addCareerPlan();
          exportCSV.push(careerPlanData);
        }

        const exportContent = exportCSV.map(title => `${title}`).join("\n");

        
        const blob = new Blob(["\ufeff", exportContent], { type: 'text/csv;charset=utf-8;' });
        
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);
        
        link.setAttribute("href", url);
        link.setAttribute("download", "portfolio_export.csv");
        link.style.visibility = 'hidden';
        
        // Create download link, click it then remove download link
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        alert("Downloading exported data");

        // Reset values after
        profileSelected.value = false;
        competenciesSelected.value = false;
        networkingSelected.value = false;
        careerPlanSelected.value = false;
        allDataSelected.value = false;
    };

  // Reset all ticked boxes on reload
  onMounted(() => {
      profileSelected.value = false;
      competenciesSelected.value = false;
      networkingSelected.value = false;
      careerPlanSelected.value = false;
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
              <input class="form-check-input" type="checkbox" v-model="networkingSelected" id="checkNet">
              <label class="form-check-label" for="checkNet">Networking</label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="careerPlanSelected" id="checkCareerPlan">
              <label class="form-check-label" for="checkCareerPlan">Career Plan</label>
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