<template>
  <Navbar/>

  <main class="container-xl py-5 px-4" v-if="profile">
    <div class="row g-4 mb-4">
      <h2 class="sec-title text-center px-2" v-if="profile.preferred_name">Welcome, {{ profile.preferred_name }}</h2>
      <h2 class="sec-title text-center px-2" v-else-if="profile.user.first_name">Welcome, {{ profile.user.first_name }}</h2>
      <h2 class="sec-title text-center px-2" v-else>Welcome, {{ profile.user.last_name }}</h2>
      <div class="col-12 col-md-6">
        <h2 class="sec-title text-center">Your Stats</h2>

        <div class="row g-4">
          <div class="col-6">

            <div class="card stat-card card-dark">
              <div class="card-body d-flex flex-column justify-content-between p-3">
                <div class="d-flex justify-content-between align-items-start">
                  <p class="stat-title mb-2">Total Reflection<br/>Entries Logged</p>
                  <span class="info-btn" :data-tooltip="`Count of published reflection entries logged across all competencies.`">
                    <img src="@/assets/question-mark.png" class="info-img"/>
                  </span>
                </div>
                
                <div class="d-flex align-items-center justify-content-between">
                  <span class="circle circle-light" @click="goToReflections" style="cursor:pointer">
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
                <div class="d-flex justify-content-between align-items-start">
                  <p class="stat-title mb-2">Mastered<br/>Competencies</p>
                  <span class="info-btn" :data-tooltip="`Count of competencies where you've reflected the highest attainment level (Confident) out of total competencies`">
                    <img src="@/assets/question-mark.png" class="info-img"/>
                  </span>
                </div>
                
                <div class="d-flex align-items-center justify-content-between">
                  <span class="circle circle-dark" @click="goToMastered" style="cursor:pointer">
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
                <div class="d-flex justify-content-between align-items-start">
                  <p class="stat-title mb-2">SMART Goals<br/>Completed</p>
                  <span class="info-btn" :data-tooltip="`Count of goals you've marked as completed out of your total goals.`">
                    <img src="@/assets/question-mark.png" class="info-img"/>
                  </span>
                </div>
                
                <div class="d-flex align-items-center justify-content-between">
                  <span class="circle circle-dark" @click="goToGoals" style="cursor:pointer">
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
                <div class="d-flex justify-content-between align-items-start">
                  <p class="stat-title mb-2">Current Average<br/>Level</p>
                  <span class="info-btn" :data-tooltip="`Your overall level based on your best entry per competency. Unstarted competencies count as zero and lower your average.`">
                    <img src="@/assets/question-mark.png" class="info-img"/>
                  </span>
                </div>
                
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
          <div class="week-scroll">
            <div class="week-grid">
              <div class="day-col" v-for="day in weekDays" :key="day.label">
                <p class="day-label">{{ day.label }}</p>
                <div class="day-events">
                  <div class="event" v-for="ev in day.events" :key="ev.id" :title="ev.name" @click="goToEvent(ev.id)">
                    <span class="ev-name">{{ ev.name }}</span>
                    <span class="ev-time">{{ ev.time }}</span>
                  </div>
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
        <div class="table-responsive table-style">
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
              <template v-for="item in filteredFocusItems" :key="item.indicator_id">
                <tr>
                  <td><router-link :to="`/student/eaCompetency/${$route.params.id}?indicator=${item.indicator_id}`" class="table-link">
                      {{ item.display_id }}
                  </router-link></td>
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
          <button class="btn btn-ql rounded-pill" @click="goToAddReflec">Add a new reflection</button>
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
    </div>
  </main>

  <div v-else-if="loading" class="text-center py-5 loading">
		<p>Loading profile...</p>
	</div>

	<div v-else class="container py-5">
		<div class="alert alert-warning" role="alert">Profile not found.</div>
	</div>
</template>

