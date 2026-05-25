<script setup>
    import { ref, watch } from 'vue';
    import { useRoute } from 'vue-router'
    import Navbar from '@/components/Navbar.vue'
    import api from "@/services/api";
    import { useExportData } from '@/composables/useExportData'

    const route = useRoute();

    // Deconstructs the functions so that they can be called individually
    const { addProfile, addCertifications, addCompetencies, addNetworkingContacts, addGoals } = useExportData(route.params.id)

    const profileSelected = ref(false);
    const certificationsSelected = ref(false);
    const competenciesSelected = ref(false);
    const networkingContactsSelected = ref(false);
    const goalsSelected = ref(false);
    const allDataSelected = ref(false);

    watch(allDataSelected, (newValue) => {
      profileSelected.value = newValue
      certificationsSelected.value = newValue
      competenciesSelected.value = newValue
      networkingContactsSelected.value = newValue
      goalsSelected.value = newValue
    })

    // Set up a pop up notification instead of having an alert
    const popUp = ref({ show: false, message: '', type: '' })

    const showPopUp = (message, type) => {
      popUp.value = { show: true, message, type }
      setTimeout(() => popUp.value.show = false, 3000)
    }

    const exportToPdf = async () => {
      // Check that at lease one category is selected
      if (profileSelected.value || certificationsSelected.value || competenciesSelected.value || networkingContactsSelected.value || goalsSelected.value) {
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

          // Creates the file-like object and a temporary URL
          const blob = new Blob([response.data], { type: 'application/pdf' });
          const url = window.URL.createObjectURL(blob);
          // Creates an a tag in memory for downloading and link to url
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', `portfolio_export_${route.params.id}.pdf`);
          // Adds the page to the link, clicks the link then removes the link
          document.body.appendChild(link);
          link.click();
        
          document.body.removeChild(link);
          window.URL.revokeObjectURL(url);
          
          showPopUp("Downloading PDF...", "success");
        } catch (error) {
          console.error(error);
          showPopUp("Error generating the PDF.", "error");
        }

        // Reset values after
        profileSelected.value = false;
        certificationsSelected.value = false;
        competenciesSelected.value = false;
        networkingContactsSelected.value = false;
        goalsSelected.value = false;
        allDataSelected.value = false;
      } else {
        showPopUp("You must select at least one category to export", "error");
        return;
      }    
    };

    const exportData = async () => {
        if (profileSelected.value || certificationsSelected.value || competenciesSelected.value || networkingContactsSelected.value || goalsSelected.value) {
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

          // Creates the file-like object and a temporary URL
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

          showPopUp("Downloading CSV...", "success");

          // Reset values after
          profileSelected.value = false;
          certificationsSelected.value = false;
          competenciesSelected.value = false;
          networkingContactsSelected.value = false;
          goalsSelected.value = false;
          allDataSelected.value = false;

        } else {
          showPopUp("You must select at least one category to export", "error");
        return;
      }    
    };
</script>

<template>
  <Navbar />

  <main class="container-xl py-5">
    <div class="row justify-content-center export-page">
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
    <div v-if="popUp.show" class="popUp-msg" :class="popUp.type">
      {{ popUp.message }}
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

  .export-page {
    min-height: calc(100vh - 150px);
  }

  .popUp-msg {
    position: fixed;
    top: 5rem;   
    left: 0;
    right: 0;
    margin-inline: auto;
    width: max-content;
    padding: 0.75rem 2rem;
    border-radius: 2rem; 
    font-family: 'Maven Pro', sans-serif;
    font-size: 1.15rem;
  }

  .popUp-msg.success {
    background: #5d5d5d;
    color: #fff;
  }

  .popUp-msg.error {
    background: #db7979;
    color: #fff;
  }
</style>