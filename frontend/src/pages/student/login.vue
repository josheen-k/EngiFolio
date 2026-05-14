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
    <main class="login-content">
      <div class="login-card shadow-lg">
        <header class="text-center mb-4">
          <h1 class="fw-bold login-title mb-3">EngiFolio Login</h1>
		  <p class="text-muted small">Sign in to your portfolio</p>
        </header>

        <section>
          <form>
            <div class="mb-3">
              <input type="text" class="form-control login-input" id="username" placeholder="Username" required>
            </div>
            <div class="mb-4">
              <input type="password" class="form-control login-input" id="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-login w-100 mb-4">Login</button>
          </form>
        </section>

        <footer class="login-footer border-top pt-4 text-center">
          <p class="small text-muted mb-2">Hardcoded Links:</p>
          <div class="d-flex justify-content-center gap-2 mb-2">
            <router-link to="/student/dashboard/1" class="btn btn-outline-secondary btn-sm rounded-pill">Student 1</router-link>
            <router-link to="/student/dashboard/2" class="btn btn-outline-secondary btn-sm rounded-pill">Student 2</router-link>
          </div>
					<div class="d-flex justify-content-center gap-2">
            <router-link to="/" class="btn btn-outline-secondary btn-sm rounded-pill">Teacher</router-link>
            <router-link to="/admin/1" class="btn btn-outline-secondary btn-sm rounded-pill">Admin</router-link>
          </div>
        </footer>
      </div>
    </main>
  </div>
</template>

<style scoped>
	.login-page {
		display: flex;
		background-color: #f8f9fa;
		min-height: calc(100vh - 135px);
		background: linear-gradient(#f0f0ff 10%, #e8e8f8 90%);
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
		padding: 0.65rem 1rem;
	}

	.login-input:focus {
		border-color: #140f50;
		box-shadow: 0 0 0 3px rgba(20, 15, 80, 0.1);
		outline: none;
	}

	.login-content {
		flex: 1;
		display: flex;
		justify-content: center;
		align-items: center;
		padding: 20px;
	}

	.login-card {
		background: white;
		width: 100%;
		max-width: 420px;
		padding: 2.5rem;
		border-radius: 1.5rem;
		border-top: 2px solid #140f50;
	}

	.nav-wrapper {
		width: 100%;
		height: 60px;
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

	@media (max-width: 768px) {
		.login-footer {
			display: none;
		}
		.login-title {
			padding: 2rem;
		}
	}
</style>

