<template>
  <Navbar/>

  <main class="container-xl py-5 px-4" v-if="profile">
    <div class="row g-4 mb-4">
      <h2 class="sec-title text-center" v-if="profile.preferred_name">Welcome, {{ profile.preferred_name }}</h2>
      <h2 class="sec-title text-center" v-else-if="profile.first_name">Welcome, {{ profile.first_name }}</h2>
      <h2 class="sec-title text-center" v-else>Welcome, {{ profile.last_name }}</h2>
      <div class="col-12 col-md-6">
        <h2 class="sec-title text-center">Your Stats</h2>

        <div class="row g-4">
          <div class="col-6">

            <div class="card stat-card card-dark">
              <div class="card-body d-flex flex-column justify-content-between p-3">
                <p class="stat-title mb-2">Total Reflection<br/>Entries Logged</p>
                
                <div class="d-flex align-items-center justify-content-between">
                  <span class="circle circle-light">
                    <img class="arrow-img" src="@/assets/arrow-up.png" alt="arrow-img">
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
                    <img class="arrow-img" src="@/assets/arrow-up.png" alt="arrow-img">
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
                    <img class="arrow-img" src="@/assets/arrow-up.png" alt="arrow-img">
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
          <button class="btn btn-filter px-4">
            Filter by year
          </button>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <h2 class="sec-title text-center mb-3">Attainment Level Distribution</h2>
        <div class="chart d-flex justify-content-center">
          <apexchart type="pie" width="120%" height="420" :options="chartOptions" :series="series" />
        </div>
      </div>
    </div>

    <div class="row g-5">
      <div class="col-12 col-lg-8">
        <h2 class="sec-title text-center">Need More Focus On</h2>
        <div class="table-style">
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
                <td><a href="#" class="table-link">{{ item.id }}</a></td>
                <td>{{ item.description }}</td>
                <td class="text-center">{{ item.entries }}</td>
                <td class="text-center">{{ item.level }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-12 col-lg-4">
        <h2 class="sec-title text-center">Quick Links</h2>

        <div class="d-flex flex-wrap gap-2 mb-4 justify-content-center">
          <button class="btn btn-ql rounded-pill">Add a new reflection</button>
          <router-link to="/settings/profile/1" class="btn btn-ql rounded-pill">Edit profile</router-link>
          <button class="btn btn-ql rounded-pill btn-ql3">Add a new networking event</button>
          <router-link to="/student/export" class="btn btn-ql rounded-pill">Export profile</router-link>
          <button class="btn btn-ql rounded-pill">Add a SMART goal</button>
        </div>

        <h2 class="sec-title text-center mt-5">Recent Activity</h2>
        <ul class="ps-5 activity-list">
          <li class="mb-3" v-for="act in recentAct" :key="act">
            {{ act }}
          </li>
        </ul>
      </div>
    </div>
  </main>

  <div v-else-if="loading" class="text-center py-5">
		<div class="spinner-border" role="status"></div>
		<p>Loading profile...</p>
		<Footer />
	</div>

	<div v-else class="container py-5">
		<div class="alert alert-warning" role="alert">Profile not found.</div>
		<Footer />
	</div>

  <Footer/>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import { useRoute } from 'vue-router'
    import axios from 'axios';
    import Navbar from '@/components/Navbar.vue'
    import Footer from '@/components/Footer.vue'

    const route = useRoute();
    const profile = ref(null);
    const userCompetencies = ref([]);
    const competencyIndicators = ref([]);
    const userGoals = ref([]);
    const loading = ref(true);
    const stats = ref({
        totalReflections: 0,
        comptMastered: "0/0",
        goalsDone: "0/0",
        avgLevel: "---"
    });
    const series = ref([0, 0, 0, 0, 0]);

    // For calculating average level
    const levelWeights = { "Emerging": 1, "Developing": 2, "Competent": 3, "Proficient": 4 };
    const weightToLevel = ["Not Started", "Emerging", "Developing", "Competent", "Proficient"];

    const chartOptions = {
      labels: [
        'Not Started',
        'Emerging',
        'Developing',
        'Competent',
        'Proficient'
      ],
      legend: {
        position: 'bottom',
        fontFamily: 'Maven Pro, sans-serif',
        fontSize: '16px'
      },
      colors: [
        '#e2dfd7', // not started
        '#aba298', // emerging
        '#b1bbb3', // developing
        '#7c848c', // competent
        '#333639'  // proficient
      ]
    }

    const loadProfileData = async () => {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/profile/1`);
        profile.value = response.data.profile || response.data;
      } catch (error) {
        console.error("Error while fetching profile info:", error);
      }
		};

    const loadUserCompetencyData = async () => {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/competency-entries/3`);
        userCompetencies.value = response.data;
      } catch (error) {
        console.error("Error while fetching user competencies:", error);
      }
		};

    const loadCompetencyIndicators = async () => {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/competency-indicators`);
        competencyIndicators.value = response.data;
      } catch (error) {
        console.error("Error while fetching competencies:", error);
      }
		};

    const loadUserGoals = async () => {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/user/smart-goals/3`);
        userGoals.value = response.data;
      } catch (error) {
        console.error("Error fetching goals:", error);
      }
    };

    const loadData = async () => {
      loading.value = true;
      try {
        await loadProfileData();
        await loadUserCompetencyData();
        await loadCompetencyIndicators();
        await loadUserGoals();

        // Calculate the total weight based of the points for each number
        const totalWeight = userCompetencies.value.reduce((acc, c) => acc + (levelWeights[c.level] || 0), 0);
        // Calculate average by dividing weight by amount of competencies
        const avgScore = competencyIndicators.value.length > 0 ? Math.round(totalWeight / competencyIndicators.value.length) : 0;

        stats.value = {
          totalReflections: userCompetencies.value.length,
          comptMastered: `${userCompetencies.value.filter(c => c.level === 'Competent' || c.level === 'Proficient').length}/${competencyIndicators.value.length}`,
          goalsDone: `${userGoals.value.filter(c => c.status === 'completed').length}/${userGoals.value.length}`,
          avgLevel: weightToLevel[avgScore]
        }

        series.value = [
          competencyIndicators.value.length - (new Set(userCompetencies.value.map(c => c.indicator_id))).size,
          userCompetencies.value.filter(c => c.level === 'Emerging').length,
          userCompetencies.value.filter(c => c.level === 'Developing').length,
          userCompetencies.value.filter(c => c.level === 'Competent').length,
          userCompetencies.value.filter(c => c.level === 'Proficient').length
        ];




      } catch (error) {
        console.error("Error while fetching info:", error);
      } finally {
          loading.value = false;
      }
  };

    onMounted(() => {
      	loadData();
    })

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


