<script setup>

</script>

<template>
	<nav class="nav-wrapper">
		<div>
			<div class="navLogo"></div>
		</div>
		<div class="nav-bar">
			<div class="nav-item">
				<router-link to="/" class="btn-back-custom">Go back</router-link>
			</div>
		</div>
	</nav>
	<div class="login-page">
		<div class="container py-5">
			<div class="row justify-content-center">
				<div class="col-lg-10">
					<header>
						<div class="d-flex justify-content-center mb-4 px-3">
								<h1 class="h2 fw-bold mb-0 login-title">EngiFolio Login</h1>
						</div>
					</header>
					<main>
						<div class="container mt-5" style="max-width: 400px;">
							<form>
								<div class="mb-3">
									<label for="username" class="form-label">Username</label>
									<input type="text" class="form-control login-input" id="username" placeholder="Username" required>
								</div>
								<div class="mb-3">
									<label for="password" class="form-label">Password</label>
									<input type="password" class="form-control login-input" id="password" placeholder="Password" required>
								</div>
								<button type="submit" class="btn btn-login w-100 mb-3">Login</button>
							</form>
						</div>
					</main>
					<footer class="login-footer border-top pt-3 mt-2 text-center">
						<p class="small text-muted mb-2">Hardcoded Links:</p>
						<div class="d-flex justify-content-center gap-2">
							<router-link to="/student/dashboard/1" class="btn btn-outline-secondary btn-sm rounded-pill">Student Profile 1</router-link>
							<router-link to="/student/dashboard/2" class="btn btn-outline-secondary btn-sm rounded-pill">Student Profile 2</router-link>
						</div>
					</footer>
				</div>
			</div>  
		</div>
	</div>
</template>

<style scoped>
	.login-page {
		display: flex;
		background-color: #f8f9fa;
	}

	.login-title {
		font-family: 'Maven Pro', sans-serif;
		color: #140f50;
		font-size: 1.8rem;
	}

	.login-input {
		border-radius: 15px;
		border: 1px solid #dee2e6;
		background-color: #fdfdfd;
	}

	.login-card {
		
	}

	.nav-wrapper {
		width: 100vw;
		display: flex;
		justify-content: space-between;
		align-items: center;
		background-color: #140f50;
		padding: 0;
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
		color: #a7a7a7;
		border: none;
		border-radius: 30px;
		padding: 6px 18px;
		font-family: 'Montserrat Alternates', sans-serif;
		font-size: 1.2rem;
		text-decoration: none;
		cursor: pointer;
		transition: color 0.3s ease;
	}

	.btn-back-custom:hover {
		color: #ffffff;
	}

	.btn-login {
		font-family: 'Montserrat Alternates', sans-serif;
		border-radius: 1.5rem;
		font-size: 1rem;
		font-weight: 400;
		color: white;
		background: #140f50;
	}

	.btn-login:hover {
		background: #646464;
		color: #ffffff;
	}

</style>

