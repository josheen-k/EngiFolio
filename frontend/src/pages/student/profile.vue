<script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute } from 'vue-router'
  import axios from 'axios';
  import Navbar from '@/components/Navbar.vue'
  import Footer from '@/components/Footer.vue'

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
  <Navbar />

  <main class="container-xl py-5 px-4" v-if="profile">
    <div class="row mb-5">
      <div class="col-12">
        <h1 class="display-5 fw-bold mb-0">{{profile.first_name}} {{profile.last_name}}</h1>
        <p class="text-muted mb-0" v-if="profile.preferred_name">Goes by: ({{ profile.preferred_name }})</p>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-8">
        <section class="card shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h2 class="h5 mb-0 text-primary">Academic Information</h2>
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
        </section>

        <section class="mb-5">
          <h2 class="h5 fw-bold mb-3">Personal Introduction</h2>
          <p class="text-dark lh-base" style="white-space: pre-line;">
            {{ profile.personal_intro }}
          </p>
        </section>

        <section class="mb-5">
          <h2 class="h5 fw-bold mb-3">Upcoming Actions</h2>
          <div class="p-3 bg-light border-start border-4">
            {{ profile.upcoming_actions }}
          </div>
        </section>

        <section class="mb-5">
          <h2 class="h5 fw-bold mb-3">Professional Links</h2>
          <table class="table table-hover border-top">
            <tbody>
              <tr v-for="link in profile.links" :key="link.link_id">
                <td class="fw-semibold w-25">{{ link.link_label }}</td>
                <td><a :href="link.link_url" target="_blank" class="text-break">{{ link.link_url }}</a></td>
              </tr>
            </tbody>
          </table>
        </section>
      </div>

      <div class="d-flex flex-column gap-2">
          <router-link :to="{name: 'profile-settings', params:{ id: route.params.id }}" class="btn btn-outline-dark">
            Edit Profile
          </router-link>
          <router-link to="/" class="btn btn-link text-muted btn-sm">
            Back to Dashboard
          </router-link>
      </div>
    </div>
  </main>

  <div v-else-if="loading" class="text-center py-5">
    <div class="spinner-border" role="status"></div>
    <p>Loading profile...</p>
  </div>

  <div v-else class="container py-5">
    <div class="alert alert-warning" role="alert">
      Profile not found.
    </div>
  </div>

  <Footer />
</template>