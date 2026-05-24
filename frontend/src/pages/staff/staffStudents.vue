<template>
  <div class="page">
    <StaffNavbar />

    <main class="container py-5">
      <div class="header">
        <p class="eyebrow">Staff Portal</p>
        <h2>Assigned Students</h2>
      </div>

      <p v-if="errorMessage" class="error">
        {{ errorMessage }}
      </p>

      <div class="controls">
        <input v-model="search" class="search" placeholder="Search students" />

        <select v-model="selectedDegree" class="search">
          <option value="">All degrees</option>
          <option v-for="degree in degreeOptions" :key="degree" :value="degree">
            {{ degree }}
          </option>
        </select>

        <select v-model="selectedSpecialisation" class="search">
          <option value="">All specialisations</option>
          <option
            v-for="specialisation in specialisationOptions"
            :key="specialisation"
            :value="specialisation"
          >
            {{ specialisation }}
          </option>
        </select>

        <!-- <select v-model="selectedIndicator" class="search">
          <option value="">All indicators</option>
          <option
            v-for="indicator in indicatorOptions"
            :key="indicator.id"
            :value="indicator.id"
          >
            {{ indicator.label }}
          </option>
        </select> -->

        <select v-model="sortBy" class="search">
          <option value="name">Sort by name</option>
          <option value="entries">Sort by entries submitted</option>
          <option value="degree">Sort by degree</option>
          <option value="specialisation">Sort by specialisation</option>
        </select>
      </div>

      <div class="table-box">
        <table class="students-table">
          <thead>
            <tr>
              <th>Student</th>
              <th>Email</th>
              <th>Degree</th>
              <th>Specialisation</th>
              <th>Entries Submitted</th>
              <th>Profile</th>
              <th>Competencies</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="student in filteredStudents" :key="student.user_id">
              <td>
                <div class="student-cell">
                  <div class="avatar">
                    {{ getInitials(student) }}
                  </div>

                  <span>
                    {{ student.first_name }}
                    {{ student.last_name }}
                  </span>
                </div>
              </td>

              <td>{{ student.email }}</td>

              <td>{{ student.degree_title || 'Not specified' }}</td>

              <td>{{ student.specialisation || 'Not specified' }}</td>

              <td>
                <span
                  class="entry-count"
                  :class="{
                    low: getEntryCount(student) <= 5,
                    medium: getEntryCount(student) >= 6 && getEntryCount(student) <= 10,
                    high: getEntryCount(student) >= 11
                  }"
                >
                  {{ getEntryCount(student) }}
                </span>
              </td>

              <td>
                <router-link
                  class="btn btn-filter"
                  :to="`/profile/${student.user_id}`"
                >
                  View Profile
                </router-link>
              </td>

              <td>
                <button class="btn btn-add" @click="selectStudent(student)">
                  View Entries
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Entries Modal -->
      <div
        v-if="selectedStudent"
        class="modal-overlay"
        @click.self="closeEntries"
      >
        <div class="entries-modal-card">
          <div class="entries-header">
            <div>
              <h3>
                Competency Entries for
                {{ selectedStudent.first_name }}
                {{ selectedStudent.last_name }}
              </h3>

              <p class="entries-subtitle">
                {{ filteredEntries.length }} entries submitted.
              </p>
            </div>

            <button class="btn btn-filter" @click="closeEntries">
              Close
            </button>
          </div>

          <p v-if="loading" class="loading">
            Loading...
          </p>

          <div v-if="!loading && filteredEntries.length === 0" class="empty">
            No competency entries found.
          </div>

          <table v-if="filteredEntries.length > 0" class="students-table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Indicator</th>
                <th>Level</th>
                <th>Year</th>
                <th>Tasks</th>
                <th>Start Date</th>
                <th>Feedback</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="entry in filteredEntries"
                :key="entry.entry_id"
                @click="openDetails(entry)"
              >
                <td>{{ entry.experience_title }}</td>

                <td>
                  {{ entry.indicator?.display_id }}
                  -
                  {{
                    entry.indicator?.indicator_name ||
                    entry.indicator?.description
                  }}
                </td>

                <td>
                  {{ entry.entry_level?.competency_level || 'Not specified' }}
                </td>

                <td>{{ entry.associated_year }}</td>

                <td class="truncate">
                  {{ entry.experience_tasks }}
                </td>

                <td>{{ entry.start_date }}</td>

                <td @click.stop>
                  <button class="btn btn-add" @click="openFeedback(entry)">
                    Give Feedback
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Details Modal -->
      <div
        v-if="selectedDetails"
        class="modal-overlay"
        @click.self="closeDetails"
      >
        <div class="modal-card">
          <h3>{{ selectedDetails.experience_title }}</h3>

          <div class="pill-row">
            <span class="pill-tag">
              Competency {{ selectedDetails.indicator?.display_id }}
            </span>

            <span class="pill-tag">
              {{ selectedDetails.entry_level?.competency_level || 'Not specified' }}
            </span>

            <span class="pill-tag">
              Year {{ selectedDetails.associated_year }}
            </span>
          </div>

          <p class="date-txt">
            {{ selectedDetails.start_date }}
            –
            {{ selectedDetails.end_date || 'Present' }}
          </p>

          <div class="details-section">
            <p class="section-label">Experience & Tasks</p>
            <p class="body-txt">{{ selectedDetails.experience_tasks }}</p>
          </div>

          <div class="details-section">
            <p class="section-label">Key Learnings</p>
            <p class="body-txt">
              {{ selectedDetails.key_learnings || 'No key learnings added.' }}
            </p>
          </div>

          <div class="details-section">
            <p class="section-label">Future Applications</p>
            <p class="body-txt">
              {{
                selectedDetails.future_applications ||
                'No future applications added.'
              }}
            </p>
          </div>

          <div class="btn-row">
            <button class="btn btn-add" @click="openFeedback(selectedDetails)">
              Give Feedback
            </button>

            <button class="btn btn-filter" @click="closeDetails">
              Close
            </button>
          </div>
        </div>
      </div>

      <!-- Feedback Modal -->
      <div
        v-if="selectedEntry"
        class="modal-overlay"
        @click.self="closeFeedback"
      >
        <div class="modal-card">
          <h3>
            Feedback for {{ selectedEntry.experience_title }}
          </h3>

          <div
            v-if="selectedEntry?.competency_feedback?.length"
            class="previous-feedback"
          >
            <h4>Previous Feedback</h4>

            <div
              v-for="feedback in selectedEntry.competency_feedback"
              :key="feedback.feedback_id"
              class="feedback-item"
            >
              <div class="feedback-meta">
                <strong>
                  {{ feedback.staff?.first_name || 'Staff' }}
                  {{ feedback.staff?.last_name || '' }}
                </strong>

                <span>
                  {{ formatDate(feedback.created_at) }}
                </span>
              </div>

              <p>{{ feedback.feedback_content }}</p>
            </div>
          </div>

          <div v-else class="previous-feedback empty-feedback">
            No previous feedback for this entry.
          </div>

          <textarea
            v-model="feedbackText"
            placeholder="Write new feedback..."
          ></textarea>

          <p v-if="feedbackError" class="feedback-error">
            {{ feedbackError }}
          </p>

          <p v-if="feedbackSuccess" class="feedback-success">
            {{ feedbackSuccess }}
          </p>

          <div class="btn-row">
            <button
              class="btn btn-add"
              @click="submitFeedback"
              :disabled="feedbackLoading"
            >
              {{ feedbackLoading ? 'Submitting...' : 'Submit Feedback' }}
            </button>

            <button class="btn btn-filter" @click="closeFeedback">
              Cancel
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

