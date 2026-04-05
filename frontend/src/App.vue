<script setup>
  import { onMounted, ref } from 'vue'
  import axios from 'axios'

  const message = ref('Waiting for Laravel...')

  const fetchData = async () => {
    try {
      const response = await axios.get('http://localhost:8000/api/data')
      message.value = response.data.content
    } catch (error) {
      message.value = "Laravel error."
    }
  }

  onMounted(() => {
    fetchData()
  })
</script>

<template>
  <p>Message from Backend: <strong>{{ message }}</strong></p>
  <div class="app-container">
    <h1>My CRUD App</h1>
    
    <router-view />
  </div>
</template>

<style>
  .app-container {
    font-family: sans-serif;
    padding: 20px;
    max-width: 800px;
    margin: 0 auto;
  }
</style>
