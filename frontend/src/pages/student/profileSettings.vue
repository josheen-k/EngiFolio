<script setup>
    import { ref, onMounted } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import axios from 'axios';


    const router = useRouter();
    const route = useRoute();
    const profile = ref(null);
    const loading = ref(true);

    const loadProfile = async () => {
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
  <div class="container py-5" v-if="profile">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        
        <div class="d-flex align-items-center justify-content-between mb-4 px-3">
          <h1 class="h2 fw-bold mb-0">Edit Profile Settings</h1>
        </div>

        <div class="card shadow-sm border-0">
          <div class="card-body p-4 p-md-5">
            
            <div class="mb-5">
              <h5 class="text-primary mb-4 fw-bold"><i class="bi bi-person-fill me-2"></i>Basic Information</h5>
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label fw-semibold">First Name</label>
                  <input v-model="profile.first_name" class="form-control form-control-lg"/>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-semibold">Last Name</label>
                  <input v-model="profile.last_name" class="form-control form-control-lg"/>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-semibold">Preferred Name</label>
                  <input v-model="profile.preferred_name" class="form-control form-control-lg"/>
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold">Degree Title</label>
                  <input v-model="profile.degree_title" class="form-control"/>
                </div>
              </div>
            </div>

            <div class="mb-5">
              <h5 class="text-primary mb-4 fw-bold"><i class="bi bi-chat-left-text-fill me-2"></i>About You</h5>
              <div class="mb-3">
                <label class="form-label fw-semibold">Personal Introduction</label>
                <textarea v-model="profile.personal_intro" class="form-control" rows="4"></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Upcoming Actions</label>
                <textarea v-model="profile.upcoming_actions" class="form-control" rows="2"></textarea>
              </div>
            </div>

            <div class="mb-5">
              <h5 class="text-primary mb-4 fw-bold"><i class="bi"></i>Professional Links</h5>
              <p class="text-muted small mb-4">Empty links will not appear on your profile.</p>
              
              <div class="row g-4">
                <div class="col-md-6">
                  <label class="form-label fw-semibold">LinkedIn</label>
                  <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi"></i></span>
                    <input v-model="getLink('linkedin').link_url" class="form-control"/>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-semibold">GitHub</label>
                  <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi"></i></span>
                    <input v-model="getLink('github').link_url" class="form-control"/>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-semibold">Resume Link</label>
                  <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi"></i></span>
                    <input v-model="getLink('resume').link_url" class="form-control"/>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Portfolio / Website</label>
                  <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi"></i></span>
                    <input v-model="getLink('portfolio').link_url" class="form-control"/>
                  </div>
                </div>

                <div class="col-12">
                  <label class="form-label">Cover Letter Link</label>
                  <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi"></i></span>
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
  </div>

  <div v-else class="container py-5 text-center">
    <p class="text-muted small">Loading settings...</p>
  </div>
</template>