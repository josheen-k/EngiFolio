<script setup>
  import { onMounted, ref } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router'

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
  <router-view />
</template>
