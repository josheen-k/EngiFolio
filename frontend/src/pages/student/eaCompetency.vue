<script setup>
  import { ref, onMounted } from 'vue';
  import { useRouter } from 'vue-router'
  import axios from 'axios';

  const router = useRouter();

  onMounted(() => {

});
</script>

<template>
  <div class="container">
    <div class="p-5">
        <h3>EA Competency</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
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