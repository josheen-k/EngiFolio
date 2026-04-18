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
			<div class="d-flex align-items-center gap-3">
				<!-- if we keep the router to go to home, it appears as if the logo signed the user out which looks bad-->
				<!-- <router-link to="/"> -->
				<div class="navLogo"></div>
				<!-- </router-link> -->

				<ul class="nav-bar">
					<li class="nav-item">
						<router-link active-class="active-link" :to="`/student/dashboard/${$route.params.id}`">Dashboard</router-link>
					</li>
					<li class="nav-item">
						<router-link active-class="active-link" :to="`/student/eaCompetency/${$route.params.id}`">Competencies</router-link>
					</li>
					<li class="nav-item">
						<router-link active-class="active-link" :to="`/student/career-planning/${$route.params.id}`">Goals</router-link>
					</li>
					<li class="nav-item">
						<router-link active-class="active-link" :to="`/student/networking/${$route.params.id}`">Networking</router-link>
					</li>
					<li class="nav-item">
						<router-link active-class="active-link" :to="`/student/career-development/${$route.params.id}`">CDL</router-link>
					</li>
				</ul>
			</div>

			<div class="nav-item" ref="dropdown">
				<img class="rounded-circle av-img"
					src="https://img.freepik.com/free-photo/young-woman-attend-courses-girl-student-studying-holding-notebooks-showing-thumb-up-approval-recommending-company-standing-blue-background_1258-70145.jpg"
					alt="Profile" @click="openDropdown">

				<div v-if="isOpen" class="dd">
					<router-link :to="`/profile/${$route.params.id}`" class="dd-item"
						@click="closeDropdown">Profile</router-link>
					<router-link :to="`/settings/profile/${$route.params.id}`" class="dd-item"
						@click="closeDropdown">Settings</router-link>
					<router-link to="/" class="dd-item logout" @click="closeDropdown">Logout</router-link>
				</div>
			</div>
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
	background-color: #130f4d;
	padding: 0;
}

.nav-bar {
	display: flex;
	list-style-type: none;
	margin: 0;
	padding: 0;
	align-items: center;
	gap: 0.8rem;
}

.nav-item {
	display: flex;
	align-items: center;
	position: relative;
}

.nav-item a {
	color: #a7a7a7;
	font-family: 'Montserrat Alternates', sans-serif;
	font-size: 1.2rem;
	text-decoration: none;
	display: flex;
	align-items: center;
    padding: 0.6rem;
	transition: color 0.3s ease;
	height: 30px;
}

.nav-item a:hover, .nav-item a.active-link {
    color: #ffffff;
}

.navLogo {
	width: 6.25rem;
	height: 3.75rem;
	margin: 0 1.5rem;
	background: linear-gradient(45deg, #d9bebe, #6b6be4);
	-webkit-mask: url('@/assets/engiFolio.png') no-repeat center;
	-webkit-mask-size: contain;
	mask: url('@/assets/engiFolio.png') no-repeat center;
	mask-size: contain;
}

.av-img {
	width: 3rem;
	height: 3rem;
	object-fit: cover;
	border: 2px solid #c5c5c5;
	cursor: pointer;
	margin: 0 20px;
}

.dd {
	position: absolute;
	right: 10px;
	top: 100%;
	width: 100px;
	padding: 0.4rem;
	background: #ffffff;
	border: 1px solid #bebebe;
	border-radius: 0.8rem;
	box-shadow: 0 0.5rem 1.4rem rgba(0, 0, 0, 0.3);
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