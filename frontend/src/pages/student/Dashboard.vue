<template>
  <Navbar/>

  <main class="container-xl py-5 px-4" v-if="profile">
    <div class="row g-4 mb-4">
      <h2 class="sec-title text-center" v-if="profile.preferred_name">Welcome, {{ profile.preferred_name }}</h2>
      <h2 class="sec-title text-center" v-else-if="profile.user.first_name">Welcome, {{ profile.user.first_name }}</h2>
      <h2 class="sec-title text-center" v-else>Welcome, {{ profile.user.last_name }}</h2>
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
              <template v-for="item in focusItems" :key="item.indicator_id">
                <tr v-if="!item.highest_entry || item.highest_entry.competency_level_weighting < 2">
                  <td><a href="#" class="table-link">{{ item.display_id }}</a></td>
                  <td>{{ item.description }}</td>
                  <td class="text-center">{{ item.entries_count || 0 }}</td>            
                  <td class="text-center">
                    <div v-if="item.highest_entry">{{ item.highest_entry.competency_level }}</div>
                    <div v-else class="text-muted">Not started</div>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-12 col-lg-4">
        <h2 class="sec-title text-center">Quick Links</h2>

        <div class="d-flex flex-wrap gap-2 mb-4 justify-content-center">
          <button class="btn btn-ql rounded-pill">Add a new reflection</button>
          <router-link :to="`/settings/profile/${$route.params.id}`" class="btn btn-ql rounded-pill">Edit profile</router-link>
          <router-link :to="`/student/networking/${$route.params.id}`" class="btn btn-ql rounded-pill">Add a new networking event</router-link>
          <router-link :to="`/student/export/${$route.params.id}`" class="btn btn-ql rounded-pill">Export profile</router-link>
          <button class="btn btn-ql rounded-pill">Add a SMART goal</button>
        </div>

        <h2 class="sec-title text-center mt-5">Recent Activity</h2>
        <div v-if="recentAct.length > 0">
          <ul class="ps-5 activity-list">
            <li class="mb-3" v-for="act in recentAct" :key="act">
              {{ formatDate(act.created_at) }} - {{ act.action }}
            </li>
          </ul>
        </div>
        <p v-else class="text-center">No recent activity</p>
      </div>

      <div class="row mt-5">
        <div class="col-12">
          <h2 class="sec-title text-center">Your Goals</h2>
          <ul class="ps-5 activity-list" v-if="userGoals && userGoals.length > 0">
            <li class="mb-3" v-for="goal in userGoals" :key="goal.goal_id">
              <strong>{{ (goal.status?.status || 'planned').toUpperCase() }}:</strong>
              {{ goal.goal_description }} 
              <span v-if="goal.end_date" class="text-muted">
                (Target: {{ goal.end_date }})
              </span>
            </li>
          </ul>
          <div v-else class="ps-5 activity-list text-muted">
            No goals currently logged.
          </div>
        </div>
      </div>
    </div>
  </main>

  <div v-else-if="loading" class="text-center py-5">
		<div class="spinner-border" role="status"></div>
		<p>Loading profile...</p>
	</div>

	<div v-else class="container py-5">
		<div class="alert alert-warning" role="alert">Profile not found.</div>
	</div>
</template>

