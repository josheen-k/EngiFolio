<script setup>
  import { onMounted, ref } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router'

  const isLoggedIn = ref(false);
  const router = useRouter();

  const signIn = () => {
    isLoggedIn.value = true
    router.push('/profile/1')
  };

  const signOut = () => {
    isLoggedIn.value = false
    router.push('/')
  };
</script>

<template>
  <div class="app-container">
        <nav class="navbar px-4 px-md-5 py-3 d-flex justify-content-between align-items-center">

        <div class="logo">
            <img src="@/assets/engifolio-logo.png" alt="EngiFolio" class="logo-img" />
          </div>

          <button  v-if="!isLoggedIn" @click="signIn" class="btn btn-dark btn-signin px-4">Sign in</button>
          <button  v-else @click="signOut" class="btn btn-dark btn-signin px-4">Sign out</button>
      </nav>
      <router-view />
  </div>
</template>

<style scoped>
.app-container {
    font-family: 'Martel', sans-serif;
    color: #1a1a2e;
    background: #fff;
    min-height: 100vh;
    overflow-x: hidden;
}

.logo-img {
  height: 2.5rem;
  object-fit: contain;
}

.btn {
    font-family: 'Montserrat Alternates', sans-serif;
    border-radius: 30px;
    font-size: 1.25rem;
}

.hero-tag {
    border: 1.5px solid #22222275;
    border-radius: 20px;
    padding: 6px 25px;
    font-family: 'Maven Pro', sans-serif;
    font-size: 1rem;
    letter-spacing: 0.05rem;
    color: #22222275;
}

.hero-highlight {
    color: #1d9ecf;
    font-family: 'Kode Mono', monospace;
    font-size: 2.8rem;
}

.hero-title,
.f-title {
    font-size: 3.75rem;
    font-family: 'Martel', sans-serif;
    color: #2b2b2b;
    line-height: 4rem;
}

.hero-desc {
    font-family: 'Maven Pro', sans-serif;
    font-size: 1.2rem;
    color: #2b2b2b;
    text-align: justify;
    width: 90%;
}

.f-icon>img {
    width: 50px;
    height: 50px;
    object-fit: contain;
}

.f-name {
    font-family: 'Kode Mono', monospace;
    font-size: 2rem;
}

.f-desc {
    font-family: 'Maven Pro', sans-serif;
    font-size: 1.2rem;
    color: #555555;
}
</style>