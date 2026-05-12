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
              <th>Actions</th>
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
              <td>
                <button class="edit-btn" @click="editEntry(entry)">Edit</button>
                <button class="delete-btn" @click="deleteEntry(entry.entry_id)">Delete</button>
              </td>
            </tr>

            <tr v-if="filteredEntries.length === 0">
              <td colspan="6" class="empty">No competency entries found</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="showForm" class="form-box">
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
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'
import api from '@/services/api'

const user = JSON.parse(localStorage.getItem('user'))
const userId = user?.user_id || 1

const entries = ref([])
const indicators = ref([])
const search = ref('')

const showForm = ref(false)
const editMode = ref(false)

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
    entry.experience_title?.toLowerCase().includes(search.value.toLowerCase())
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
    console.error('Save entry error:', err.response?.data || err)
  }
}

const deleteEntry = async (entryId) => {
  try {
    await api.delete(`/users/${userId}/competency-entries/${entryId}`)
    fetchEntries()
  } catch (err) {
    console.error('Delete entry error:', err)
  }
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

.edit-btn,
.delete-btn {
  border-radius: 10px;
  padding: 6px 12px;
  margin-right: 8px;
}

.edit-btn {
  background: #e5e7eb;
}

.delete-btn {
  background: #ef4444;
  color: white;
}

.form-box {
  margin-top: 30px;
  padding: 25px;
  border-radius: 16px;
  border: 1px solid #eee;
  width: 500px;
  background: white;
  box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}

.form-box input,
.form-box textarea,
.form-box select {
  width: 100%;
  margin-bottom: 12px;
  padding: 10px;
  border-radius: 10px;
  border: 1px solid #ddd;
}

.btn-row {
  display: flex;
  gap: 10px;
}
</style>