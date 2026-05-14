<template>
  <div class="page">
    <Navbar />

    <main class="container py-5">
      <div class="header">
        <h2>Competency Entries</h2>
        <button class="btn btn-dark" @click="openForm">+ Add Entry</button>
      </div>

      <input
        v-model="search"
        class="search"
        placeholder="Search competency entries"
      />

      <div class="table-box">
        <table>
          <thead>
            <tr>
              <th>Experience</th>
              <th>Indicator</th>
              <th>Year</th>
              <th>Tasks</th>
              <th>Start Date</th>
              <th></th>
              <th>Feedback</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="entry in filteredEntries" :key="entry.entry_id">
              <td>{{ entry.experience_title }}</td>

              <td>
                {{ entry.indicator?.display_id }} -
                {{ entry.indicator?.indicator_name }}
              </td>

              <td>{{ entry.associated_year }}</td>
              <td>{{ entry.experience_tasks }}</td>
              <td>{{ entry.start_date }}</td>

              <td class="actions-cell">
                <ButtonsStyle
                  @edit="editEntry(entry)"
                  @delete="deleteEntry(entry.entry_id)"
                />
              </td>

              <td>
                <button class="btn btn-dark btn-sm" @click="viewFeedback(entry)">
                  View Feedback
                </button>
              </td>
            </tr>

            <tr v-if="filteredEntries.length === 0">
              <td colspan="7" class="empty">
                No competency entries found
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Add/Edit Modal -->
      <div v-if="showForm" class="modal-overlay" @click.self="closeForm">
        <div class="modal-card">
          <h3>{{ editMode ? 'Edit Entry' : 'Add Entry' }}</h3>

          <select v-model="form.indicator_id">
            <option disabled value="">Select indicator</option>
            <option
              v-for="indicator in indicators"
              :key="indicator.indicator_id"
              :value="indicator.indicator_id"
            >
              {{ indicator.display_id }} - {{ indicator.indicator_name }}
            </option>
          </select>

          <input v-model="form.experience_title" placeholder="Experience title" />
          <input v-model.number="form.associated_year" type="number" placeholder="Associated year" />
          <textarea v-model="form.experience_tasks" placeholder="Experience tasks"></textarea>
          <textarea v-model="form.key_learnings" placeholder="Key learnings"></textarea>
          <textarea v-model="form.future_applications" placeholder="Future applications"></textarea>

          <select v-model="form.entry_level_id">
            <option disabled value="">Select level</option>
            <option value="1">Emerging</option>
            <option value="2">Developing</option>
            <option value="3">Proficient</option>
            <option value="4">Confident</option>
          </select>

          <select v-model="form.entry_status_id">
            <option disabled value="">Select status</option>
            <option value="1">Draft</option>
            <option value="2">Submitted</option>
            <option value="3">Reviewed</option>
          </select>

          <input type="date" v-model="form.start_date" />
          <input type="date" v-model="form.end_date" />

          <div class="btn-row">
            <button class="btn btn-dark" @click="saveEntry">
              {{ editMode ? 'Update' : 'Create' }}
            </button>

            <button class="btn btn-light" @click="closeForm">
              Cancel
            </button>
          </div>
        </div>
      </div>

      <!-- Feedback Modal -->
      <div
        v-if="selectedFeedbackEntry"
        class="modal-overlay"
        @click.self="closeFeedback"
      >
        <div class="modal-card">
          <h3>Feedback for {{ selectedFeedbackEntry.experience_title }}</h3>

          <p v-if="feedbackList.length === 0" class="empty-feedback">
            No feedback received yet.
          </p>

          <div
            v-for="fb in feedbackList"
            :key="fb.feedback_id"
            class="feedback-box"
          >
            <p>{{ fb.feedback_content }}</p>
            <small>From staff ID: {{ fb.staff_id }}</small>
          </div>

          <div class="btn-row">
            <button class="btn btn-light" @click="closeFeedback">
              Close
            </button>
          </div>
        </div>
      </div>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'
import ButtonsStyle from '@/components/ButtonsStyle.vue'
import api from '@/services/api'
import { useRoute } from 'vue-router'

const route = useRoute()
const userId = route.params.id

const entries = ref([])
const indicators = ref([])
const search = ref('')

const showForm = ref(false)
const editMode = ref(false)

const selectedFeedbackEntry = ref(null)
const feedbackList = ref([])

const form = ref({
  entry_id: null,
  indicator_id: '',
  experience_title: '',
  associated_year: '',
  experience_tasks: '',
  key_learnings: '',
  future_applications: '',
  entry_level_id: '',
  entry_status_id: '',
  start_date: '',
  end_date: '',
})

