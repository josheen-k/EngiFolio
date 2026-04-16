<template>
    <nav class="navbar px-4 px-md-5 py-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-5">
            <div class="logo">
                <img src="@/assets/engifolio-logo.png" alt="EngiFolio" class="logo-img" />
            </div>
            <ul class="navbar-nav flex-row gap-2">
                <li class="nav-item"><router-link class="nav-link" active-class="active-link" :to="`/student/dashboard/${$route.params.id}`">Dashboard</router-link></li>
                <li><router-link class="nav-link" active-class="active-link" to="/student/eaCompetency">Competencies</router-link></li>
                <li><router-link class="nav-link" active-class="active-link" to="/student/career-planning">Goals</router-link></li>
                <li><router-link class="nav-link" active-class="active-link" to="/student/networking">Networking</router-link></li>
                <li><router-link class="nav-link" active-class="active-link" to="/student/career-development">CDL</router-link></li>
            </ul>
        </div>

        <div class="profile" ref="dropdown">
            <img class="rounded-circle av-img"
                src="https://img.freepik.com/free-photo/young-woman-attend-courses-girl-student-studying-holding-notebooks-showing-thumb-up-approval-recommending-company-standing-blue-background_1258-70145.jpg"
                alt="Profile" @click="openDropdown">

            <div v-if="isOpen" class="dd">
                <router-link :to="`/profile/${$route.params.id}`" class="dd-item" @click="closeDropdown">Profile</router-link>
                <router-link :to="`/settings/profile/${$route.params.id}`" class="dd-item" @click="closeDropdown">Settings</router-link>
                <router-link to="/" class="dd-item logout" @click="closeDropdown">Logout</router-link>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref } from 'vue';
import { onClickOutside } from '@vueuse/core'
import { useRoute } from 'vue-router';

const route = useRoute();
const id = route.params.id;
const isOpen = ref(false);
const dropdown = ref(null);

const openDropdown = () => {
    isOpen.value = !isOpen.value
}
const closeDropdown = () => {
    isOpen.value = false;
}

onClickOutside(dropdown, () => {
    isOpen.value = false;
})
</script>

<style scoped>
.logo-img {
    height: 2.5rem;
    object-fit: contain;
}

.nav-link {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 1.25rem;
    color: #444444;
    border-radius: 1.8rem;
    padding: 0.5rem 1rem;
}

.nav-link:hover {
    background: #f0f0f0;
    color: #222222;
}

.active-link {
    background: #1a1a1a;
    color: #ffffff;
    font-weight: lighter;
}

.active-link:hover {
    background: #1a1a1a;
    color: #ffffff;
}

.av-img {
    width: 3.5rem;
    height: 3.5rem;
    object-fit: cover;
    border: 2px solid #c5c5c5;
    cursor: pointer;
}

.profile {
    position: relative;
    display: inline-block;
}

.dd {
    position: absolute;
    right: -0.625rem;
    top: 120%;
    width: 6rem;
    padding: 0.4rem;
    background: #ffffff;
    border: 1px solid #bebebe;
    border-radius: 0.8rem;
    box-shadow: 0 0.5rem 1.4rem #bbbbbba9;
    z-index: 1;
}

.dd-item {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 0.9rem;
    color: #444444;
    display: block;
    padding: 0.5rem 0.8rem;
    text-align: center;
    cursor: pointer;
    text-decoration: none;
}

.dd-item:hover {
    background: #f1f1f1;
    border-radius: 0.5rem;
}

.logout {
    color: #ff746c;
}
</style>