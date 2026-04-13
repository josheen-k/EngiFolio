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
    <div class="row mb-5">
      <div class="col-12">
        <h1 class="display-5 fw-bold mb-0">Export Your Portfolio</h1>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-8">
        <section class="shadow-sm mb-4">
          <div class="card-header py-3">
            <h2 class="h5 mb-0 text-primary">Tick the boxes of the data you would like to be included:</h2>
          </div>
        </section>

        <section class="mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="profile" id="checkProfile">
              <label class="form-check-label" for="checkProfile">Profile</label>
            </div>
        </section>

        <section class="mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="competencies" id="checkComp">
              <label class="form-check-label" for="checkComp">Competencies</label>
            </div>
        </section>

        <section class="mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="networking" id="checkNet">
              <label class="form-check-label" for="checkNet">Networking</label>
            </div>
        </section>

        <section class="mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="goals" id="checkGoals">
              <label class="form-check-label" for="checkGoals">Goals</label>
            </div>
        </section>

        <section class="mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="stats" id="checkStats">
              <label class="form-check-label" for="checkStats">Stats</label>
            </div>
        </section>

        <hr />

        <section class="mb-5">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="allData" id="checkAll">
              <label class="form-check-label fw-bold" for="checkAll">All data</label>
            </div>
        </section>
      </div>

      <div class="d-flex flex-column gap-2">
          <button class="btn btn-primary" @click="exportData">Export Data</button>
          <router-link to="/student/dashboard" class="btn btn-link text-muted btn-sm">Cancel</router-link>
      </div>
    </div>
  </main>

  <Footer />
</template>