<script setup>
    import { ref, onMounted, watch } from 'vue';
    import { useRoute } from 'vue-router'
    import Navbar from '@/components/Navbar.vue'
    import api from "@/services/api";

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

    const focusItems = ref([]);
    const compLevels = ref([]);
    const recentAct = ref([]);

    const chartOptions = ref({
      labels: [],
      legend: {
        position: 'bottom',
        fontFamily: 'Maven Pro, sans-serif',
        fontSize: '16px'
      },
      colors: [
        '#e2dfd7', // not started
        '#aba298', // emerging
        '#b1bbb3', // developing
        '#7c848c', // proficient
        '#333639'  // confident
      ]
    })

    // For formatting the date used by recent activity
    const formatDate = (dateString) => {
        if (!dateString) return '';
        const date = new Date(dateString);
        
        return date.toLocaleDateString('en-AU') + ', ' + 
              date.toLocaleTimeString('en-AU', { 
                hour: 'numeric', 
                minute: '2-digit', 
                hour12: true 
              }).toLowerCase();
      };

    const loadProfileData = async () => {
      try {
        const response = await api.get(`/profile/${route.params.id}`);
        profile.value = response.data.profile || response.data;
      } catch (error) {
        console.error("Error while fetching profile info:", error);
      }
		};

    const loadUserCompetencyData = async () => {
      try {
        const response = await api.get(`/competency-entries/${route.params.id}`);
        userCompetencies.value = response.data;
      } catch (error) {
        console.error("Error while fetching user competencies:", error);
      }
		};

    const loadCompetencyIndicators = async () => {
      try {
        const response = await api.get(`/competency-indicators`);
        competencyIndicators.value = response.data;
      } catch (error) {
        console.error("Error while fetching competencies:", error);
      }
		};

    const loadCompetencyIndicatorsWithCount = async () => {
      try {
        const response = await api.get(`/student-competency-indicators/${route.params.id}`);
        focusItems.value = response.data;
      } catch (error) {
        console.error("Error while fetching competencies:", error);
      }
		};

    const loadUserGoals = async () => {
      try {
        const response = await api.get(`/career-plans/${route.params.id}`);
        const plans = response.data || [];


        userGoals.value = plans.flatMap(plan => plan.smart_goals || plan.smartGoals || []);
        
        // Make sure array exists or else set empty array
        if (!Array.isArray(userGoals.value)) {
            userGoals.value = [];
        }
      } catch (error) {
        console.error("Error fetching goals:", error);
        userGoals.value = [];
      }
    };

    const loadUserActions = async () => {
      try {
        const response = await api.get(`/student-actions/recent/${route.params.id}`);
        recentAct.value = response.data;
      } catch (error) {
        console.error("Error fetching recent actions:", error);
      }
    };

    // Update the chart with values
    const updateChart = async () => {
      try {
          const response = await api.get(`/competency-levels`);
          const levels = response.data;

          // Add the number of not started competencies to the count
          const counts = [
            focusItems.value.filter(item => !item.highest_entry).length
          ];

          // Get labels for chart using the competency levels that can be selected
          compLevels.value = [
            'Not started', 
            ...response.data.map(item => item.competency_level)
          ];

          // Add labels to chart
          chartOptions.value = {
            ...chartOptions.value,
            labels: compLevels.value
          };

          // Go through each level and find out how many of the competencies are at this level
          levels.forEach(level => {
            const count = focusItems.value.filter(item => item.highest_entry?.competency_level === level.competency_level).length;   

            counts.push(count);
          });

          series.value = counts;

      } catch (error) {
          console.error("Failed to load chart data:", error);
      }
    };

    const loadData = async () => {
      loading.value = true;
      try {
        await loadProfileData();
        await loadUserCompetencyData();
        await loadCompetencyIndicators();
        await loadUserGoals();
        await loadUserActions();
        await loadCompetencyIndicatorsWithCount();
        await updateChart();

        // Calculate the total weight based of the weight of each competency
        const totalWeight = focusItems.value.reduce((acc, item) => {
            const weight = Number(item.highest_entry?.competency_level_weighting) || 0;
            return acc + weight;
        }, 0);

        // Calculate average by dividing weight by amount of competencies
        const avgScore = competencyIndicators.value.length > 0 ? Math.round(totalWeight / competencyIndicators.value.length) : 0;

        // Get average level from competency levels using the weight
        let displayLevel = "Not Started";
        if (avgScore > 0) {
          try {
            const levelResponse = await api.get(`/competency-levels-by-weight/${avgScore}`);
            displayLevel = levelResponse.data.competency_level;
          } catch (error) {
            console.error("Could not fetch average level:", error);
          }
        }

        // Filter all competencies above a level 3 and count
        const masteredCount = focusItems.value.filter(item => item.highest_entry?.competency_level_weighting > 3).length;

        // Stats that are to be displayed to the dashboard
        stats.value = {
          totalReflections: userCompetencies.value.length,
          comptMastered: `${masteredCount}/${competencyIndicators.value.length}`,
          goalsDone: `${userGoals.value.filter(g => g.status?.goal_status_id === 3).length}/${userGoals.value.length}`,
          avgLevel: displayLevel
        }

      } catch (error) {
        console.error("Error while fetching info:", error);
      } finally {
          loading.value = false;
      }
    };

    onMounted(() => {
      	loadData();
    })

    watch(() => route.params.id, () => {
      loadData();
    });
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
  font-size: 1.8rem;
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
  background: #333333;
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