import StaffNavbar from '@/components/StaffNavbar.vue'
import Footer from '@/components/Footer.vue'
import api from '@/services/api'

const staffUserId = 4

const students = ref([])
const entries = ref([])

const selectedStudent = ref(null)
const selectedProfileId = ref(null)

const search = ref('')
const selectedDegree = ref('')
const selectedSpecialisation = ref('')
const selectedIndicator = ref('')
const sortBy = ref('name')

const loading = ref(false)
const errorMessage = ref('')

const selectedDetails = ref(null)
const selectedEntry = ref(null)

const feedbackText = ref('')
const feedbackLoading = ref(false)
const feedbackError = ref('')
const feedbackSuccess = ref('')

const fetchStudents = async () => {
  try {
    loading.value = true
    errorMessage.value = ''

    const res = await api.get(
      `/staff/my-students?staff_id=${staffUserId}`
    )

    students.value = await Promise.all(
      res.data.map(async student => {
        try {
          const entryRes = await api.get(
            `/competency-entries/${student.profile_id}`
          )

          return {
            ...student,
            entries: Array.isArray(entryRes.data)
              ? entryRes.data
              : []
          }
        } catch {
          return {
            ...student,
            entries: []
          }
        }
      })
    )
  } catch (err) {
    console.error(
      'Fetch students error:',
      err.response?.data || err
    )

    errorMessage.value = 'Could not load assigned students.'
  } finally {
    loading.value = false
  }
}

const getEntryCount = (student) => {
  return student.entries?.length || 0
}

const degreeOptions = computed(() => {
  return [
    ...new Set(
      students.value
        .map(student => student.degree_title)
        .filter(Boolean)
    )
  ]
})

const specialisationOptions = computed(() => {
  return [
    ...new Set(
      students.value
        .map(student => student.specialisation)
        .filter(Boolean)
    )
  ]
})

