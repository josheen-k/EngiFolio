<script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute } from 'vue-router'
  import axios from 'axios';

  const posts = ref([]); 
  const newPost = ref({ title: '', body: '' }); 

  // Load the posts
  const loadPosts = async () => {
    const response = await axios.get('http://127.0.0.1:8000/api/posts');
    posts.value = response.data;
  };

  // Add A Post
  const addPost = async () => {
      await axios.post('http://127.0.0.1:8000/api/posts', newPost.value);
      newPost.value = { title: '', body: '' };
      await loadPosts();
  };

    // Remove a post
  const removePost = async (id) => {
    try {
        await axios.delete(`http://127.0.0.1:8000/api/posts/${id}`);
        await loadPosts();
    } catch (e) {
        console.error(e);
    }
  };

  onMounted(() => {
    loadPosts();
});


</script>

<template>
  <div class="post-container">
    <div class="p-5" style="margin-bottom: 20px; border: 1px solid #eee; padding: 20px;">
        <h6>Add post</h6>
        <input v-model="newPost.title" placeholder="Title" />
        <input v-model="newPost.body" placeholder="Body" />
        <button @click="addPost">Add Post</button>
    </div>

    <div class="table-container">
      <h2>Post List</h2>
      <table border="1" style="width: 100%; text-align: left; border-collapse: collapse;">
        <thead>
          <tr style="background-color: #f2f2f2;">
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Options</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="post in posts" :key="post.id">
            <td><router-link :to="'/post/' + post.id">{{ post.id }}</router-link></td>
            <td>{{ post.title }}</td>
            <td>{{ post.body }}</td>
            <td><router-link :to="'/edit/' + post.id"><button type="button">Edit</button></router-link>
              <button @click="removePost(post.id)">Delete</button></td>
          </tr>
          <tr v-if="posts.length === 0">
            <td colspan="4" style="text-align: center;">No posts found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style>
  table {
    table-layout: fixed;
    width: 100%;
    border-collapse: collapse;
  }

  th, td {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
</style>