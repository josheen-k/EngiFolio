<script setup>
import { ref } from 'vue';
import { onClickOutside } from '@vueuse/core';
import { useRoute } from 'vue-router';

const route = useRoute();
const isOpen = ref(false);
const dropdown = ref(null);

const openDropdown = () => {
  isOpen.value = !isOpen.value;
};
const closeDropdown = () => {
  isOpen.value = false;
};

onClickOutside(dropdown, () => {
  isOpen.value = false;
});
</script>

<template>
  <div class="app-container">
    <div class="nav-wrapper">
      <div>
        <router-link to="/">
          <img class="navLogo" src="@/assets/Logo.png" alt="Adelaide University">
        </router-link>
      </div>

      <ul class="nav-bar">
        <li class="nav-item">
          <router-link :to="`/student/dashboard/${$route.params.id}`">Dashboard</router-link>
        </li>
        <li class="nav-item">
          <router-link :to="`/student/eaCompetency/${$route.params.id}`">Competencies</router-link>
        </li>
        <li class="nav-item">
          <router-link :to="`/student/career-planning/${$route.params.id}`">Goals</router-link>
        </li>
        <li class="nav-item">
          <router-link :to="`/student/networking/${$route.params.id}`">Networking</router-link>
        </li>
        <li class="nav-item">
          <router-link :to="`/student/career-development/${$route.params.id}`">CDL</router-link>
        </li>

        <li class="nav-item" ref="dropdown">
          <img 
            class="rounded-circle av-img"
            src="https://img.freepik.com/free-photo/young-woman-attend-courses-girl-student-studying-holding-notebooks-showing-thumb-up-approval-recommending-company-standing-blue-background_1258-70145.jpg"
            alt="Profile" 
            @click="openDropdown"
          >
          
          <div v-if="isOpen" class="dd">
            <router-link :to="`/profile/${$route.params.id}`" class="dd-item" @click="closeDropdown">Profile</router-link>
            <router-link :to="`/settings/profile/${$route.params.id}`" class="dd-item" @click="closeDropdown">Settings</router-link>
            <router-link to="/" class="dd-item logout" @click="closeDropdown">Logout</router-link>
          </div>
        </li>
      </ul>
    </div>
    
    <router-view />
  </div>
</template>

<style scoped>
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
		align-items: center;
	}

	.nav-item {
		display: flex;
		align-items: center;
		position: relative;
	}

	.nav-item a {
		color: white;
		font-family: "Roboto Serif", Helvetica, Arial, sans-serif;
		text-decoration: none;
		display: flex;
		align-items: center;
		padding: 0 1.5rem;
		transition: background-color 0.3s ease;
		height: 60px;
		font-weight: 510
	}

	.nav-item a:hover {
		background-color: #020110; 
	}

	.navLogo {
		height: 60px;
		width: auto;
		display: block;
		padding: 10px 20px;
	}

	.av-img {
		width: 3.5rem;
		height: 3.5rem;
		object-fit: cover;
		border: 2px solid #c5c5c5;
		cursor: pointer;
		margin: 0 20px;
	}

	.dd {
		position: absolute;
		right: 10px;
		top: 100%;
		width: 150px;
		padding: 0.4rem;
		background: #ffffff;
		border: 1px solid #bebebe;
		border-radius: 0.8rem;
		box-shadow: 0 0.5rem 1.4rem rgba(0,0,0,0.3);
		z-index: 1000;
	}

	.dd a.dd-item {
		color: #444444;
		font-family: 'Montserrat Alternates', sans-serif;
		font-size: 0.9rem;
		display: block;
		padding: 0.5rem 0.8rem;
		text-align: center;
		height: auto;
		background-color: transparent;
	}

	.dd a.dd-item:hover {
		background-color: #f1f1f1;
		color: #000000;
		border-radius: 0.5rem;
	}

	.dd a.dd-item.logout {
		color: #ff746c;
	}
</style>