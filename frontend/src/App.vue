<script setup>
  import { onMounted, ref } from 'vue'
  import axios from 'axios'
</script>

<template>
  <div class="app-container">
    <!-- <div class="nav-wrapper"> -->
      <!-- <div><router-link to="/"><img class="navLogo" src="@/assets/Logo.png" alt="Adelaide University"></router-link></div> -->
      <!-- <ul class="nav-bar">
        <li class="nav-item"><router-link to="/">Home</router-link></li>
        <li class="nav-item"><router-link to="/Dashboard">Student Dashboard</router-link></li>
        <li class="nav-item"><router-link to="/profile/username">Student Profile</router-link></li>
        <li class="nav-item"><router-link to="/career-planning">Career Planning</router-link></li>
        <li class="nav-item"><router-link to="/career-development">Career Development Plan</router-link></li>
        <li class="nav-item"><router-link to="/eaCompetency">EA Competency</router-link></li>
      </ul> -->
    <!-- </div> -->
    <router-view />
  </div>
  <div id="app" class="p-4">
    <header>
      <h1>EngiFolio - Industry Contacts</h1>
    </header>

    <main>
      <IndustryContacts />
    </main>
  </div>
</template>

<script setup>
import IndustryContacts from './components/IndustryContact.vue';
</script>

<style>
#app {
  font-family: Arial, Helvetica, sans-serif, Helvetica, Arial, sans-serif;
  color: grey black;
  max-width: 600px;
  margin: 0 auto;
}

header {
  margin-bottom: 2rem;
  text-align: center;
}

main {
  background: whitesmoke;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0,0,0,0.05);
}
</style>
