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
</template>

<style>

.nav-item a {
  color: white;
  font-family: "Roboto Serif", Helvetica, Arial, sans-serif;
  text-decoration: none;
  display: flex;
  align-items: center;
  height: 100%;
  padding: 0 1.5rem;
  transition: background-color 0.3s ease;
  height: auto;
}

.navLogo {
  height: 40px;
  width: auto;
  display: block;
  padding: 10px 20px;
}

.nav-item a:hover {
  background-color: #020110; 
}


.nav-wrapper {
  width: 100vw;
  display: flex;
  justify-content: space-between;
  align-items: stretch;
  background-color: #140f50;
  padding: 0;
}

.nav-bar {
  display: flex;
  list-style-type: none;
  margin: 0;
  padding: 0;
}

.nav-item {
  display: flex;
}

</style>
