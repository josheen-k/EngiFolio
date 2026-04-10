<script setup>
  import { ref, onMounted } from 'vue';
  import { useRouter } from 'vue-router'
  import axios from 'axios';

  const router = useRouter();

const editCareerPlanning = async () => {
    router.push('/settings/career-planning');
  };

  onMounted(() => {

});
</script>

<template>
  <div class="container">
    <div class="p-5">
        <h3>Competency Planning</h3>
        <p>This will display the student's career planning information and can be edited by the student.</p>
        <h6>Preferred Employers</h6>
        <ul>
            <li>First employer</li>
            <li>Second employer</li>
            <li>Third employer</li>
        </ul>

        <h6>Preferred Roles</h6>
        <ul>
            <li>First role</li>
            <li>Second role</li>
            <li>Third role</li>
        </ul>

        <h6>Professional Values</h6>
        <ul>
          <li>First Value</li>
          <li>Second Value</li>
          <li>Third Value</li>
        </ul>


        <h6>Key Skills and Strengths</h6>
        <p>Summarise your strongest technical and professional skills relevant to engineering.</p>

        <h6>Current Development Goals</h6>
        <p>Outline a few short-term goals (e.g. build CAD skills, improve presentation confidence, gain industry exposure).</p>


        <h6>Extra-Curricular Activities</h6>
        <ul>
          <li>Engineers Australia</li>
          <li>Adelaide Uni Engineering Society</li>
        </ul>
        

        <button class="btn btn-primary " type="submit" @click="editCareerPlanning">Edit</button>
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