const indicatorOptions = computed(() => {
  const indicators = []

  students.value.forEach(student => {
    student.entries?.forEach(entry => {
      if (entry.indicator) {
        indicators.push({
          id: entry.indicator.indicator_id,
          label:
            `${entry.indicator.display_id} - ` +
            `${entry.indicator.indicator_name || entry.indicator.description}`
        })
      }
    })
  })

  return [
    ...new Map(
      indicators.map(indicator => [indicator.id, indicator])
    ).values()
  ]
})

const filteredStudents = computed(() => {
  const term = search.value.toLowerCase()

  let result = students.value.filter(student => {
    const fullName =
      `${student.first_name} ${student.last_name}`.toLowerCase()

    const matchesSearch =
      fullName.includes(term) ||
      student.email?.toLowerCase().includes(term) ||
      student.degree_title?.toLowerCase().includes(term) ||
      student.specialisation?.toLowerCase().includes(term)

    const matchesDegree =
      !selectedDegree.value ||
      student.degree_title === selectedDegree.value

    const matchesSpecialisation =
      !selectedSpecialisation.value ||
      student.specialisation === selectedSpecialisation.value

    const matchesIndicator =
      !selectedIndicator.value ||
      student.entries?.some(entry =>
        String(entry.indicator_id) === String(selectedIndicator.value)
      )

    return (
      matchesSearch &&
      matchesDegree &&
      matchesSpecialisation &&
      matchesIndicator
    )
  })

  result = [...result].sort((a, b) => {
    if (sortBy.value === 'entries') {
      return getEntryCount(b) - getEntryCount(a)
    }

    if (sortBy.value === 'degree') {
      return (a.degree_title || '').localeCompare(b.degree_title || '')
    }

    if (sortBy.value === 'specialisation') {
      return (a.specialisation || '').localeCompare(b.specialisation || '')
    }

    return `${a.first_name} ${a.last_name}`.localeCompare(
      `${b.first_name} ${b.last_name}`
    )
  })

  return result
})

const selectStudent = async (student) => {
  selectedStudent.value = student
  selectedProfileId.value = student.profile_id

  await fetchEntries()
}

const closeEntries = () => {
  selectedStudent.value = null
  selectedProfileId.value = null
  entries.value = []
}

const fetchEntries = async () => {
  if (!selectedProfileId.value) return

  try {
    loading.value = true
    errorMessage.value = ''

    const res = await api.get(
      `/competency-entries/${selectedProfileId.value}`
    )

    entries.value = Array.isArray(res.data) ? res.data : []
  } catch (err) {
    console.error(
      'Fetch entries error:',
      err.response?.data || err
    )

    if (err.response?.status === 404) {
      entries.value = []
      errorMessage.value = ''
    } else {
      errorMessage.value = 'Could not load competency entries.'
    }
  } finally {
    loading.value = false
  }
}

const filteredEntries = computed(() => {
  return entries.value
})

const getInitials = (student) => {
  const first = student.first_name?.charAt(0) || ''
  const last = student.last_name?.charAt(0) || ''

  return `${first}${last}`.toUpperCase()
}

const formatDate = (date) => {
  if (!date) return 'No date'

  return new Date(date).toLocaleDateString()
}

const openDetails = (entry) => {
  selectedDetails.value = entry
}

const closeDetails = () => {
  selectedDetails.value = null
}

const openFeedback = (entry) => {
  selectedEntry.value = entry
  selectedDetails.value = null
  feedbackText.value = ''
  feedbackError.value = ''
  feedbackSuccess.value = ''
}

const closeFeedback = () => {
  selectedEntry.value = null
  feedbackText.value = ''
  feedbackError.value = ''
  feedbackSuccess.value = ''
}

const submitFeedback = async () => {
  feedbackError.value = ''
  feedbackSuccess.value = ''

  if (!feedbackText.value.trim()) {
    feedbackError.value = 'Please enter feedback before submitting.'
    return
  }

  try {
    feedbackLoading.value = true

    await api.post(
      `/competency-entries/${selectedEntry.value.entry_id}/feedback`,
      {
        staff_id: staffUserId,
        feedback_content: feedbackText.value,
      }
    )

    feedbackSuccess.value = 'Feedback submitted successfully.'

    await fetchEntries()

    setTimeout(() => {
      closeFeedback()
    }, 800)
  } catch (err) {
    console.error(
      'Submit feedback error:',
      err.response?.data || err
    )

    if (err.response?.status === 422) {
      feedbackError.value = 'Feedback validation failed.'
    } else if (err.response?.status >= 500) {
      feedbackError.value = 'Server error. Please try again later.'
    } else {
      feedbackError.value = 'Could not submit feedback.'
    }
  } finally {
    feedbackLoading.value = false
  }
}

onMounted(fetchStudents)
</script>

<style scoped>
.page {
  min-height: 100vh;
  background: #f8f8fb;
}

