<script setup>
  import { ref, onMounted } from 'vue';
  import { useRouter } from 'vue-router'
  import axios from 'axios';

  const router = useRouter();


  const editProfile = async () => {
    router.push('/settings/profile');
  };

  onMounted(() => {

});
</script>

<template>
  <div class="container">
    <div class="p-5">
        <h3>John Smith's profile</h3>
        <h6>Introduction</h6>
        <p>Write a brief introduction about yourself (e.g. your degree, areas of interest in engineering, and key strengths).</p>

        <h6>Elevator Pitch</h6>
        <p>Prepare a 30-second pitch to introduce yourself at networking events or interviews.</p>

        <h6>Networking Questions</h6>
        <ul>
          <li>First question</li>
          <li>Second question</li>
          <li>Third question</li>
        </ul>


        <h6>Updated Resume</h6>
        <div class="linked-in">
          <a href="https://au.linkedin.com/">My LinkedIn Profile</a>
        </div>
        

        <button class="btn btn-primary " type="submit" @click="editProfile">Edit Profile</button>
    </div>
  </div>
</template>

<style>
    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 50vh;
      background-color: #f8f9fa;
    }

    .profilePic {
      height: 120px;
      width: 120px;
      object-fit: cover;
      border-radius: 50%;
      margin: 0 auto 20px auto;
      border: 4px solid #f0f2f5;
      padding: 0;
    }

    h3, h6 {
      font-weight: 700;
      color: #2c3e50;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 20px;
    }

    p {
      color: #6c757d;
      font-size: 0.95rem;
      margin-bottom: 8px;
    }
</style>