<script setup>
    import { ref, computed, onMounted, watch } from 'vue';
    import { useRoute } from 'vue-router'
    import { useRouter } from 'vue-router'
    import Navbar from '@/components/Navbar.vue'
    import api from "@/services/api";

    const route = useRoute();
    const router = useRouter()
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

    function goToReflections() {
      router.push({
        path: `/student/eaCompetency/${route.params.id}`,
        query: { filterReflec: 'has-reflections' }
      })
    }

    function goToMastered() {
      router.push({
        path: `/student/eaCompetency/${route.params.id}`,
        query: { filterLevel: 'Confident' }
      })
    }
    
    function goToGoals() {
      router.push(`/goals/${route.params.id}`)
    }

    function goToAddReflec() {
      router.push({
        path: `/student/eaCompetency/${route.params.id}`,
        query: { openAdd: 'true' }
      })
    }

    function goToEvent(eventId) {
      router.push({
        path: `/student/networking/${route.params.id}`,
        query: { eventId }
      })
}
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
              id: ev.event_id,
              name: ev.event_name,
              time: d.toLocaleTimeString('en-AU', { hour: '2-digit', minute: '2-digit' })
            }
          })

        return { label, events }
      })
    })

    // Get only the first 7 entries and all entries that have highest confident
    const filteredFocusItems = computed(() => 
      focusItems.value.filter(item => !item.highest_entry || item.highest_entry.competency_level_weighting < 4).slice(0, 7)
    )

    // Load individual backend calls
    const loadProfileData = async () => {
      try {
        const response = await api.get(`/profile/${route.params.id}`);
        profile.value = response.data;
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
        competencyIndicators.value = response.data.filter(compt => !compt.discontinued_date);
      } catch (error) {
        console.error("Error while fetching competencies:", error);
      }
		};

    // Gets highest level competency for each category
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
        const response = await api.get('/smart-goals', {
          params: { profile_id: route.params.id }
        });
      
      userGoals.value = Array.isArray(response.data) ? response.data : [];

      let incompleteGoals = userGoals.value.filter(goal => {
        return Number(goal.goal_status_id) !== 3;
      });

      incompleteGoals.sort((a, b) => {
        // Goals without end dates go to the very end
        if (!a.end_date) return 1;
        if (!b.end_date) return -1;
        
        return new Date(a.end_date) - new Date(b.end_date);
      });

      priorityGoals.value = incompleteGoals.slice(0, 3);

      } catch (error) {
        console.error("Error fetching goals:", error);
        userGoals.value = [];
        priorityGoals.value = [];
      }
    };

    const loadNetworkingEvents = async () => {
      try { 
        const response = await api.get('/networking-events')
        allEvents.value = response.data
      } catch (error) {
        console.error('Error fetching networking events:', error)
        allEvents.value = []
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
        // Run all backend calls at the same time
        await Promise.all([
          loadProfileData(),
          loadUserCompetencyData(),
          loadCompetencyIndicators(),
          loadUserGoals(),
          loadUserActions(),
          loadCompetencyIndicatorsWithCount(),
          loadNetworkingEvents()
        ])

        // Depends on data fetched above so runs afterwards
        await updateChart()

        // Calculate the total weight based of the weight of each competency. reduce goes through and accumulates a single value
        const totalWeight = focusItems.value.reduce((acc, item) => {
            const weight = item.highest_entry?.competency_level_weighting || 0;
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

    // Load all data required for the dashboard
    loadData();

    watch(() => route.params.id, () => {
      loadData();
    });
</script>

<style scoped>
.sec-title {
  font-family: 'Martel', serif;
  font-size: clamp(1.3rem, 4vw, 2rem);
  color: #2b2b2bc5;
  font-weight: lighter;
  margin-bottom: 1.5rem;
  margin-top: 1.5rem;
}

.stat-card {
  border-radius: 1.5rem;
  border: 1px solid #bababa;
  height: auto;
  min-height: 12rem;
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
  font-size: clamp(0.75rem, 2.5vw, 1.5rem);
  color: #878787;
}

.stat-data {
  font-family: 'Martian Mono', monospace;
  font-size: clamp(1.1rem, 3vw, 1.8rem);
  font-weight: 300;
  color: #606060;
}

.circle {
  width: clamp(1.8rem, 4vw, 2.5rem);
  height: clamp(1.8rem, 4vw, 2.5rem);
  border-radius: 2rem;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.circle:hover {
  transform: translateY(-5px) rotate(30deg);
  box-shadow: 0 3px 8px #c9c9c9;
  border: 1px solid #c7c7c7;
}

.circle-light {
  background: #ffffff;
}

.circle-dark {
  background: #f1f1f1;
}

.arrow-img {
  width: clamp(1.2rem, 3vw, 2rem);
  height: clamp(1.2rem, 3vw, 2rem);
  object-fit: contain;
}

.info-btn {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}

.info-img {
  width: 1.3rem;
  height: 1.3rem;
  object-fit: contain;
  opacity: 0.5;
  transition: opacity 0.2s ease;
}

.info-img:hover {
  opacity: 1;
}

.info-btn::after {
  content: attr(data-tooltip);
  position: absolute;
  top: calc(100% + 0.4rem);
  background: #727272;
  color: #ffffff;
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.75rem;
  white-space: normal;
  width: clamp(8rem, 40vw, 15rem);
  padding: 0.4rem 0.65rem;
  border-radius: 0.5rem;
  box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.2);
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.15s ease;
  z-index: 5;
}

.info-btn:hover::after {
  opacity: 1;
}

.todo-card {
  background: #ffffff;
  border: 1px solid #d0d0d0;
  border-radius: 1.5rem;
  padding: 1rem 1.25rem;
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
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: clamp(0.85rem, 2vw, 1rem);
  font-weight: 500;
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
  font-size: clamp(0.85rem, 2vw, 1rem);
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
  padding: 1rem 1.25rem;
}

.week-scroll {
  overflow-x: auto;
  margin-bottom: 0.5rem;
}

.week-grid {
  display: grid;
  grid-template-columns: repeat(7, minmax(2.5rem, 1fr));
  gap: 0.25rem;
  margin-bottom: 0.75rem;
  min-width: 20rem;
}

.day-col {
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
}

.day-label {
  font-family: 'Martian Mono', monospace;
  font-size: clamp(0.55rem, 1.5vw, 0.7rem);
  color: #888888;
  text-align: center;
  margin-bottom: 0.2rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.day-events {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  min-height: 8rem;
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
  cursor: pointer;
}

.ev-name {
  font-family: 'Maven Pro', sans-serif;
  font-size: clamp(0.55rem, 1.5vw, 0.65rem);
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
  font-size: clamp(0.75rem, 2vw, 0.95rem);
}

.focus-table thead th {
  font-family: 'Martian Mono', monospace;
  color: #222222;
  font-weight: 200;
  font-size: clamp(0.85rem, 2vw, 1.2rem);
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
  font-size: clamp(0.85rem, 2vw, 1rem);
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

.loading {
  min-height: calc(100vh);
}

@media (max-width: 576px) {
  .stat-card {
    min-height: 8rem;
  }

  .todo-card, .week-card {
    padding: 0.75rem 1rem;
  }

  .day-events {
    min-height: 6rem;
  }
}

@media (min-width: 768px) {
  .todo-card, .week-card {
    padding: 1.5rem 1.75rem;
    min-height: 20rem;
  }

  .day-events {
    min-height: 15rem;
  }
}
</style>