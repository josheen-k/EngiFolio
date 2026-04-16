<template>
  <Navbar/>

  <main class="container-xl py-5 px-4" v-if="profile">
    <div class="row g-4 mb-4">
      <h2 class="sec-title text-center" v-if="profile.preferred_name">
        Welcome, {{ profile.preferred_name }}
      </h2>
      <h2 class="sec-title text-center" v-else-if="profile.first_name">
        Welcome, {{ profile.first_name }}
      </h2>
      <h2 class="sec-title text-center" v-else>
        Welcome, {{ profile.last_name }}
      </h2>

      <!-- STATS -->
      <div class="col-12 col-md-6">
        <h2 class="sec-title text-center">Your Stats</h2>

        <div class="row g-4">
          <div class="col-6">
            <div class="card stat-card card-dark">
              <div class="card-body d-flex flex-column justify-content-between p-3">
                <p class="stat-title mb-2">Total Reflection<br/>Entries Logged</p>
                <div class="d-flex align-items-center justify-content-between">
                  <span class="circle circle-light">
                    <img class="arrow-img" src="@/assets/arrow-up.png" />
                  </span>
                  <span class="stat-data">{{ stats.totalReflections }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-6">
            <div class="card stat-card card-light">
              <div class="card-body d-flex flex-column justify-content-between p-3">
                <p class="stat-title mb-2">Mastered<br/>Competencies</p>
                <div class="d-flex align-items-center justify-content-between">
                  <span class="circle circle-dark">
                    <img class="arrow-img" src="@/assets/arrow-up.png" />
                  </span>
                  <span class="stat-data">{{ stats.comptMastered }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-6">
            <div class="card stat-card card-light">
              <div class="card-body d-flex flex-column justify-content-between p-3">
                <p class="stat-title mb-2">SMART Goals<br/>Completed</p>
                <div class="d-flex align-items-center justify-content-between">
                  <span class="circle circle-dark">
                    <img class="arrow-img" src="@/assets/arrow-up.png" />
                  </span>
                  <span class="stat-data">{{ stats.goalsDone }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-6">
            <div class="card stat-card card-dark">
              <div class="card-body d-flex flex-column justify-content-between p-3">
                <p class="stat-title mb-2">Current Average<br/>Level</p>
                <div class="d-flex justify-content-center">
                  <span class="stat-data">{{ stats.avgLevel }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-3">
          <button class="btn btn-filter px-4">Filter by year</button>
        </div>
      </div>

      <!-- PIE CHART -->
      <div class="col-12 col-md-6">
        <h2 class="sec-title text-center mb-3">Attainment Level Distribution</h2>
        <div class="chart d-flex justify-content-center">
          <apexchart
            type="pie"
            width="120%"
            height="420"
            :options="chartOptions"
            :series="series"
          />
        </div>
      </div>
    </div>

    <!-- TABLE -->
    <div class="row g-5">
      <div class="col-12 col-lg-8">
        <h2 class="sec-title text-center">Need More Focus On</h2>

        <table class="table table-bordered focus-table">
          <thead>
            <tr>
              <th class="text-center">Id</th>
              <th class="text-center">Description</th>
              <th class="text-center">Entries</th>
              <th class="text-center">Level</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="item in focusItems" :key="item.id">
              <td>{{ item.id }}</td>
              <td>{{ item.description }}</td>
              <td class="text-center">{{ item.entries }}</td>
              <td class="text-center">{{ item.level }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- QUICK LINKS -->
      <div class="col-12 col-lg-4">
        <h2 class="sec-title text-center">Quick Links</h2>

        <div class="d-flex flex-wrap gap-2 mb-4 justify-content-center">
          <button class="btn btn-ql rounded-pill">Add a new reflection</button>

          <router-link
            :to="`/settings/profile/${route.params.id}`"
            class="btn btn-ql rounded-pill"
          >
            Edit profile
          </router-link>

          <!-- ✅ INDUSTRY CONTACTS LINK -->
          <router-link
            :to="`/student/industry-contacts/${route.params.id}`"
            class="btn btn-ql rounded-pill"
          >
            Industry Contacts
          </router-link>

          <button class="btn btn-ql rounded-pill btn-ql3">
            Add a new networking event
          </button>

          <router-link to="/student/export" class="btn btn-ql rounded-pill">
            Export profile
          </router-link>

          <button class="btn btn-ql rounded-pill">
            Add a SMART goal
          </button>
        </div>

        <h2 class="sec-title text-center mt-5">Recent Activity</h2>
        <ul class="ps-5 activity-list">
          <li v-for="act in recentAct" :key="act" class="mb-3">
            {{ act }}
          </li>
        </ul>
      </div>
    </div>

    <Footer/>
  </main>

  <!-- LOADING -->
  <div v-else-if="loading" class="text-center py-5">
    <div class="spinner-border"></div>
    <p>Loading profile...</p>
    <Footer/>
  </div>

  <!-- ERROR -->
  <div v-else class="container py-5">
    <div class="alert alert-warning">Profile not found.</div>
    <Footer/>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'

const route = useRoute()

const profile = ref(null)
const userCompetencies = ref([])
const competencyIndicators = ref([])
const userGoals = ref([])
const loading = ref(true)

const stats = ref({
  totalReflections: 0,
  comptMastered: "0/0",
  goalsDone: "0/0",
  avgLevel: "---"
})

const series = ref([0, 0, 0, 0, 0])

const levelWeights = {
  Emerging: 1,
  Developing: 2,
  Competent: 3,
  Proficient: 4
}

const weightToLevel = [
  "Not Started",
  "Emerging",
  "Developing",
  "Competent",
  "Proficient"
]

const chartOptions = {
  labels: ['Not Started','Emerging','Developing','Competent','Proficient'],
  legend: { position: 'bottom' },
  colors: ['#e2dfd7','#aba298','#b1bbb3','#7c848c','#333639']
}

/* API LOADERS */
const loadProfileData = async () => {
  const res = await axios.get(`http://127.0.0.1:8000/api/profile/${route.params.id}`)
  profile.value = res.data.profile || res.data
}

const loadUserCompetencyData = async () => {
  const res = await axios.get(`http://127.0.0.1:8000/api/competency-entries/${route.params.id}`)
  userCompetencies.value = res.data
}

const loadCompetencyIndicators = async () => {
  const res = await axios.get(`http://127.0.0.1:8000/api/competency-indicators`)
  competencyIndicators.value = res.data
}

const loadUserGoals = async () => {
  const res = await axios.get(`http://127.0.0.1:8000/api/user/smart-goals/${route.params.id}`)
  userGoals.value = res.data
}

const loadData = async () => {
  loading.value = true

  try {
    await loadProfileData()
    await loadUserCompetencyData()
    await loadCompetencyIndicators()
    await loadUserGoals()

    const totalWeight = userCompetencies.value.reduce(
      (acc, c) => acc + (levelWeights[c.level] || 0),
      0
    )

    const avgScore =
      competencyIndicators.value.length > 0
        ? Math.round(totalWeight / userCompetencies.value.length)
        : 0

    stats.value = {
      totalReflections: userCompetencies.value.length,
      comptMastered: `${userCompetencies.value.filter(c => c.level === 'Competent' || c.level === 'Proficient').length}/${competencyIndicators.value.length}`,
      goalsDone: `${userGoals.value.filter(c => c.status === 'completed').length}/${userGoals.value.length}`,
      avgLevel: weightToLevel[avgScore]
    }

    series.value = [
      competencyIndicators.value.length -
        new Set(userCompetencies.value.map(c => c.indicator_id)).size,
      userCompetencies.value.filter(c => c.level === 'Emerging').length,
      userCompetencies.value.filter(c => c.level === 'Developing').length,
      userCompetencies.value.filter(c => c.level === 'Competent').length,
      userCompetencies.value.filter(c => c.level === 'Proficient').length
    ]
  } finally {
    loading.value = false
  }
}

onMounted(loadData)
watch(() => route.params.id, loadData)

/* STATIC DATA */
const focusItems = [
  {
    id: '1.2',
    description: 'Comprehensive understanding of engineering fundamentals',
    entries: 0,
    level: 'Emerging'
  }
]

const recentAct = [
  '2 Apr 2026: Added reflection',
  '1 Apr 2026: Updated goal',
  '25 Mar 2026: Deleted goal'
]
</script>