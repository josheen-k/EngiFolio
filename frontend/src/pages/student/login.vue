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
    <nav class="nav-wrapper">
        <div>
            <router-link to="/">
                <img class="navLogo" src="@/assets/Logo.png" alt="Adelaide University">
            </router-link>
        </div>
        <div class="nav-bar">
            <div class="nav-item">
                <router-link to="/" class="btn-back-custom">Back to homepage</router-link>
            </div>
        </div>
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
					<router-link to="/student/dashboard/1" class="btn btn-link btn-sm">Student Profile 1</router-link>
					<router-link to="/student/dashboard/2" class="btn btn-link btn-sm">Student Profile 2</router-link>
				</footer>
			</div>
		</div>  
    </div>
    
  <Footer />
</template>

<style scoped>
.nav-wrapper {
  width: 100vw;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #140f50;
  padding: 0;
}

.navLogo {
  height: 60px;
  width: auto;
  display: block;
  padding: 10px 20px;
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
  padding-right: 20px;
}

.btn-back-custom {
    background-color: #ffffff;
    color: #140f50;
    border: none;
    border-radius: 30px;
    padding: 8px 25px;
    font-family: 'Montserrat Alternates', sans-serif;
    font-weight: bold;
    font-size: 1.1rem;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-back-custom:hover {
    background-color: #e0e0e0;
    color: #140f50;
}

.btn {
    font-family: 'Montserrat Alternates', sans-serif;
    border-radius: 30px;
}

form .btn-primary:hover {
    background-color: #1d9ecf;
}
</style>