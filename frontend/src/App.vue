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

  // const response = ref();

  // const getValue = async () => {
  //     try {
  //         response.value = await axios.get("/api/test-me");
  //     } catch (error) {
  //         // Do something with the error
  //         console.log(error);
  //     }
  // };

  onMounted(() => {
    fetchData()
  })
</script>

<template>
  <h1>Vue + Laravel Connection</h1>
  <p>Message from Backend: <strong>{{ message }}</strong></p>
  <h1>TEST</h1>
  <div>
    <h2>CRUD</h2>
    <!-- <button @click.prevent="tiggerEndpoint">Trigger Endpoint</button>
    <p v-if="response">{{ response.data }}</p> -->

    <div v-for="p in posts" :key="p.id">
        {{ p.title }} - {{ p.body }}
    </div>

    <input v-model="name" placeholder="Name"/>
    <input v-model="student_id" placeholder="ID"/>
    <input v-model="grade" placeholder="Grade"/>
    <button @click="addPost">Add post</button>
  </div>
</template>

<script>
import post from '@/stores/post';

</script>