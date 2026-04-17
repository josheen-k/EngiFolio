<script setup>
    import { ref, onMounted, watch } from 'vue';
    import { useRoute } from 'vue-router'
    import axios from 'axios';
    import Navbar from '@/components/Navbar.vue'
    import Footer from '@/components/Footer.vue'

    const route = useRoute();

    const profile = ref(false);
    const competencies = ref(false);
    const networking = ref(false);
    const goals = ref(false);
    const stats = ref(false);
    const allData = ref(false);

    watch(allData, (newValue) => {
        profile.value = newValue;
        competencies.value = newValue;
        networking.value = newValue;
        goals.value = newValue;
        stats.value = newValue;
    });

    const exportData = () => {
        if (!profile.value && !competencies.value && !networking.value && !goals.value && !stats.value) {
            alert("You must select at least one category to export");
            return;
        }

        // Add only selected fields
        const selectedFields = [];
        if (profile.value) selectedFields.push("Profile");
        if (competencies.value) selectedFields.push("Competencies");
        if (networking.value) selectedFields.push("Networking");
        if (goals.value) selectedFields.push("Goals");
        if (stats.value) selectedFields.push("Stats");


        const exportContent = selectedFields.map(title => `"${title}"`).join("\n");

        
        const blob = new Blob(["\ufeff", exportContent], { type: 'text/csv;charset=utf-8;' });
        
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);
        
        link.setAttribute("href", url);
        link.setAttribute("download", "portfolio_export.csv");
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        alert("Data has been exported. Your download will begin shortly");
    };

</script>

<template>
  <Navbar />

  <main class="container-xl py-5 px-4">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h1 class="sec-title mb-4">Export Your Portfolio</h1>

        <div class="card card-dark border-0 p-4 mb-4">
          <h2 class="sec-title mb-4" style="font-size: 1.25rem;">
            Tick the boxes of the data you would like to be included:
          </h2>

          <div class="focus-table d-flex flex-column gap-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="profile" id="checkProfile">
              <label class="form-check-label" for="checkProfile">Profile</label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="competencies" id="checkComp">
              <label class="form-check-label" for="checkComp">Competencies</label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="networking" id="checkNet">
              <label class="form-check-label" for="checkNet">Networking</label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="goals" id="checkGoals">
              <label class="form-check-label" for="checkGoals">Goals</label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="stats" id="checkStats">
              <label class="form-check-label" for="checkStats">Stats</label>
            </div>

            <hr class="my-2" />

            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="allData" id="checkAll">
              <label class="form-check-label fw-bold" for="checkAll">All data</label>
            </div>
          </div>
        </div>

        <div class="d-flex gap-3 align-items-center">
          <button class="btn btn-ql rounded-pill px-5" @click="exportData">Export Data</button>
          <router-link to="/student/dashboard" class="btn btn-link text-muted btn-sm text-decoration-none">Cancel</router-link>
        </div>
      </div>
    </div>
  </main>

  <Footer />
</template>

<style scoped>
  .sec-title {
    font-family: 'Martel', serif;
    font-size: 2.0rem;
    color: #303030c5;
  }

  .card-dark {
    background: #f1f1f1;
    border-radius: 8px;
  }

  .focus-table {
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.95rem;
  }

  .btn-ql {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 1rem;
    color: #ffffff;
    background: #555555;
    padding: 0.5rem 1rem;
  }

  .btn-ql:hover {
    color: #ffffff;
    background: #333333;
  }
</style>