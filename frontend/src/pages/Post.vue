<script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute } from 'vue-router'
  import axios from 'axios';

  const route = useRoute();
  const post = ref(null);

  const viewPost = async () => {
    const response = await axios.get(`http://127.0.0.1:8000/api/posts/${route.params.id}`);
    post.value = response.data;
  };

  onMounted(() => {
    viewPost();
  })
</script>

<template>
  <div class="post" v-if="post">
    <h1>{{ post.title }}</h1>
    <p>{{ post.body }}</p>  
    <p>Posted on: {{ post.created_at }}</p>
    <router-link to="/">Back to Table</router-link>
  </div>

  <div v-else>
    <p>Loading</p>
  </div>
</template>

