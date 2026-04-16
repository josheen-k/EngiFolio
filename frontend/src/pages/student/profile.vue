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

	<body class="container-xl py-5" v-if="profile">
		<div class="row mb-5">
			<header class="col-12">
				<h1 class="display-5 fw-bold mb-0">{{profile.first_name}} {{profile.last_name}}</h1>
				<p class="mb-0" v-if="profile.preferred_name">Preferred Name: {{ profile.preferred_name }}</p>
			</header>
		</div>

		<main class="row g-4 col-lg-8">
			<section class="mb-4">
				<div class="card-header py-3">
					<h5 class="h mb-0 text-primary">Academic Information</h5>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<label class="small text-uppercase fw-bold">Degree</label>
							<p class="lead">{{ profile.degree_title }}</p>
						</div>
						<div class="col-sm-6">
							<label class="small text-uppercase fw-bold">Specialisation</label>
							<p class="lead">{{ profile.specialisation }}</p>
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
				<div class="p-3 border-start border-4">
					{{ profile.upcoming_actions }}
				</div>
			</section>

			<section class="mb-5">
				<h2 class="h5 fw-bold mb-3">Professional Links</h2>
				<table class="table table-hover border-top">
					<tbody>
						<tr v-for="link in profile.links" :key="link.link_id">
							<td class="fw-semibold">{{ link.link_label }}</td>
							<td><a :href="link.link_url" class="text-break">{{ link.link_url }}</a></td>
						</tr>
					</tbody>
				</table>
			</section>

			<div class="gap-2">
				<router-link :to="{name: 'profile-settings', params:{ id: route.params.id }}" class="btn btn-primary">Edit Profile</router-link>
				<router-link to="/" class="btn btn-link text-muted btn-sm">Back to Dashboard</router-link>
			</div>
		</main>

		<footer>
			<Footer />
		</footer>
	</body>

	<div v-else-if="loading" class="text-center py-5">
		<div class="spinner-border" role="status"></div>
		<p>Loading profile...</p>
		<Footer />
	</div>

	<div v-else class="container py-5">
		<div class="alert alert-warning" role="alert">Profile not found.</div>
		<Footer />
	</div>

</template>