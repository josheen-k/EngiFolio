<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const username = ref('')
const password = ref('')

const login = () => {
  let user = null

// for testing purposes
  if (username.value === 'Alex') {
    user = {
      user_id: 1,
      role: 'student'
    }
  }

  else if (username.value === 'Kate') {
    user = {
      user_id: 2,
      role: 'student'
    }
  }

  else if (username.value === 'Eleanor') {
    user = {
      user_id: 4,
      role: 'staff'
    }
  }

  if (!user || password.value !== 'testpassword') {
    alert('Invalid username or password')
    return
  }

  // Save logged in user
  localStorage.setItem(
    'user',
    JSON.stringify(user)
  )

  // Redirect by role
  if (user.role === 'staff') {
    router.push('/staff/dashboard')
  }

  else if (user.role === 'student') {
    router.push(`/student/dashboard/${user.user_id}`)
  }
}
</script>

<template>
  <nav class="nav-wrapper">
    <div>
      <div class="navLogo"></div>
    </div>

    <div class="nav-bar">
      <div class="nav-item">
        <router-link to="/" class="btn-back-custom">
          Go back
        </router-link>
      </div>
    </div>
  </nav>

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-10">

        <header>
          <div class="d-flex align-items-center mb-4 px-3">
            <h1 class="h2 fw-bold mb-0">
              Login
            </h1>
          </div>
        </header>

        <main>
          <div class="container mt-5" style="max-width: 400px;">

            <form @submit.prevent="login">

              <div class="mb-3">
                <label for="username" class="form-label">
                  Username
                </label>

                <input
                  v-model="username"
                  type="text"
                  class="form-control"
                  id="username"
                  placeholder="Username"
                  required
                >
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">
                  Password
                </label>

                <input
                  v-model="password"
                  type="password"
                  class="form-control"
                  id="password"
                  placeholder="Password"
                  required
                >
              </div>

              <button type="submit" class="btn btn-primary">
                Login
              </button>

            </form>

          </div>
        </main>

      </div>
    </div>
  </div>
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
  align-items: center;
}

.nav-item {
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
  transition: color 0.3s ease;
}

.btn-back-custom:hover {
  color: #ffffff;
}

.btn {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 30px;
}

.test-users {
  background: #f5f5f5;
  border-radius: 14px;
  padding: 16px;
  font-size: 0.95rem;
}
</style>