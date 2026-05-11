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

    <!--goals & weekly evets-->
    <div class="row g-5 mb-5">
      <!--TODO- incomplete goals sorted by nearest deadline only, no user priority system yet-->
      <!--TODO- action steps for goals are read only, cant tick them off, need to add a tracked state for their completion in backend-->
      <div class="col-12 col-lg-6">
        <h2 class="sec-title text-center">To Do</h2>
        <div class="todo-card">

          <div v-if="priorityGoals.length">
            <div class="goal-grp" v-for="goal in priorityGoals" :key="goal.goal_id">
              <div class="goal-row">
                <p class="goal-heading">Goal: {{ goal.goal_description }}</p>
                <span v-if="goal.end_date" class="goal-deadline">Due {{ goal.end_date }}</span>
              </div>

              <ul class="action-list" v-if="(goal.action_steps).length">
                <li class="action" v-for="step in (goal.action_steps).slice(0, 3)" :key="step.step_id">
                  <span class="ac-check"></span>
                  <span class="ac-name">{{ step.step_description }}</span>
                </li>
                <li v-if="(goal.action_steps || []).length > 3" class="action more-steps">
                  <span class="ac-check"></span>
                  <span class="ac-name">+ {{ (goal.action_steps).length-3 }} more steps</span>
                </li>
              </ul>
              <p v-else class="no-steps">No action steps added yet.</p>
            </div>
          </div>
          <p v-else class="empty-sub text-center py-3">No active goals right now.</p>
        </div>
      </div>

      <!--this week's events-->
      <div class="col-12 col-lg-6">
        <h2 class="sec-title text-center">Upcoming Events</h2>
        <div class="week-card">

          <!--days-->
          <div class="week-grid">
            <div class="day-col" v-for="day in weekDays" :key="day.label">
              <p class="day-label">{{ day.label }}</p>
              <div class="day-events">
                <div class="event" v-for="ev in day.events" :key="ev.id" :title="ev.name">
                  <span class="ev-name">{{ ev.name }}</span>
                  <span class="ev-time">{{ ev.time }}</span>
                </div>
              </div>
            </div>
          </div>
          <p class="ev-count">{{ thisWeekEvents.length }} event(s) for this week</p>
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
          <router-link :to="`/settings/profile/${$route.params.id}`" class="btn btn-ql rounded-pill">Edit profile</router-link>
          <router-link :to="`/student/networking/${$route.params.id}`" class="btn btn-ql rounded-pill">Add a new networking event</router-link>
          <router-link :to="`/student/export/${$route.params.id}`" class="btn btn-ql rounded-pill">Export profile</router-link>
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
	</div>

	<div v-else class="container py-5">
		<div class="alert alert-warning" role="alert">Profile not found.</div>
	</div>
</template>

