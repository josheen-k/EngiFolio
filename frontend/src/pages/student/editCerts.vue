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
    router.push({ name: 'profile', params: { id: route.params.id } });
  } catch (error) {
    console.error("Save failed:", error);
    alert("There was an error saving your certifications.");
  }
};

  const cancel = () => {
      router.push({ name: 'profile', params: { id: route.params.id } });
  };

onMounted(() => {
  loadProfile();
})


</script>

<template>
  <Navbar />
  <body class="container py-5" v-if="profile">
<div class="card stat-card card-dark border-0 h-auto p-4 mb-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="stat-title mb-0">Achievement Certifications</h5>
    <button @click="addAchCert" class="btn btn-ql rounded-pill px-4">Add Achievement</button>
  </div>       
  <div class="row g-3">
    <div v-for="(cert, index) in profile.achievement_certs" :key="index" class="col-12 border-bottom border-secondary pb-3 mb-2">
      <div class="row g-2 align-items-end">
        <div class="col-md-3">
          <label class="form-label fw-bold">Title</label>
          <input v-model="cert.title" class="form-control"/>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-bold">Description</label>
          <input v-model="cert.body" class="form-control"/>
        </div>
				<div class="col-md-4">
          <label class="form-label fw-bold">File Path</label>
          <input v-model="cert.file_path" class="form-control"/>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-bold">Issued Date</label>
          <input type="date" v-model="cert.issued_date" class="form-control"/>
        </div>
        <div class="col-md-2 offset-md-1">
          <button @click="removeAchCert(index)" class="btn btn-filter px-4">Delete</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card stat-card card-dark border-0 h-auto p-4 mb-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="stat-title mb-0">Attainment Certifications</h5>
    <button @click="addAttCert" class="btn btn-ql rounded-pill px-4">Add Attainment</button>
  </div>       
  <div class="row g-3">
    <div v-for="(cert, index) in profile.attainment_certs" :key="index" class="col-12 border-bottom border-secondary pb-3 mb-2">
      <div class="row g-2 align-items-end">
        <div class="col-md-3">
          <label class="form-label fw-bold">Title</label>
          <input v-model="cert.title" class="form-control"/>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-bold">Description</label>
          <input v-model="cert.body" class="form-control"/>
        </div>
				<div class="col-md-5">
          <label class="form-label fw-bold">File Path</label>
          <input v-model="cert.file_path" class="form-control"/>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-bold">Issued Date</label>
          <input type="date" v-model="cert.issued_date" class="form-control"/>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-bold">Expiry Date</label>
          <input type="date" v-model="cert.expiry_date" class="form-control"/>
        </div>
        <div class="col-md-2 offset-md-1">
          <button @click="removeAttCert(index)" class="btn btn-filter px-4">Delete</button>
        </div>
      </div>
    </div>
  </div>
</div>
        <footer class="border-top pt-4 d-flex justify-content-end gap-2">
          <button class="btn btn-filter px-4" @click="cancel">Cancel</button>
          <button class="btn btn-ql rounded-pill px-4" @click="saveChanges">Save Changes</button>
        </footer>
  </body>
	

	

  <div v-else class="container py-5 text-center">
    <p class="text-muted small">Loading settings...</p>
  </div>
</template>

<style scoped>
	.sec-title {
		font-family: 'Martel', serif;
		font-size: 2.0rem;
		color: #1c1c1cc5;
		font-weight: lighter;
		margin-bottom: 2rem;
	}

	.card-dark {
		background: #f1f1f1;
	}

	.stat-title {
		font-family: 'Maven Pro', sans-serif;
		font-size: 1.5rem;
		color: #3b3b3b;
	}

	.btn-filter {
		font-family: 'Montserrat Alternates', sans-serif;
		border-radius: 1.5rem;
		font-weight: lighter;
		background: #e6e6e6;
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

	.btn-filter:hover {
		background: #666666;
		color: #ffffff;
	}

  .profile-pic {
		width: 150px;
		height: 150px; 
		border-radius: 50%;
		object-fit: cover;   
		border: 3px solid #f1f1f1; 
		background-color: #fff;
		box-shadow: 0 2px 5px rgba(0,0,0,0.05);
	}
</style>