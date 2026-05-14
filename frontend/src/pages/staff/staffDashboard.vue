<template>
  <div class="page">
    <StaffNavbar />



    <main class="container py-5">

      <section class="dash">
        <p class="eyebrow">Staff Portal</p>

        <h1>Staff Dashboard</h1>

        <p class="subtitle">
          Manage assigned students, review competency submissions,
          and provide feedback from one place.
        </p>
      </section>


      <section class="dashboard-grid">

        <router-link
          to="/staff/students"
          class="dashboard-card"
        >
          <div class="card-icon">👥</div>

          <h3>My Students</h3>

          <p>
            View students assigned to you and access their profiles.
          </p>
        </router-link>

        <router-link
          to="/staff/competency-review"
          class="dashboard-card"
        >
          <div class="card-icon">📝</div>

          <h3>Competency Reviews</h3>

          <p>
            Review competency entries and provide structured feedback.
          </p>
        </router-link>


      </section>

      <section class="activity-section">
  <div class="activity-card">
    <div class="activity-header">
      <h3>Recent Competency Entries</h3>
      <span class="activity-badge">
  {{ recentEntries.length - readEntries.length }} unread
</span>
    </div>

    <div v-if="recentEntries.length === 0" class="empty">
      No recent entries found.
    </div>

    <div v-for="entry in recentEntries" :key="entry.entry_id" class="activity-item clickable" @click="openEntry(entry)" >
      <div
  v-if="!readEntries.includes(entry.entry_id)"
  class="activity-dot"
></div>

      <div>
        <strong>{{ entry.experience_title }}</strong>
        <p>
          {{ entry.student_name }} —
          {{ entry.indicator?.display_id }}
          {{ entry.indicator?.indicator_name }}
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
import api from '@/services/api'
import Footer from '@/components/Footer.vue'
import StaffNavbar from '@/components/StaffNavbar.vue'
import { useRouter } from 'vue-router'

const router = useRouter();

const user = JSON.parse(localStorage.getItem('user'))
const staffUserId = user?.user_id || 4

const students = ref([])
const allEntries = ref([])
const readEntries = ref(
  JSON.parse(localStorage.getItem('readEntries') || '[]')
)

const fetchDashboardData = async () => {
  try {
    const studentRes = await api.get(`/staff/my-students?staff_id=${staffUserId}`)
    students.value = studentRes.data

    const entryRequests = students.value.map(student =>
      api.get(`/users/${student.user_id}/competency-entries`)
    )

    const entryResponses = await Promise.all(entryRequests)

    allEntries.value = entryResponses.flatMap((res, index) =>
      res.data.map(entry => ({
        ...entry,
        student_name: `${students.value[index].first_name} ${students.value[index].last_name}`,
      }))
    )
  } catch (err) {
    console.error('Dashboard fetch error:', err.response?.data || err)
  }
}
// this is for notifications on the staff homepage
const openEntry = (entry) => {
  if (!readEntries.value.includes(entry.entry_id)) {
    readEntries.value.push(entry.entry_id)

    localStorage.setItem(
      'readEntries',
      JSON.stringify(readEntries.value)
    )
  }

  router.push('/staff/competency-review')
}

const recentEntries = computed(() => {
  return [...allEntries.value]
    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
    .slice(0, 5)
})

onMounted(fetchDashboardData)
</script>

<style scoped>
.page {
  min-height: 100vh;
  background: #f8f8fb;
}



.dash {
  background: linear-gradient(135deg, #140f50, #302a86);
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
  color: #e5e3ff;
  max-width: 620px;
  margin: 0;
  line-height: 1.6;
  font-size: 1.05rem;
}

.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
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

  box-shadow:
    0 12px 32px rgba(0,0,0,0.1);
}

.muted-card {
  opacity: 0.78;
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
  font-size: 1.3rem;
  margin-bottom: 10px;
}

.dashboard-card p {
  color: #666;
  line-height: 1.5;
  margin: 0;
}


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
  margin: 0;
}

.activity-badge {
  background: #f0efff;
  color: #302a86;
  padding: 6px 14px;
  border-radius: 999px;
  font-size: 0.85rem;
  font-weight: 600;
}

.activity-item {
  display: flex;
  gap: 14px;
  padding: 14px 0;
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
  flex-shrink: 0;
}

.activity-item p {
  margin: 4px 0 0;
  color: #666;
}
.clickable {
  cursor: pointer;
  transition: background 0.2s ease;
}

.clickable:hover {
  background: #f8f8ff;
  border-radius: 12px;
}
</style>

// need to integrate both the pages into one single page, and in a tabular format, add the view profile as a button, and also we need the row
// to pop out