const fetchEntries = async () => {
  try {
    const res = await api.get(`/users/${userId}/competency-entries`)
    entries.value = res.data
  } catch (err) {
    console.error('Fetch entries error:', err)
  }
}

const fetchIndicators = async () => {
  try {
    const res = await api.get('/competency-indicators')
    indicators.value = res.data
  } catch (err) {
    console.error('Fetch indicators error:', err)
  }
}

onMounted(() => {
  fetchEntries()
  fetchIndicators()
})

const filteredEntries = computed(() => {
  return entries.value.filter(entry =>
    entry.experience_title?.toLowerCase().includes(search.value.toLowerCase()) ||
    entry.indicator?.indicator_name?.toLowerCase().includes(search.value.toLowerCase()) ||
    entry.indicator?.display_id?.toLowerCase().includes(search.value.toLowerCase())
  )
})

const openForm = () => {
  editMode.value = false
  form.value = {
    entry_id: null,
    indicator_id: '',
    experience_title: '',
    associated_year: '',
    experience_tasks: '',
    key_learnings: '',
    future_applications: '',
    entry_level_id: '',
    entry_status_id: '',
    start_date: '',
    end_date: '',
  }
  showForm.value = true
}

const closeForm = () => {
  showForm.value = false
}

const editEntry = (entry) => {
  editMode.value = true
  form.value = { ...entry }
  showForm.value = true
}
const saveEntry = async () => {
  try {
    const payload = {
      indicator_id: form.value.indicator_id,
      experience_title: form.value.experience_title,
      associated_year: form.value.associated_year,
      experience_tasks: form.value.experience_tasks,
      key_learnings: form.value.key_learnings,
      future_applications: form.value.future_applications,
      entry_level_id: form.value.entry_level_id,
      entry_status_id: form.value.entry_status_id,
      start_date: form.value.start_date,
      end_date: form.value.end_date || null,
    }

    if (editMode.value) {
      await api.put(`/users/${userId}/competency-entries/${form.value.entry_id}`, payload)
    } else {
      await api.post(`/users/${userId}/competency-entries`, payload)
    }

    closeForm()
    fetchEntries()
  } catch (err) {
    console.log(err.response)

    alert(
      JSON.stringify(
        err.response?.data,
        null,
        2
      )
    )
  }
}

const deleteEntry = async (entryId) => {
  if (!confirm('Delete this competency entry?')) return

  try {
    await api.delete(`/users/${userId}/competency-entries/${entryId}`)
    fetchEntries()
  } catch (err) {
    console.error('Delete entry error:', err)
  }
}

const viewFeedback = async (entry) => {
  try {
    selectedFeedbackEntry.value = entry
    const res = await api.get(`/competency-entries/${entry.entry_id}/feedback`)
    feedbackList.value = res.data
  } catch (err) {
    console.error('Fetch feedback error:', err.response?.data || err)
    feedbackList.value = []
  }
}

const closeFeedback = () => {
  selectedFeedbackEntry.value = null
  feedbackList.value = []
}
</script>

<style scoped>
.page {
  min-height: 100vh;
  background: #fff;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.search {
  margin-bottom: 25px;
  padding: 12px;
  width: 320px;
  border-radius: 12px;
  border: 1px solid #ddd;
}

.table-box {
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.05);
  overflow: hidden;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th {
  text-align: left;
  padding: 14px;
  background: #fafafa;
}

td {
  padding: 14px;
  border-top: 1px solid #eee;
}

.empty {
  text-align: center;
  color: #777;
}

.actions-cell {
  display: flex;
  align-items: center;
  gap: 10px;
}

.btn-sm {
  padding: 6px 12px;
  font-size: 0.8rem;
  border-radius: 10px;
}

.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.35);
  backdrop-filter: blur(6px);
  z-index: 1000;
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-card {
  background: white;
  width: 520px;
  max-width: 92%;
  max-height: 85vh;
  overflow-y: auto;
  padding: 25px;
  border-radius: 18px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.25);
}

.modal-card input,
.modal-card textarea,
.modal-card select {
  width: 100%;
  margin-bottom: 12px;
  padding: 10px;
  border-radius: 10px;
  border: 1px solid #ddd;
}

.feedback-box {
  border: 1px solid #eee;
  border-radius: 12px;
  padding: 14px;
  margin-bottom: 12px;
  background: #fafafa;
}

.feedback-box p {
  margin-bottom: 6px;
}

.feedback-box small {
  color: #777;
}

.empty-feedback {
  color: #777;
  margin: 16px 0;
}

.btn-row {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
}
</style>