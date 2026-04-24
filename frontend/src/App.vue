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
  <router-view :key="$route.fullPath" />
  <Footer/>
</template>