.header {
  margin-bottom: 24px;
}

.eyebrow {
  font-family: 'Montserrat Alternates', sans-serif;
  text-transform: uppercase;
  letter-spacing: 0.12rem;
  font-size: 0.75rem;
  color: #302a86;
  margin-bottom: 6px;
}

.controls {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
  margin-bottom: 22px;
}

.search {
  padding: 12px 16px;
  border-radius: 16px;
  border: 1px solid #ddd;
  min-width: 220px;
  font-family: 'Maven Pro', sans-serif;
}

.table-box {
  background: white;
  border-radius: 22px;
  overflow: hidden;
  box-shadow: 0 6px 24px rgba(0,0,0,0.06);
}

.students-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.92rem;
}

.students-table th {
  text-align: left;
  padding: 16px;
  background: #f8f8f8;
  border-bottom: 2px solid #eee;
  font-family: 'Montserrat Alternates', sans-serif;
}

.students-table td {
  padding: 16px;
  border-bottom: 1px solid #eee;
  vertical-align: top;
  font-family: 'Maven Pro', sans-serif;
}

.students-table tbody tr:hover {
  background: #fafafa;
}

.student-cell {
  display: flex;
  align-items: center;
  gap: 12px;
}

.avatar {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: #140f50;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
}

.entry-count {
  padding: 0.35rem 0.9rem;
  border-radius: 999px;
  font-size: 0.82rem;
  font-weight: 600;
  display: inline-block;
}

.low {
  background: #ffe2e2;
  color: #b42318;
}

.medium {
  background: #fff4d6;
  color: #b26b00;
}

.high {
  background: #dcfce7;
  color: #15803d;
}

.pill-tag {
  border: 0.09rem solid #d0d0d0;
  border-radius: 999px;
  padding: 0.25rem 1rem;
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.8rem;
  color: #444444;
  background: #ffffff;
}

.btn-filter {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  font-size: 0.95rem;
  background: #e6e6e6;
  color: #222222;
  border: none;
  padding: 0.5rem 1rem;
}

.btn-filter:hover {
  background: #666666;
  color: #ffffff;
}

.btn-add {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  font-size: 0.95rem;
  color: #ffffff;
  background: #555555;
  border: none;
  padding: 0.5rem 1rem;
}

.btn-add:hover {
  background: #333333;
  color: #ffffff;
}

.loading,
.empty,
.error {
  margin-top: 20px;
}

.error {
  color: #b91c1c;
  font-weight: 600;
}

.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.3);
  backdrop-filter: blur(0.375rem);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  padding: 20px;
}

.entries-modal-card {
  background: white;
  width: 1100px;
  max-width: 95%;
  max-height: 85vh;
  overflow-y: auto;
  padding: 25px;
  border-radius: 22px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.25);
}

.entries-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 22px;
}

.entries-subtitle {
  color: #666;
  margin-top: 4px;
}

.truncate {
  max-width: 320px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.modal-card {
  background: white;
  width: 560px;
  max-width: 92%;
  max-height: 88vh;
  overflow-y: auto;
  padding: 28px;
  border-radius: 22px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.25);
}

.pill-row {
  display: flex;
  gap: 10px;
  margin: 16px 0;
  flex-wrap: wrap;
}

.date-txt {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.9rem;
  color: #888888;
}

.details-section {
  margin-top: 22px;
}

.section-label {
  font-family: 'Martel', sans-serif;
  font-size: 1rem;
  text-decoration: underline;
  color: #222222;
  margin-bottom: 0.5rem;
}

.body-txt {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.9rem;
  line-height: 1.75;
  color: #444444;
}

.modal-card textarea {
  width: 100%;
  min-height: 160px;
  margin-bottom: 12px;
  padding: 12px;
  border-radius: 16px;
  border: 1px solid #ddd;
  font-family: 'Maven Pro', sans-serif;
}

.previous-feedback {
  margin-bottom: 20px;
}

.previous-feedback h4 {
  margin-bottom: 12px;
}

.feedback-item {
  background: #f8f8fb;
  border: 1px solid #ececf3;
  border-radius: 14px;
  padding: 14px;
  margin-bottom: 12px;
}

.feedback-meta {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
  font-size: 0.85rem;
  color: #666;
}

.feedback-item p {
  margin: 0;
  line-height: 1.5;
}

.empty-feedback {
  color: #777;
  background: #f8f8fb;
  border: 1px solid #ececf3;
  border-radius: 12px;
  padding: 12px;
}

.feedback-error {
  color: #b91c1c;
  margin-bottom: 10px;
  font-size: 0.9rem;
}

.feedback-success {
  color: #15803d;
  margin-bottom: 10px;
  font-size: 0.9rem;
}

.btn-row {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
  margin-top: 20px;
}
</style>