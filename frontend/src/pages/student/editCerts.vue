<template>
  <Navbar />

  <div class="container-lg py-5 px-4" v-if="profile">
    <!--achievement certs-->
    <section class="mb-5 pb-4 border-bottom">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="sec-title mb-0">Achievement Certifications</h2>
        <button class="btn btn-ql" @click="addAchCert">Add Achievement</button>
      </div>
      <div v-if="profile.achievement_certs.length" class="d-flex flex-column gap-3">
        <div class="cert-row" v-for="(cert, index) in profile.achievement_certs" :key="index">

          <!-- header-->
          <div class="d-flex align-items-center gap-2 mb-3">
            <img src="@/assets/cert.png" class="cert-icon" alt="certificate" />
            <span class="cert-type-label">Certificate of Achievement</span>
            <button class="remove-btn ms-auto" @click="removeAchCert(index)">
              <img src="@/assets/delete.png" class="del-icon" alt="remove" />
            </button>
          </div>

          <!--form-->
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="field-label">Title</label>
              <input v-model="cert.title" class="field-input form-control" placeholder="eg: Dean's Award" />
            </div>
            <div class="col-12">
              <label class="field-label">Description</label>
              <input v-model="cert.body" class="field-input form-control"
                placeholder="Brief description of this certification" />
            </div>
            <div class="col-12">
              <label class="field-label">File path / URL</label>
              <input v-model="cert.file_path" class="field-input form-control"
                placeholder="https://example.com/cert.pdf" />
            </div>
            <div class="col-12 col-md-4">
              <label class="field-label">Issued date</label>
              <input type="date" v-model="cert.issued_date" class="field-input form-control" />
            </div>
          </div>

        </div>
      </div>
      <p v-else class="empty-txt text-center py-4">No achievement certifications yet! Click add to get started.</p>
    </section>

    <!--attainment certs-->
    <section class="mb-5 pb-4 border-bottom">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="sec-title mb-0">Attainment Certifications</h2>
        <button class="btn btn-ql" @click="addAttCert">Add Attainment</button>
      </div>

      <div v-if="profile.attainment_certs.length" class="d-flex flex-column gap-3">
        <div class="cert-row" v-for="(cert, index) in profile.attainment_certs" :key="index">

          <div class="d-flex align-items-center gap-2 mb-3">
            <img src="@/assets/cert.png" class="cert-icon" alt="certificate" />
            <span class="cert-type-label">Certificate of Attainment</span>
            <button class="remove-btn ms-auto" @click="removeAttCert(index)">
              <img src="@/assets/delete.png" class="del-icon" alt="remove" />
            </button>
          </div>

          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="field-label">Title</label>
              <input v-model="cert.title" class="field-input form-control" placeholder="e.g. Certified Engineer" />
            </div>
            <div class="col-12">
              <label class="field-label">Description</label>
              <input v-model="cert.body" class="field-input form-control"
                placeholder="Brief description of this certification" />
            </div>
            <div class="col-12">
              <label class="field-label">File path / URL</label>
              <input v-model="cert.file_path" class="field-input form-control"
                placeholder="https://example.com/cert.pdf" />
            </div>
            <div class="col-12 col-md-4">
              <label class="field-label">Issued date</label>
              <input type="date" v-model="cert.issued_date" class="field-input form-control" />
            </div>
            <div class="col-12 col-md-4">
              <label class="field-label">Expiry date</label>
              <input type="date" v-model="cert.expiry_date" class="field-input form-control" />
            </div>
          </div>

        </div>
      </div>

      <p v-else class="empty-txt text-center py-4">No attainment certifications yet! Click add to getstarted.</p>
    </section>

    <!--footer-->
    <div class="d-flex justify-content-center gap-3 pt-3">
      <button class="btn btn-filter" @click="cancel">Cancel</button>
      <button class="btn btn-ql" @click="saveChanges">Save Changes</button>
    </div>
  </div>

  <div v-else class="container py-5 text-center">
    <p class="text-muted small">Loading settings...</p>
  </div>
</template>

<script setup>
  import { ref, onMounted } from 'vue';
  import { useRouter, useRoute } from 'vue-router';
  import Navbar from '@/components/Navbar.vue'
  import api from "@/services/api";

  const router = useRouter();
  const route = useRoute();
  const profile = ref(null);
  const loading = ref(true);
  const achievementCertsToDelete = ref([]);
  const attainmentCertsToDelete = ref([]);

  const loadProfile = async () => {
    // Get profile data, throw error if unsuccessful
    try {
      const response = await  api.get(`/profile/${route.params.id}`);
      profile.value = response.data.profile || response.data;
    } catch (error) {
      console.error("Error while fetching profile:", error);
    } finally {
      loading.value = false;
    }
  };

  // Adds an empty cert to the frontend profile data when add cert is clicked
  const addAchCert = () => {
		profile.value.achievement_certs.push({
			title: '',
			body: '',
			issued_date: '',
			profile_id: route.params.id
		});
	};

	const addAttCert = () => {
		profile.value.attainment_certs.push({
			title: '',
			body: '',
			issued_date: '',
			expiry_date: '',
			profile_id: route.params.id
		});
	};

	const removeAchCert = (index) => {
    const cert = profile.value.achievement_certs[index];
		if (cert.achievement_cert_id) {
			achievementCertsToDelete.value.push(cert.achievement_cert_id);
		}

		profile.value.achievement_certs.splice(index, 1);
	};

	const removeAttCert = (index) => {
		const cert = profile.value.attainment_certs[index];
		if (cert.attainment_cert_id) {
			attainmentCertsToDelete.value.push(cert.attainment_cert_id);
		}
		profile.value.attainment_certs.splice(index, 1);
	};


