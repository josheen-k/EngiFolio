<template>
  <div class="page">
    <StaffNavbar />

    <main class="container py-5">
      <section class="dash">
        <p class="eyebrow">Staff Dashboard</p>

        <h1>Welcome Back</h1>

        <p class="subtitle">
          Review student competency progress, monitor engagement,
          and provide meaningful feedback across assigned students.
        </p>
      </section>

      <!-- Summary stats -->
      <section class="stats-grid">
        <div class="stat-card">
          <span>Total Students</span>
          <strong>{{ students.length }}</strong>
        </div>

        <div class="stat-card">
          <span>Total Entries</span>
          <strong>{{ allEntries.length }}</strong>
        </div>

        <div class="stat-card warning">
          <span>Pending Feedback</span>
          <strong>{{ pendingFeedbackCount }}</strong>
        </div>

        <div class="stat-card success">
          <span>Reviewed Entries</span>
          <strong>{{ reviewedEntriesCount }}</strong>
        </div>
      </section>

      <!-- Dashboard cards -->
      <section class="dashboard-grid">
        <router-link
          to="/staff/students"
          class="dashboard-card"
        >
          <div class="card-icon">👥</div>

          <h3>Student Management</h3>

          <p>
            View assigned students, access profiles,
            review competencies, and provide feedback.
          </p>
        </router-link>
      </section>

      <!-- Recent entries -->
      <section class="activity-section">
        <div class="activity-card">
          <div class="activity-header">
  <h3>Recent Competency Entries</h3>

  <span
    v-if="unreadCount > 0"
    class="activity-badge"
  >
    {{ unreadCount }}
    {{ unreadCount === 1 ? 'new entry' : 'new entries' }}
  </span>
</div>

          <div
            v-if="recentEntries.length === 0"
            class="empty"
          >
            No recent entries found.
          </div>

          <div
            v-for="entry in recentEntries"
            :key="entry.entry_id"
            class="activity-item clickable"
            @click="openEntry(entry)"
          >
            <div
              v-if="!readEntries.includes(entry.entry_id)"
              class="activity-dot"
            ></div>

            <div class="activity-content">
              <div class="activity-top">
                <strong>
                  {{ entry.experience_title }}
                </strong>

                <span
                  class="feedback-status"
                  :class="hasFeedback(entry) ? 'reviewed' : 'pending'"
                >
                  {{ hasFeedback(entry) ? 'Feedback given' : 'Pending feedback' }}
                </span>
              </div>

              <p>
                {{ entry.student_name }}
                —
                {{ entry.indicator?.display_id }}

                {{
                  entry.indicator?.indicator_name ||
                  entry.indicator?.description
                }}
              </p>

              <p class="entry-meta">
                Level:
                {{ entry.entry_level?.competency_level || 'Not specified' }}
                ·
                Submitted:
                {{ formatDate(entry.created_at) }}
              </p>
            </div>
          </div>
        </div>
      </section>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'

import api from '@/services/api'
import Footer from '@/components/Footer.vue'
import StaffNavbar from '@/components/StaffNavbar.vue'

const router = useRouter()

const staffUserId = 4 // staff user id hardcoded to 4 for the project scope

const students = ref([])
const allEntries = ref([])

const readEntries = ref(
  JSON.parse(localStorage.getItem('readEntries') || '[]')
)

const fetchDashboardData = async () => {
  try {
    const studentRes = await api.get(
      `/staff/my-students?staff_id=${staffUserId}`
    )

    students.value = studentRes.data

    const entryRequests = students.value.map(student =>
      api.get(`/competency-entries/${student.profile_id}`)
    )

    const entryResponses = await Promise.allSettled(entryRequests) // promise all settled is used to return all requests, even if one fails

    allEntries.value = entryResponses.flatMap((result, index) => {
      if (result.status !== 'fulfilled') {
        return []
      }

      return result.value.data.map(entry => ({
        ...entry,

        student_name:
          `${students.value[index].first_name} ${students.value[index].last_name}`,

        student_profile_id:
          students.value[index].profile_id,
      }))
    })
  } catch (err) {
    console.error(
      'Dashboard fetch error:',
      err.response?.data || err
    )
  }
}

const hasFeedback = (entry) => {
  return entry.competency_feedback?.length > 0 // optional chaining used here
}

// count the pending feedback entries
const pendingFeedbackCount = computed(() => {
  return allEntries.value.filter(entry => !hasFeedback(entry)).length
})

// count the reviewed feedback entries
const reviewedEntriesCount = computed(() => {
  return allEntries.value.filter(entry => hasFeedback(entry)).length
})

// recent entries, restricted to 5 at most
const recentEntries = computed(() => {
  return [...allEntries.value]
    .sort(
      (a, b) =>
        new Date(b.created_at) - new Date(a.created_at)
    )
    .slice(0, 5)
})

const unreadCount = computed(() => {
  return recentEntries.value.filter(
    entry =>
      !readEntries.value.includes(entry.entry_id)
  ).length
})

const formatDate = (date) => {
  if (!date) return 'No date'

  return new Date(date).toLocaleDateString()
}

