<template>
  <div class="page">
    <StaffNavbar />

    <main class="container py-5">
      <div class="header">
        <h2>Assigned Students</h2>
      </div>

      <div v-if="students.length === 0" class="empty">
        No students assigned.
      </div>

      <div class="student-grid">
        <div v-for="student in students" :key="student.user_id" class="student-card" >
          <div class="avatar">
            {{ student.first_name.charAt(0) }}
          </div>

          <div class="student-info">
            <h3>
              {{ student.first_name }}
              {{ student.last_name }}
            </h3>

            <p>{{ student.email }}</p>

            <div class="btn-row">
              <router-link
                class="btn btn-dark"
                :to="`/student/competency-entry/${student.user_id}`"
              >
                View Competencies
              </router-link>

              <router-link
                class="btn btn-light"
                :to="`/profile/${student.user_id}`"
              >
                View Profile
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import StaffNavbar from '@/components/StaffNavbar.vue'
import Footer from '@/components/Footer.vue'
import api from '@/services/api'

const user = JSON.parse(localStorage.getItem('user'))
const staffUserId = user?.user_id || 4

const students = ref([])

const fetchStudents = async () => {
  try {
      const res = await api.get(
      `/staff/my-students?staff_id=${staffUserId}`
    )

    students.value = res.data
  } catch (err) {
    console.error(
      'Fetch students error:',
      err.response?.data || err
    )
  }
}

onMounted(fetchStudents)
</script>

<style scoped>
.page {
  min-height: 100vh;
  background: white;
}

.header {
  margin-bottom: 30px;
}

.student-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 20px;
}

.student-card {
  display: flex;
  gap: 18px;
  padding: 22px;
  border-radius: 18px;
  background: white;
  border: 1px solid #eee;
  box-shadow: 0 4px 20px rgba(0,0,0,0.06);
}

.avatar {
  width: 65px;
  height: 65px;
  border-radius: 50%;
  background: #140F50;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  font-weight: 700;
  flex-shrink: 0;
}

.student-info h3 {
  margin-bottom: 6px;
}

.student-info p {
  color: #666;
  margin-bottom: 16px;
}

.btn-row {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.empty {
  color: #777;
}
</style>