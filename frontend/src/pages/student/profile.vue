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
  <div class="profile" v-if="profile">
    <h1 class="ps-3">{{profile.first_name}} {{profile.last_name}}</h1>
    <p class="ps-3" v-if="profile.preferred_name"><strong>Preferred Name:</strong> {{ profile.preferred_name }}</p>
    
    <div class="degree-details ps-3">
      <p><strong>Degree:</strong> {{ profile.degree_title }}</p>
      <p><strong>Specialisation:</strong> {{ profile.specialisation }}</p>
    </div>

    <div class="intro ps-3">
      <h3>Personal Introduction: </h3>
      <p>{{ profile.personal_intro }}</p>
    </div>

    <div class="intro ps-3">
      <h3>Upcoming Actions: </h3>
      <p>{{ profile.upcoming_actions}}</p>
    </div>

    <router-link :to="{name: 'profile-settings', params:{ id: profile.profile_id }}" class="btn btn-outline-primary me-2 ps-3">Edit Profile</router-link>

    <router-link to="/" class="ps-3">Back to Dashboard</router-link>
  </div>

  <div v-else-if="loading">
    <p class="ps-3">Loading profile...</p>
  </div>

  <div v-else>
    <p class="ps-3">Profile not found.</p>
  </div>
</template>