const openEntry = (entry) => {
  if (!readEntries.value.includes(entry.entry_id)) {
    readEntries.value.push(entry.entry_id)

    localStorage.setItem(
      'readEntries',
      JSON.stringify(readEntries.value) // conversion into a string
    )
  }

  router.push('/staff/students')
}

onMounted(fetchDashboardData)
</script>

<style scoped>
.page {
  min-height: 100vh;
  background: #f8f8fb;
}

/* Hero */

.dash {
  background:
    linear-gradient(
      135deg,
      #140f50,
      #302a86
    );
  color: white;
  padding: 42px;
  border-radius: 24px;
  margin-bottom: 34px;
  box-shadow: 0 12px 35px rgba(20, 15, 80, 0.25);
}

.eyebrow {
  font-family: 'Montserrat Alternates', sans-serif;
  text-transform: uppercase;
  letter-spacing: 0.12rem;
  font-size: 0.8rem;
  color: #d7d4ff;
  margin-bottom: 8px;
}

.dash h1 {
  font-family: 'Martel', serif;
  font-size: 2.6rem;
  margin-bottom: 10px;
}

.subtitle {
  font-family: 'Maven Pro', sans-serif;
  color: #e5e3ff;
  max-width: 620px;
  margin: 0;
  line-height: 1.6;
  font-size: 1.05rem;
}

/* Stats */

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 18px;
  margin-bottom: 34px;
}

.stat-card {
  background: white;
  border-radius: 20px;
  padding: 22px;
  border: 1px solid #ececf3;
  box-shadow: 0 6px 24px rgba(0,0,0,0.05);
}

.stat-card span {
  font-family: 'Maven Pro', sans-serif;
  color: #666;
  font-size: 0.9rem;
}

.stat-card strong {
  display: block;
  font-family: 'Martian Mono', monospace;
  font-size: 2rem;
  margin-top: 8px;
  color: #222;
  font-weight: 200;
}

.stat-card.warning strong {
  color: #b26b00;
}

.stat-card.success strong {
  color: #15803d;
}

/* Dashboard cards */

.dashboard-grid {
  display: grid;
  grid-template-columns:
    repeat(auto-fill, minmax(280px, 1fr));
  gap: 22px;
  margin-bottom: 34px;
}

.dashboard-card {
  display: block;
  padding: 26px;
  border-radius: 22px;
  background: white;
  border: 1px solid #ececf3;
  box-shadow: 0 6px 24px rgba(0,0,0,0.06);
  text-decoration: none;
  color: inherit;
  transition:
  transform 0.2s ease,
  box-shadow 0.2s ease;
}

.dashboard-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 32px rgba(0,0,0,0.1);
}

.card-icon {
  width: 52px;
  height: 52px;
  border-radius: 16px;
  background: #f0efff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.6rem;
  margin-bottom: 18px;
}

.dashboard-card h3 {
  font-family: 'Martel', serif;
  font-size: 1.3rem;
  margin-bottom: 10px;
  color: #222222;
}

.dashboard-card p {
  font-family: 'Maven Pro', sans-serif;
  color: #666;
  line-height: 1.6;
  margin: 0;
}

/* Activity */

.activity-section {
  margin-top: 12px;
}

.activity-card {
  background: white;
  border-radius: 22px;
  padding: 28px;
  border: 1px solid #ececf3;
  box-shadow: 0 6px 24px rgba(0,0,0,0.05);
}

.activity-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.activity-header h3 {
  font-family: 'Martel', serif;
  margin: 0;
}

.activity-badge {
  background: #f0efff;
  color: #302a86;
  padding: 6px 14px;
  border-radius: 999px;
  font-size: 0.85rem;
  font-weight: 600;
  font-family: 'Maven Pro', sans-serif;
}

.activity-item {
  display: flex;
  gap: 14px;
  padding: 16px 0;
  border-bottom: 1px solid #f1f1f1;
}

.activity-item:last-child {
  border-bottom: none;
}

.activity-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #302a86;
  margin-top: 6px;
  flex-shrink: 0; /*use to maintain the size*/
}

.activity-content {
  flex: 1;
}

.activity-top {
  display: flex;
  justify-content: space-between;
  gap: 12px;
  align-items: center;
}

.activity-item strong {
  font-family: 'Montserrat Alternates', sans-serif;
  color: #222222;
}

.activity-item p {
  font-family: 'Maven Pro', sans-serif;
  margin: 4px 0 0;
  color: #666;
  line-height: 1.5;
}

.entry-meta {
  font-size: 0.82rem;
  color: #888888 !important;
}

.feedback-status {
  padding: 0.3rem 0.75rem;
  border-radius: 999px;
  font-size: 0.78rem;
  font-weight: 600;
  font-family: 'Maven Pro', sans-serif;
  white-space: nowrap;
}

.feedback-status.reviewed {
  background: #dcfce7;
  color: #15803d;
}

.feedback-status.pending {
  background: #ffe2e2;
  color: #b42318;
}

.clickable {
  cursor: pointer;
  transition:
    background 0.2s ease,
    padding-left 0.2s ease;
}

.clickable:hover {
  background: #f8f8ff;
  border-radius: 12px;
  padding-left: 10px;
}

.empty {
  font-family: 'Maven Pro', sans-serif;
  color: #777;
}
</style>