</script>

<style scoped>
.sec-title {
  font-family: 'Martel', serif;
  font-size: 2rem;
  color: #2b2b2bc5;
  font-weight: lighter;
  margin-bottom: 2rem;
}

.stat-card {
  border-radius: 1.5rem;
  border-color: #000000;
  height: 12rem;
  padding: 0.5rem;
}

.card-dark {
  background: #f1f1f1;
}

.card-light {
  background: #ffffff;
}

.stat-title {
  font-family: 'Maven Pro', sans-serif;
  font-size: 1.5rem;
  color: #878787;
}

.stat-data {
  font-family: 'Martian Mono', monospace;
  font-size: 2.1rem;
  font-weight: 300;
  color: #606060;
}

.circle {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 2rem;
  display: flex;
  justify-content: center;
  align-items: center;
}

.circle-light {
  background: #ffffff;
}

.circle-dark {
  background: #f1f1f1;
}

.arrow-img {
  width: 2rem;
  height: 2rem;
  object-fit: contain;
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

.focus-table {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.95rem;
}

.focus-table thead th {
  font-family: 'Martian Mono', monospace;
  color: #222222;
  font-weight: 200;
  font-size: 1.2rem;
  background-color: #f1f1f1;
  border-color: #d0d0d0;
  margin: 5rem;
}

.focus-table tbody td {
  border-color: #e0e0e0;
  color: #222222;
  vertical-align: middle;
}

.table-link {
  color: #1a1a1a;
  font-family: 'Martian Mono', monospace;
  font-size: 0.8rem;
}

.btn-ql {
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 1rem;
  color: #ffffff;
  background: #555555;
  padding: 0.5rem 1rem;
}

.btn-ql3 {
  width: 86%;
}

.btn-ql:hover {
  color: #ffffff;
  background: #333333;
}

.activity-list {
  font-family: 'Maven Pro', sans-serif;
  color: #444444;
}
</style>