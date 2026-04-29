<script setup>
    import { ref, onMounted } from 'vue';
    import { useRoute } from 'vue-router'
    import Navbar from '@/components/Navbar.vue'
	import defaultAvatar from '@/assets/default.jpg';
    import api from "@/services/api";

    const route = useRoute();
    const profile = ref(null);
    const loading = ref(true);

    const loadProfile = async () => {
		try {
			const response = await api.get(`/profile/${route.params.id}`);
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
    <body class="container-xl py-5" v-if="profile">
        <header class="mb-5">
            <div class="d-flex align-items-center gap-4">        
                <img :src="profile.profile_image_url || defaultAvatar" @error="(e) => e.target.src = defaultAvatar" alt="Profile Picture" class="profile-pic"/>

                <div class="text-start">
                    <h1 class="sec-title mb-0">{{profile.user.first_name}} {{profile.user.last_name}}</h1>
                    <p class="stat-title mb-0" v-if="profile.preferred_name">
                        Preferred Name: {{ profile.preferred_name }}
                    </p>
                </div>
            </div>
        </header>

        <main class="row g-4 justify-content-center">
            <section class="mb-4">
                <div class="card border-0 h-auto p-4">                        
                    <div class="card-header py-3">
                        <h5 class="sec-title">Academic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="table-link text-uppercase d-block">Degree</label>
                                <p class="lead">{{ profile.degree_title }}</p>
                            </div>
                            <div class="col-sm-6">
                                <label class="table-link text-uppercase d-block">Specialisation</label>
                                <p class="lead">{{ profile.specialisation }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-5 card border-0 h-auto p-4">
                <h2 class="sec-title card-header py-3">Personal Introduction</h2>
                <p class="lead ps-3">{{ profile.personal_intro }}</p>
            </section>

            <section class="mb-5 card border-0 h-auto p-4">
                <h2 class="sec-title card-header">Professional Links</h2>
                <table class="table table-hover border-top">
                    <tbody>
                        <tr v-for="link in profile.links" :key="link.link_id">
                            <td class="fw-semibold ps-3">{{ link.link_label }}</td>
                            <td><a :href="link.link_url" class="text-break">{{ link.link_url }}</a></td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <div class="d-flex gap-3 justify-content-center mt-4 mb-5">
                <router-link :to="{name: 'profile-settings', params:{ id: route.params.id }}" class="btn btn-ql rounded-pill px-5">Edit Profile</router-link>
                <router-link class="btn btn-filter px-4" :to="`/student/dashboard/${$route.params.id}`">Back to Dashboard</router-link>
            </div>
        </main>
    </body>

    <div v-else-if="loading" class="text-center py-5">
        <div class="spinner-border" role="status"></div>
        <p>Loading profile...</p>
    </div>

    <div v-else class="container py-5">
        <div class="alert alert-warning" role="alert">Profile not found.</div>
    </div>
</template>

<style scoped>
	.sec-title {
		font-family: 'Martel', serif;
		font-size: 2.0rem;
		color: #303030c5;
	}

	.card-dark {
		background: #f1f1f1;
	}

	.btn-filter {
		font-family: 'Montserrat Alternates', sans-serif;
		border-radius: 1.5rem;
		font-weight: lighter;
		background: #e6e6e6;
	}

	.focus-table {
		font-family: 'Maven Pro', sans-serif;
		font-size: 0.95rem;
	}

	.btn-ql {
		font-family: 'Montserrat Alternates', sans-serif;
		font-size: 1rem;
		color: #ffffff;
		background: #555555;
		padding: 0.5rem 1rem;
	}

	.btn-ql:hover {
		color: #ffffff;
		background: #333333;
	}

	.btn-filter:hover {
		background: #666666;
		color: #ffffff;
	}

	.profile-pic {
		width: 150px;
		height: 150px; 
		border-radius: 50%;
		object-fit: cover;   
		border: 3px solid #f1f1f1; 
		background-color: #fff;
		box-shadow: 0 2px 5px rgba(0,0,0,0.05);
	}
</style>