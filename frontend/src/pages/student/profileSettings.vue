<script setup>
  import { ref, onMounted } from 'vue';
  import { useRouter, useRoute } from 'vue-router';
  import Navbar from '@/components/Navbar.vue'
  import api from "@/services/api";

  const router = useRouter();
  const route = useRoute();
  const profile = ref(null);
  const loading = ref(true);
  const linksToDelete = ref([]);

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

  // Adds an empty link to the frontend profile data when add link is clicked
  const addLink = () => {
    profile.value.links.push({
      link_label: '',
      link_url: '',
      profile_id: route.params.id
    });
  };

const removeLink = (index) => {
  const link = profile.value.links[index];
    if (link.link_id) {
      linksToDelete.value.push(link.link_id);
    }
    
    profile.value.links.splice(index, 1);
  };


const saveChanges = async () => {
  try {
    // Saves the main profile
    await api.put(`/profile/${route.params.id}`, profile.value);

    // Deletes the required links
    const deletePromises = linksToDelete.value.map(id => api.delete(`/link/${id}`));

    // 3. Handle Updates and Creations
    const upsertPromises = profile.value.links.map(link => {
      // Ignore empty rows
      if (!link.link_url || link.link_url.trim() === '') return null;

      // Create or update the link
      if (link.link_id) {
        return api.put(`/link/${link.link_id}`, link);
      } else {
        return api.post(`/link`, link);
      }
    }).filter(p => p !== null);

    // Execute all API calls
    await Promise.all([...deletePromises, ...upsertPromises]);
    
    // Clear the delete tracking for next time
    linksToDelete.value = [];
    
    router.push({ name: 'profile', params: { id: route.params.id } });
  } catch (error) {
    console.error("Save failed:", error);
    alert("There was an error saving your changes.");
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
    <div class="row justify-content-center">
      <div class="col-lg-10">
        
        <div class="align-items-center mb-4">
          <h2 class="sec-title mb-1">Edit Profile</h2>
        </div>
      <div class="card stat-card card-dark border-0 p-4">
          <h5 class="stat-title mb-4">Profile Image</h5>
          <div class="d-flex align-items-center gap-4">
              <img :src="profile.profile_image_url || '/src/assets/default.jpg'" @error="(e) => e.target.src = '/default.jpg'" class="profile-pic" style="flex-shrink: 0;"/>
              <div class="flex-grow-1">
                  <label class="form-label fw-bold">Profile Image URL</label>
                  <input v-model.lazy="profile.profile_image_url" class="form-control" placeholder="Link to your profile picture"/>
              </div>
          </div>
      </div>
        <div class="row justify-content-center">
          <div class="card-body p-4">
            
            <div class="card stat-card card-dark border-0 h-auto p-4 mb-4">
              <h5 class="stat-title mb-4">Basic Information</h5>
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label fw-bold">First Name</label>
                  <input v-model="profile.user.first_name" class="form-control form-control-lg"/>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-bold">Last Name</label>
                  <input v-model="profile.user.last_name" class="form-control form-control-lg"/>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-bold">Preferred Name</label>
                  <input v-model="profile.preferred_name" class="form-control form-control-lg"/>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-bold">Degree Title</label>
                  <input v-model="profile.degree_title" class="form-control"/>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-bold">Specialisation</label>
                  <input v-model="profile.specialisation" class="form-control"/>
                </div>
              </div>
            </div>

            <div class="card stat-card card-dark border-0 h-auto p-4 mb-4">
              <h5 class="stat-title mb-4">About You</h5>
              <div class="mb-3">
                <label class="form-label fw-bold">Personal Introduction</label>
                <textarea v-model="profile.personal_intro" class="form-control" rows="4"></textarea>
              </div>
            </div>

            <div class="card stat-card card-dark border-0 h-auto p-4 mb-4">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="stat-title mb-0">Professional Links</h5>
                <button @click="addLink" class="btn  btn-ql rounded-pill px-4">Add New Link</button>
              </div>       
              <div class="row g-3">
                <div v-for="(link, index) in profile.links" :key="index" class="col-12 border-bottom border-secondary pb-3 mb-2">
                  <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                      <label class="form-label fw-bold">Link Title</label>
                      <input v-model="link.link_label" class="form-control"/>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-bold">URL</label>
                      <input v-model="link.link_url" class="form-control"/>
                    </div>
                    <div class="col-md-2">
                      <button @click="removeLink(index)" class="btn btn-filter px-4" title="Remove Link">
                      Delete</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="profile.links.length === 0" class="text-center py-3">
              <p class="text-muted small mb-0">No links to show</p>
            </div>
          </div>
        </div>
            
        <footer class="border-top pt-4 d-flex justify-content-end gap-2">
          <button class="btn btn-filter px-4" @click="cancel">Cancel</button>
          <button class="btn btn-ql rounded-pill px-4" @click="saveChanges">Save Changes</button>
        </footer>

      </div>
    </div>
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