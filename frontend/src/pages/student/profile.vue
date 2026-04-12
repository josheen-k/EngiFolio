<script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute } from 'vue-router'
  import axios from 'axios';

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

  onMounted(() => {
    loadProfile();
  })
</script>

<template>
  <div class="container py-5" v-if="profile">
    <div class="row justify-content-center">
      <div class="col-md-8">
        
        <div class="card shadow-sm mb-4">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h1 class="display-5 fw-bold mb-0">{{profile.first_name}} {{profile.last_name}}</h1>
                <p class="text-muted mb-0" v-if="profile.preferred_name">({{ profile.preferred_name }})</p>
              </div>
              <span class="badge bg-primary rounded-pill px-3 py-2">Student</span>
            </div>
          </div>
        </div>

        <div class="card shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary">Academic Information</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-sm-6 mb-3">
                <label class="text-muted small text-uppercase fw-bold">Degree</label>
                <p class="lead mb-0">{{ profile.degree_title }}</p>
              </div>
              <div class="col-sm-6 mb-3">
                <label class="text-muted small text-uppercase fw-bold">Specialisation</label>
                <p class="lead mb-0">{{ profile.specialisation }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <div class="mb-4">
              <h5 class="border-bottom pb-2">Personal Introduction</h5>
              <p class="text-secondary" style="white-space: pre-line;">{{ profile.personal_intro }}</p>
            </div>

            <div class="mb-4">
              <h5 class="border-bottom pb-2">Upcoming Actions</h5>
              <p class="text-secondary">{{ profile.upcoming_actions }}</p>
            </div>

            <div>
              <h5 class="border-bottom pb-2">Professional Links</h5>
              <div v-if="profile.links && profile.links.length > 0" class="list-group list-group-flush">
                <a 
                  v-for="link in profile.links" 
                  :key="link.link_id" 
                  :href="link.link_url" 
                  target="_blank" 
                  class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                >
                  <span class="fw-bold">{{ link.link_label }}</span>
                  <span class="text-primary small text-truncate ms-2">{{ link.link_url }}</span>
                </a>
              </div>
              <p v-else class="text-muted italic">No links added yet.</p>
            </div>
          </div>
        </div>

        <div class="d-flex align-items-center">
          <router-link :to="{name: 'profile-settings', params:{ id: route.params.id }}" class="btn btn-primary px-4">Edit Profile</router-link>
          <router-link to="/" class="btn btn-outline-secondary">Back to Dashboard</router-link>
        </div>
      </div>
    </div>
  </div>

  <div v-else-if="loading" class="container py-5 text-center">
    <p class="mt-2 text-muted">Loading profile...</p>
  </div>

  <div v-else class="container py-5">
    <div class="alert alert-warning" role="alert">
      Profile not found.
    </div>
  </div>
</template>