const saveChanges = async () => {
  try {
    // 1. Deletions (Handles both types of certs)
    const deleteAchPromises = achievementCertsToDelete.value.map(id => api.delete(`/achievement-cert/${id}`));
    const deleteAttPromises = attainmentCertsToDelete.value.map(id => api.delete(`/attainment-cert/${id}`));

    // 2. Handle Achievement Upserts
    const achUpsertPromises = profile.value.achievement_certs.map(cert => {
      if (!cert.title || cert.title.trim() === '') return null; // Ignore empty rows

      if (cert.achievement_cert_id) {
        return api.put(`/achievement-cert/${cert.achievement_cert_id}`, cert);
      } else {
        // Add profile_id so the backend knows who owns this new cert
        return api.post(`/achievement-cert`, { ...cert, profile_id: route.params.id });
      }
    }).filter(p => p !== null);

    // 3. Handle Attainment Upserts
    const attUpsertPromises = profile.value.attainment_certs.map(cert => {
      if (!cert.title || cert.title.trim() === '') return null;

      if (cert.attainment_cert_id) {
        return api.put(`/attainment-cert/${cert.attainment_cert_id}`, cert);
      } else {
        return api.post(`/attainment-cert`, { ...cert, profile_id: route.params.id });
      }
    }).filter(p => p !== null);

    // 4. Execute all API calls concurrently
    await Promise.all([
        ...deleteAchPromises, 
        ...deleteAttPromises, 
        ...achUpsertPromises, 
        ...attUpsertPromises
    ]);
    
    // Clear tracking arrays
    achievementCertsToDelete.value = [];
    attainmentCertsToDelete.value = [];
    
    // Redirect back to the view page
    router.push({ name: 'profile', params: { id: route.params.id }, query: { tab: 'CERTIFICATIONS' } });
  } catch (error) {
    console.error("Save failed:", error);
    alert("There was an error saving your certifications.");
  }
};

  const cancel = () => {
      router.push({ name: 'profile', params: { id: route.params.id }, query: { tab: 'CERTIFICATIONS' } });
  };

onMounted(() => {
  loadProfile();
})

</script>

<style scoped>
.sec-title {
    font-family: 'Martel', serif;
    font-size: 2rem;
    color: #303030c5;
  }

  .cert-row {
    background: #f7f7f7;
    border: 1px solid #cccccc;
    border-radius: 2rem;
    padding: 1.5rem 1.75rem;
    transition: box-shadow 0.2s ease;
  }
  
  .cert-row:hover {
    box-shadow: 0 0.5rem 1.5rem #e5e5e5;
  }
  
  .cert-icon {
    width: 1.3rem;
    height: 1.3rem;
    object-fit: contain;
    opacity: 0.6;
  }
  
  .cert-type-label {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 0.85rem;
    color: #aaaaaa;
  }
  
  .remove-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.2rem;
    opacity: 0.6;
    transition: transform 0.2s ease, opacity 0.2s ease;
  }
  
  .remove-btn:hover {
    transform: scale(1.15);
    opacity: 1;
  }
  
  .del-icon {
    width: 1.3rem;
    height: 1.3rem;
    object-fit: contain;
  }
  
  .field-label {
    display: block;
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 0.8rem;
    color: #999999;
    margin-bottom: 0.3rem;
  }
  
  .field-input.form-control {
    font-family: 'Maven Pro', sans-serif;
    font-size: 1rem;
    color: #333333;
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 0.75rem;
  }
  
  .field-input.form-control:focus {
    border-color: #c4c4c4;
    box-shadow: 0 0 0 0.02rem #2b2b2b;
  }
  
  .empty-txt {
    font-family: 'Maven Pro', sans-serif;
    font-size: 1rem;
    color: #aaaaaa;
  }
  
  .btn-filter {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 1rem;
    border-radius: 1.5rem;
    background: #e6e6e6;
    color: #222222;
    padding: 0.5rem 2rem;
  }
  
  .btn-filter:hover {
    background: #666666;
    color: #ffffff;
  }
  
  .btn-ql {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 1rem;
    color: #ffffff;
    background: #555555;
    border-radius: 1.5rem;
    padding: 0.5rem 2rem;
  }
  
  .btn-ql:hover {
    color: #ffffff;
    background: #333333;
  }
  
  @media (min-width: 820px) {
      .container-lg {
          max-width: 60%;
      }
  }
</style>