<script setup>
  import { ref, onMounted } from 'vue';
  import { useRouter, useRoute } from 'vue-router';
  import Navbar from '@/components/Navbar.vue'
  import api from "@/services/api";

  const router = useRouter();
  const route = useRoute();
  const profile = ref(null);
  const loading = ref(true);

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

  const getLink = (type) => {
    if (!profile.value) return { link_url: '' };
  
    // Match link type
    let found = profile.value.links.find(l => l.link_type === type);
    if (!found) {
      // Create the object structure if not found
      found = { 
        link_type: type, 
        link_label: type.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' '),
        link_url: '', 
        profile_id: route.params.id 
      };
      // Add to profile
      profile.value.links.push(found);
    }
    return found;
  };

  const saveChanges = async () => {
    await  api.put(`/profile/${route.params.id}`, profile.value);

    for (const link of profile.value.links) {
      // Check if link is empty
      if (link.link_url.trim() === '') {
        if (link.link_id) {
          await api.delete(`/link/${link.link_id}`);
        }
      } else {
        if (link.link_id) {
          // Update link
          await api.put(`/link/${link.link_id}`, link);
        } else {
          // Create link
          await api.post(`/link`, link);
        }
      }
    }
    router.push({ name: 'profile', params: { id: route.params.id } });
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
              <h5 class="stat-title mb-4">Professional Links</h5>
              
              <div class="row g-4">
                <div class="col-md-6">
                  <label class="form-label fw-bold">LinkedIn</label>
                  <div class="input-group">
                    <input v-model="getLink('linkedin').link_url" class="form-control"/>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-bold">GitHub</label>
                  <div class="input-group">
                    <input v-model="getLink('github').link_url" class="form-control"/>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-bold">Resume Link</label>
                  <div class="input-group">
                    <input v-model="getLink('resume').link_url" class="form-control"/>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-bold">Portfolio / Website</label>
                  <div class="input-group">
                    <input v-model="getLink('portfolio').link_url" class="form-control"/>
                  </div>
                </div>

                <div class="col-6">
                  <label class="form-label fw-bold">Cover Letter Link</label>
                  <div class="input-group">
                    <input v-model="getLink('cover_letter').link_url" class="form-control"/>
                  </div>
                </div>
              </div>
            </div>
            <footer class="border-top pt-4 d-flex justify-content-end gap-2">
              <button class="btn btn-filter px-4" @click="cancel">Cancel</button>
              <button class="btn btn-ql rounded-pill px-4" @click="saveChanges">Save Changes</button>
            </footer>
          </div>
        </div>
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