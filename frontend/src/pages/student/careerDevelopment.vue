<script setup>
  import { ref, onMounted } from 'vue';
  import { useRouter } from 'vue-router'
  import axios from 'axios';
  import Navbar from '@/components/Navbar.vue'
  import api from "@/services/api";

  const router = useRouter();

  onMounted(() => {

});
</script>

<template>
  <Navbar/>
  <div class="container">
    <div class="p-5">
        <h3>Career Development</h3>
        <p>Use this page to plan your career goals and track how your interests, skills, and professional goals develop across your degree. For each year, start by identifying your current interests, values, skills, and networking plans. Then set at least one SMART goal using the section provided. SMART goals should be Specific, Measurable, Achievable, Relevant, and Time-bound. For each goal, identify key action steps, your timeline, and any progress or insights you gain throughout the year.</p>
    </div>

    <h3>John Smith's Career Plan</h3>

    <h5>Year 1</h5>
    <h6>Professional Interests</h6>
    <p>List specific roles, industries, or fields you are currently interested in.</p>

    <h6>Employers of Interest</h6>
    <ul>
        <li>First Value</li>
        <li>Second Value</li>
        <li>Third Value</li>
    </ul>

    <h6>Personal Values</h6>
    <p>Note what is most important to you in your future workplace.</p>

    <h6>Development Focus For This Year</h6>
    <p>List your main goals for the year (e.g. build network, get a part-time job) and the key technical and professional skills you want to develop (e.g. communication, software tools). Reference relevant EA Stage 1 competencies where appropriate.</p>

    <h6>Extra-curricular Activities Involved In</h6>
    <p>List part time work, clubs, societies, or volunteering relevant to your goals.</p>

    <h6>Networking Plans</h6>
    <p>List people or types of events you want to connect with.</p>
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