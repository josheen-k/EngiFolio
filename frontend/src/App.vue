<script setup>
  import { onMounted, ref } from 'vue'
  import axios from 'axios'

  const message = ref('Waiting for Laravel...')

  // This function talks to the backend
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
  <h1>Vue + Laravel Connection</h1>
  <p>Message from Backend: <strong>{{ message }}</strong></p>
</template>
