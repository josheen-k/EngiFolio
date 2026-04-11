<template>
  <Navbar/>

  <main class="container-xl py-4 px-4">
    <div class="row g-4 mb-4">
      <div class="col-12 col-md-6">
        <h2>Your Stats</h2>
      </div>
      <div class="col-12 col-md-6">
        <h2>Attainment Level Distribution</h2>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-12 col-lg-8">
        <h2>Need More Focus On</h2>
      </div>
      <div class="col-12 col-lg-4">
        <h2>Quick Links</h2>
      </div>
    </div>
  </main>

  <Footer/>
</template>

<script setup>
  import Navbar from '@/components/Navbar.vue'
  import Footer from '@/components/Footer.vue'
</script>