<script setup>
    import { ref, computed, onMounted, watch } from 'vue';
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

    // For calculating average level
    const levelWeights = { "Emerging": 1, "Developing": 2, "Proficient": 3, "Confident": 4 };
    const weightToLevel = ["Not Started", "Emerging", "Developing", "Proficient", "Confident"];

    const chartOptions = {
      labels: [
        'Not Started',
        'Emerging',
        'Developing',
        'Proficient',
        'Confident'
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
        '#7c848c', // proficient
        '#333639'  // confident
      ]
    }

    // priority filtering will be added whe backend supports that
    const priorityGoals = ref([])
    
    const allEvents = ref([])
    const dayLabels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']

    // get start: mon and end: sun of the currweek
    function getWeekBounds() {
      const now = new Date()
      const day = now.getDay()
      let diffToMon
      if (day === 0) {
        // Sunday
        diffToMon = -6
      } else {
        diffToMon = 1 - day
      }
      const mon = new Date(now)
      mon.setDate(now.getDate() + diffToMon)
      mon.setHours(0, 0, 0, 0)
      const sun = new Date(mon)
      sun.setDate(mon.getDate() + 6)
      sun.setHours(23, 59, 59, 999)
      return { mon, sun }
    }

    // events that fall in curr week
    const thisWeekEvents = computed(function () {
      const { mon, sun } = getWeekBounds()
      return allEvents.value.filter(function (ev) {
        const d = new Date(ev.event_datetime)
        if (d >= mon && d <= sun) {
          return true
        } else {
          return false
        }
      })
    })

    // build cols for 7 days
    const weekDays = computed(function () {
      const { mon } = getWeekBounds()
      return dayLabels.map(function (label, i) {
        const dayDate = new Date(mon)
        dayDate.setDate(mon.getDate() + i)
        const dayStart = new Date(dayDate); dayStart.setHours(0, 0, 0, 0)
        const dayEnd = new Date(dayDate); dayEnd.setHours(23, 59, 59, 999)

        const events = thisWeekEvents.value
          .filter(function (ev) {
            const d = new Date(ev.event_datetime)
            return d >= dayStart && d <= dayEnd
          })
          .map(function (ev) {
            const d = new Date(ev.event_datetime)
            return {
              id: ev.id,
              name: ev.event_name,
              time: d.toLocaleTimeString('en-AU', { hour: '2-digit', minute: '2-digit' })
            }
          })

        return { label, events }
      })
    })

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

    const loadUserGoals = async () => {
      const response = await api.get('/smart-goals', {
        params: { profile_id: route.params.id }
      })
      userGoals.value = response.data

      let incompleteGoals = userGoals.value.filter(function (goal) {
        return Number(goal.goal_status_id) !== 3
      })

      // sort by closest deadline
      incompleteGoals.sort(function (a, b) {
          // goals without end date go last
          if (!a.end_date) {
            return 1
          }
          if (!b.end_date) {
            return -1
          }
          const dateA = new Date(a.end_date)
          const dateB = new Date(b.end_date)
          return dateA - dateB
      })
      // only keep first 3 goals for dash
      priorityGoals.value = incompleteGoals.slice(0, 2)
    }

    const loadNetworkingEvents = async () => {
      try {
        const response = await api.get('/networking-events')
        allEvents.value = response.data
      } catch (error) {
        console.error('Error fetching networking events:', error)
        allEvents.value = []
      }
    };

    const loadData = async () => {
      loading.value = true;
      const id = route.params.id;
      try {
        await loadProfileData();
        await loadUserCompetencyData();
        await loadCompetencyIndicators();
        await loadUserGoals();
        await loadNetworkingEvents()

        // Calculate the total weight based of the points for each number
        const totalWeight = userCompetencies.value.reduce((acc, c) => acc + (levelWeights[c.level] || 0), 0);
        // Calculate average by dividing weight by amount of competencies
        const avgScore = competencyIndicators.value.length > 0 ? Math.round(totalWeight / competencyIndicators.value.length) : 0;

        // Made some changes here
        stats.value = {
          totalReflections: userCompetencies.value.length,
          comptMastered: `${userCompetencies.value.filter(c => c.entry_level?.competency_level === 'Confident').length}/${competencyIndicators.value.length}`,
          goalsDone: `${userGoals.value.filter(g => g.status?.status === 'completed').length}/${userGoals.value.length}`,
          avgLevel: weightToLevel[avgScore]
        }

        // Find the amount of each competency level
        series.value = [
          competencyIndicators.value.length - (new Set(userCompetencies.value.map(c => c.indicator_id))).size,
          userCompetencies.value.filter(c => c.entry_level?.competency_level === 'Emerging').length,
          userCompetencies.value.filter(c => c.entry_level?.competency_level === 'Developing').length,
          userCompetencies.value.filter(c => c.entry_level?.competency_level === 'Proficient').length,
          userCompetencies.value.filter(c => c.entry_level?.competency_level === 'Confident').length
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

    watch(() => route.params.id, () => {
      loadData();
    });


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
  margin-top: 2.5rem;
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

.todo-card {
  background: #ffffff;
  border: 1px solid #d0d0d0;
  border-radius: 1.5rem;
  padding: 1.5rem 1.75rem;
  min-height: 20rem;
}

.goal-grp {
  margin-bottom: 1.25rem;
}

.goal-row {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
}

.goal-heading {
  font-family: 'Martian Mono', monospace;
  font-size: 1rem;
  font-weight: 400;
  color: #222222;
  margin-bottom: 0;
  flex: 1;
}

.goal-deadline {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.9rem;
  color: #c08080;
}

.no-steps {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.8rem;
  color: #aaaaaa;
  margin: 0;
}

.more-steps .ac-name {
  color: #aaaaaa;
  font-style: italic;
}

.action-list {
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
}

.action {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  cursor: pointer;
  user-select: none;
}

.ac-name {
  font-family: 'Maven Pro', sans-serif;
  font-size: 1rem;
  color: #333333;
}

.ac-name-done {
  text-decoration: line-through;
  color: #aaaaaa;
}

.week-card {
  background: #ffffff;
  border: 1px solid #d0d0d0;
  border-radius: 1.5rem;
  padding: 1.25rem 1.5rem;
  min-height: 15rem;
}

.week-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 0.25rem;
  margin-bottom: 0.75rem;
}

.day-col {
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
}

.day-label {
  font-family: 'Martian Mono', monospace;
  font-size: 0.7rem;
  color: #888888;
  text-align: center;
  margin-bottom: 0.2rem;
}

.day-events {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  min-height: 15rem;
  background: #f8f8f8;
  border-radius: 0.5rem;
  padding: 0.25rem;
}

.event {
  background: #e8e8e8;
  border-radius: 0.4rem;
  padding: 0.25rem 0.35rem;
  display: flex;
  flex-direction: column;
  cursor: default;
}

.ev-name {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.65rem;
  color: #333333;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.ev-time {
  font-family: 'Martian Mono', monospace;
  font-size: 0.6rem;
  color: #888888;
}

.ev-count {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.8rem;
  color: #aaaaaa;
  text-align: right;
  margin: 0;
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

.btn-ql:hover {
  color: #ffffff;
  background: #333333;
}

.activity-list {
  font-family: 'Maven Pro', sans-serif;
  color: #444444;
}

.empty-sub {
  font-family: 'Maven Pro', sans-serif;
  font-size: 1rem;
  color: #aaaaaa;
}
</style>