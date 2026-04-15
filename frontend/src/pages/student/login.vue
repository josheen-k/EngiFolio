<script setup>
    import { ref, onMounted } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import axios from 'axios';
    import Navbar from '@/components/Navbar.vue'
    import Footer from '@/components/Footer.vue'


    const router = useRouter();
    const route = useRoute();
</script>

<template>
	<nav class="navbar px-4 px-md-5 py-3 d-flex justify-content-between align-items-center">
		<div class="logo">
			<img src="@/assets/engifolio-logo.png" alt="EngiFolio" class="logo-img" />
		</div>
		<router-link to="/" class="btn btn-dark btn-signin px-4">Back to homepage</router-link>
	</nav>

	<div class="container py-5">
		<div class="row justify-content-center">
			<div class="col-lg-10">
				<header>
					<div class="d-flex align-items-center mb-4 px-3">
							<h1 class="h2 fw-bold mb-0">Login</h1>
					</div>
				</header>
				<main>
					<div class="container mt-5" style="max-width: 400px;">
						<form>
							<div class="mb-3">
								<label for="username" class="form-label">Username</label>
								<input type="text" class="form-control" id="username" placeholder="Username" required>
							</div>
							<div class="mb-3">
								<label for="password" class="form-label">Password</label>
								<input type="password" class="form-control" id="password" placeholder="Password" required>
							</div>
							<button type="submit" class="btn btn-primary">Login</button>
						</form>
					</div>
				</main>
				<footer>
					<router-link to="/profile/1" class="btn btn-link btn-sm">Student Profile 1</router-link>
					<router-link to="/profile/2" class="btn btn-link btn-sm">Student Profile 2</router-link>
				</footer>
			</div>
		</div>  
	</div>
	
  <Footer />
</template>

<style scoped>
.logo-img {
  height: 2.5rem;
  object-fit: contain;
}

.btn {
    font-family: 'Montserrat Alternates', sans-serif;
    border-radius: 30px;
    font-size: 1.25rem;
}
</style>