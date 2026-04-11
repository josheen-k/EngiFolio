<template>
  <Navbar/>

  <main class="container-xl py-4 px-4">
    <div class="row g-4 mb-4">
      <div class="col-12 col-md-6">
        <h2 class="sec-title text-center mb-3">Your Stats</h2>

        <div class="row g-3">
          <div class="col-6">

            <div class="card stat-card">
              <div class="card-body d-flex flex-column justify-content-between p-3">
                <p class="stat-title mb-2">Total Reflection<br/>Entries Logged</p>
                
                <div class="d-flex align-items-center gap-2">
                  <span class="stat-data">{{ stats.totalReflections }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-6">

            <div class="card stat-card">
              <div class="card-body d-flex flex-column justify-content-between p-3">
                <p class="stat-title mb-2">Total Reflection<br/>Entries Logged</p>
                
                <div class="d-flex align-items-center gap-2">
                  <span class="stat-data">{{ stats.comptMastered }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-6">

            <div class="card stat-card">
              <div class="card-body d-flex flex-column justify-content-between p-3">
                <p class="stat-title mb-2">Total Reflection<br/>Entries Logged</p>
                
                <div class="d-flex align-items-center gap-2">
                  <span class="stat-data">{{ stats.goalsDone }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-6">

            <div class="card stat-card">
              <div class="card-body d-flex flex-column justify-content-between p-3">
                <p class="stat-title mb-2">Total Reflection<br/>Entries Logged</p>
                
                <div class="d-flex align-items-center gap-2">
                  <span class="stat-data">{{ stats.avgLevel }}</span>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="mt-3">
          <button class="btn btn-filter px-4">
            Filter by year
          </button>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <h2 class="sec-title text-center mb-3">Attainment Level Distribution</h2>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-12 col-lg-8">
        <h2 class="sec-title text-center mb-3">Need More Focus On</h2>

        <table class="table">
          <tbody>
            <tr v-for="item in focusItems" :key="item.id">
              <td>{{ item.id }}</td>
              <td>{{ item.description }}</td>
              <td>{{ item.entries }}</td>
              <td>{{ item.level }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-12 col-lg-4">
        <h2 class="sec-title text-center mb-3">Quick Links</h2>
      </div>
      <div class="col-12 col-lg-4">
        <h2 class="sec-title text-center mb-3">Recent Activity</h2>
        <ul>
          <li v-for="act in recentAct" :key="act">
            {{ act }}
          </li>
        </ul>
      </div>
    </div>
  </main>

  <Footer/>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'

const focusItems = [
  {
    id: '1.2',
    description: 'Comprehensive, theory based understanding of the underpinning natural and physical sciences and the engineering fundamentals applicable to the engineering discipline.',
    entries: 0,
    level: 'Emerging'
  },

  {
    id: '1.2',
    description: 'Comprehensive, theory based understanding of the underpinning natural and physical sciences and the engineering fundamentals applicable to the engineering discipline.',
    entries: 0,
    level: 'Emerging'
  },

  {
    id: '1.2',
    description: 'Comprehensive, theory based understanding of the underpinning natural and physical sciences and the engineering fundamentals applicable to the engineering discipline.',
    entries: 0,
    level: 'Emerging'
  },

  {
    id: '1.2',
    description: 'Comprehensive, theory based understanding of the underpinning natural and physical sciences and the engineering fundamentals applicable to the engineering discipline.',
    entries: 0,
    level: 'Emerging'
  },

  {
    id: '1.2',
    description: 'Comprehensive, theory based understanding of the underpinning natural and physical sciences and the engineering fundamentals applicable to the engineering discipline.',
    entries: 0,
    level: 'Emerging'
  },
]

const recentAct = [
  '2 Apr 2026: Added reflection for competency 1.2',
  '1 Apr 2026: Updated goal: Do 3 mini projects',
  '25 Mar 2026: Deleted goal: Improve teamwork',
  '22 Mar 2026: Updated profile'
]

const stats = {
  totalReflections: 14,
  comptMastered: "3/16",
  goalsDone: "6/10",
  avgLevel: "Developing"
}
</script>

<style scoped>
.sec-title {
  font-family: 'Martel', serif;
  font-size: 2rem;
  color: #2b2b2bc5;
  font-weight: lighter;
}

.stat-card {
  border-radius: 1.5rem;
  border-color: #000000;
  height: 11rem;
}

.stat-title {
  font-family: 'Maven Pro', sans-serif;
  font-size: 1.5rem;
  color: #878787;
}

.stat-data {
  font-family: 'Martian Mono', monospace;
  font-size: 1.5rem;
  font-weight: 300;
  color: #606060;
}

.btn-filter {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  font-size: 1rem;
  font-weight: lighter;
  background: #e6e6e6;
}

.btn-filter:hover {
  background: #666666;
  color: #ffffff;
}
</style>