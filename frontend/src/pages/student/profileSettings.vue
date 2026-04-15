<script setup>
  import { ref, onMounted } from 'vue';
  import { useRouter, useRoute } from 'vue-router';
  import axios from 'axios';

  const router = useRouter();
  const route = useRoute();
  const profile = ref(null);
  const loading = ref(true);

  const loadProfile = async () => {
    // Get profile data, throw error if unsuccessful
    try {
      const response = await axios.get(`http://127.0.0.1:8000/api/profile/${route.params.id}`);
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
    await axios.put(`http://127.0.0.1:8000/api/profile/${route.params.id}`, profile.value);

    for (const link of profile.value.links) {
      // Check if link is empty
      if (link.link_url.trim() === '') {
        if (link.link_id) {
          await axios.delete(`http://127.0.0.1:8000/api/link/${link.link_id}`);
        }
      } else {
        if (link.link_id) {
          // Update link
          await axios.put(`http://127.0.0.1:8000/api/link/${link.link_id}`, link);
        } else {
          // Create link
          await axios.post(`http://127.0.0.1:8000/api/link`, link);
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
  <body class="container py-5" v-if="profile">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        
        <div class="align-items-center mb-4">
          <h2 class="fw-bold">Edit Profile Settings</h2>
        </div>

        <div class="card shadow-sm border-0">
          <div class="card-body p-4 p-md-5">
            
            <div class="mb-5">
              <h5 class="text-primary mb-4 fw-bold">Basic Information</h5>
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label fw-bold">First Name</label>
                  <input v-model="profile.first_name" class="form-control form-control-lg"/>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-bold">Last Name</label>
                  <input v-model="profile.last_name" class="form-control form-control-lg"/>
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

            <div class="mb-5">
              <h5 class="text-primary mb-4 fw-bold">About You</h5>
              <div class="mb-3">
                <label class="form-label fw-bold">Personal Introduction</label>
                <textarea v-model="profile.personal_intro" class="form-control" rows="4"></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Upcoming Actions</label>
                <textarea v-model="profile.upcoming_actions" class="form-control" rows="2"></textarea>
              </div>
            </div>

            <div class="mb-5">
              <h5 class="text-primary mb-4 fw-bold">Professional Links</h5>
              
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
              <button class="btn btn-outline-secondary btn-sm" @click="cancel">Cancel</button>
              <button class="btn btn-dark btn-sm" @click="saveChanges">Save All Changes</button>
            </footer>

          </div>
        </div>
      </div>
    </div>
    
    <Footer />
  </body>

  <div v-else class="container py-5 text-center">
    <p class="text-muted small">Loading settings...</p>
    <Footer />
  </div>
</template>