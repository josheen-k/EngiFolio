<script setup>
  import { onMounted, ref } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router'
  import Footer from '@/components/Footer.vue';

  const isLoggedIn = ref(false);
  const router = useRouter();

  const signIn = () => {
    isLoggedIn.value = true
    router.push('/student/dashboard')
  };

  const signOut = () => {
    isLoggedIn.value = false
    router.push('/')
  };
</script>

<template>
  <div class="app">
    <router-view :key="$route.fullPath" />
    <Footer/>
  </div>
</template>

<style>
html, body {
  min-height: 100vh;
  margin: 0;
  padding: 0;
}

.app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

footer {
  margin-top: auto;
}
</style>