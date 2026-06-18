<template>
  <div class="admin-page">
    <AdminNavbar />

    <main class="container-xl py-4 px-4 px-md-5 admin-main">
      <section class="page-header mb-4">
        <div>
          <h1 class="page-title mb-2">Admin Console</h1>
          <p class="page-subtitle mb-0">Manage users, monitor progress, and quickly identify students who need support.</p>
        </div>
      </section>

      <!-- Summary cards: totals mirror backend aggregation over the current result set (same request as the table). -->
      <section class="stats-grid mb-4">
        <article class="stat-card">
          <p class="stat-label">Students</p>
          <p class="stat-value">{{ totalStudents }}</p>
        </article>
        <article class="stat-card">
          <p class="stat-label">Staff</p>
          <p class="stat-value">{{ totalStaff }}</p>
        </article>
        <article class="stat-card">
          <p class="stat-label">Admins</p>
          <p class="stat-value">{{ totalAdmins }}</p>
        </article>
        <article class="stat-card">
          <p class="stat-label">Total Users</p>
          <p class="stat-value">{{ totalUsers }}</p>
        </article>
      </section>

      <!-- POST /admin/users — server hashes password; role_id 3 also creates a student_profiles row for View/Edit. -->
      <section class="panel-card mb-4">
        <h2 class="panel-title mb-3">Create User</h2>
        <form class="create-user-form" @submit.prevent="createUser">
          <div class="form-row">
            <div class="form-field">
              <label for="create-role">Role</label>
              <select id="create-role" v-model.number="newUser.role_id" class="filter-select" required>
                <option :value="1">Admin</option>
                <option :value="2">Staff</option>
                <option :value="3">Student</option>
              </select>
            </div>
            <div class="form-field form-field-id">
              <label for="create-username">ID</label>
              <input
                id="create-username"
                ref="usernameInput"
                v-model.trim="newUser.username"
                type="text"
                class="filter-input"
                placeholder="e.g. a123456"
                maxlength="9"
                required
                @input="clearUsernameValidation"
                @blur="checkUsernameAvailable"
              />
            </div>
            <div class="form-field">
              <label for="create-email">Email</label>
              <input
                id="create-email"
                ref="emailInput"
                v-model.trim="newUser.email"
                type="email"
                class="filter-input"
                placeholder="name@adelaide.edu.au"
                required
                @input="clearEmailValidation"
                @blur="checkEmailAvailable"
              />
            </div>
          </div>

          <div class="form-row">
            <div class="form-field">
              <label for="create-first-name">First name</label>
              <input
                id="create-first-name"
                v-model.trim="newUser.first_name"
                type="text"
                class="filter-input"
                placeholder="First name"
                required
              />
            </div>
            <div class="form-field">
              <label for="create-last-name">Last name</label>
              <input
                id="create-last-name"
                v-model.trim="newUser.last_name"
                type="text"
                class="filter-input"
                placeholder="Last name"
                required
              />
            </div>
            <div class="form-field">
              <label for="create-password">Password</label>
              <input
                id="create-password"
                v-model="newUser.password"
                type="password"
                class="filter-input"
                placeholder="Minimum 6 characters"
                minlength="6"
                required
              />
            </div>
          </div>

          <div v-if="newUser.role_id === 3" class="form-row form-row-student">
            <div class="form-field form-field-year">
              <label for="create-year">Start year</label>
              <select id="create-year" v-model.number="newUser.year_started" class="filter-select" required>
                <option disabled value="">Select start year</option>
                <option v-for="year in yearOptions" :key="year" :value="year">{{ year }}</option>
              </select>
            </div>
          </div>

          <div class="form-footer">
            <button type="submit" class="btn page-btn-primary create-user-btn" :disabled="creatingUser">
              {{ creatingUser ? 'Creating...' : 'Create User' }}
            </button>
          </div>
        </form>
      </section>

      <section class="panel-card mb-4">
        <div class="panel-head">
          <div class="panel-head-left">
            <h2 class="panel-title mb-0">User Management</h2>
            <div class="export-actions">
              <button
                type="button"
                class="btn btn-csv"
                :disabled="loading || users.length === 0"
                @click="exportUsersCsv"
              >
                Export CSV
              </button>
              <button
                type="button"
                class="btn btn-pdf"
                :disabled="loading || users.length === 0"
                @click="exportUsersPdf"
              >
                Export PDF
              </button>
            </div>
          </div>
          <div class="filters-wrap">
            <input
              v-model="searchQuery"
              type="text"
              class="filter-input"
              placeholder="Search by name, email, or ID"
            />
          </div>
        </div>

        <div class="table-scroll">
          <table class="admin-table">
            <colgroup>
              <col class="col-user" />
              <col class="col-role" />
              <col class="col-id" />
              <col class="col-goals" />
              <col class="col-completed" />
              <col class="col-date" />
              <col class="col-actions" />
            </colgroup>
            <thead>
              <tr>
                <th>User</th>
                <th>Role</th>
                <th>ID</th>
                <th>Goals</th>
                <th>Completed</th>
                <th>Last Updated</th>
                <th class="actions-col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="7" class="empty-state">Loading users...</td>
              </tr>
              <tr v-else-if="loadError">
                <td colspan="7" class="empty-state">{{ loadError }}</td>
              </tr>
              <tr v-for="user in filteredUsers" :key="user.user_id">
                <td>
                  <p class="user-name mb-0">{{ user.name }}</p>
                  <p class="user-email mb-0">{{ user.email }}</p>
                </td>
                <td>{{ user.role }}</td>
                <td>
                  {{ user.username || '-' }}
                </td>
                <td>{{ user.goals }}</td>
                <td>{{ user.completedGoals }}</td>
                <td>{{ user.updatedAt }}</td>
                <td class="actions-cell">
                  <div class="actions-stack">
                    <span class="action-tooltip" :title="!user.profile_id ? 'Only student users have profile pages.' : 'View profile'">
                      <button
                        type="button"
                        class="action-icon-btn"
                        aria-label="View profile"
                        :disabled="!user.profile_id"
                        @click="viewProfile(user)"
                      >
                        <img :src="profileIcon" alt="" class="action-icon-image" aria-hidden="true" />
                      </button>
                    </span>
                    <span class="action-tooltip" :title="!user.profile_id ? 'Only student users have profile pages.' : 'Career development plan'">
                      <button
                        type="button"
                        class="action-icon-btn"
                        aria-label="Career development plan"
                        :disabled="!user.profile_id"
                        @click="viewGoals(user)"
                      >
                        <img :src="goalsIcon" alt="" class="action-icon-image" aria-hidden="true" />
                      </button>
                    </span>
                    <button
                      type="button"
                      class="action-icon-btn"
                      aria-label="Delete user"
                      title="Delete"
                      :disabled="deletingUserId === user.user_id"
                      @click="showDeleteConfirm = true, userToDelete = user; "
                    >
                      <img :src="deleteIcon" alt="" class="action-icon-image" aria-hidden="true" />
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="!loading && !loadError && filteredUsers.length === 0">
                <td colspan="7" class="empty-state">No users match this filter.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- UI placeholders only — wire to routes or modals when those features exist.
      <section class="panel-card quick-actions">
        <h2 class="panel-title">Quick Actions</h2>
        <div class="quick-action-grid">
          <button type="button" class="btn page-btn-primary">Create Announcement</button>
          <button type="button" class="btn page-btn-outline">Export Summary</button>
          <button type="button" class="btn page-btn-outline">Send Reminder Emails</button>
        </div>
      </section>
      -->
    </main>
  </div>
  <div v-if="popUp.show" class="popUp-msg" :class="popUp.type">
    {{ popUp.message }}
  </div>

  <!--Delete confirm -->
  <div v-if="showDeleteConfirm" class="view-popup" @click.self="showDeleteConfirm = false">
    <div class="delete-box text-center p-4">
      <h5 class="fw-bold mb-2 field-label delete-title">Delete user {{ userToDelete?.username || userToDelete?.email }}? This cannot be undone.</h5>

      <div class="d-flex gap-2 justify-content-center">
        <button class="btn btn-filter" @click="showDeleteConfirm = false">Cancel</button>
        <button class="btn btn-add rounded-pill px-4" @click="deleteUser(userToDelete); showDeleteConfirm = false">Delete</button>
      </div>
    </div>
  </div>
</template>


<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'
import profileIcon from '@/assets/default.jpg'
import goalsIcon from '@/assets/edit.png'
import deleteIcon from '@/assets/delete.png'
import AdminNavbar from '@/components/AdminNavbar.vue'

const router = useRouter()
const searchQuery = ref('')
const users = ref([])
const loading = ref(false)
const loadError = ref('')
const creatingUser = ref(false)
const deletingUserId = ref(null)
const userToDelete = ref(null)
const showDeleteConfirm = ref(false)

const currentYear = new Date().getFullYear()

const yearOptions = computed(() => {
  const years = []
  for (let year = currentYear - 6; year <= currentYear + 2; year += 1) {
    years.push(year)
  }
  return years
})

const newUser = ref({
  // Role mapping in this project: 1 = Admin, 2 = Staff, 3 = Student.
  // Defaulting to Student
  role_id: 3,
  username: '',
  email: '',
  first_name: '',
  last_name: '',
  password: '',
  year_started: '',
})

// Populated from the same /admin/users-overview response as the table (totals are sums over returned rows).
const stats = ref({
  totalUsers: 0,
  totalStudents: 0,
  totalStaff: 0,
  totalAdmins: 0,
})

const filteredUsers = computed(() => {
  // Users with student profiles can be viewed/edited, so keep them at the top.
  return [...users.value].sort((a, b) => Number(Boolean(b.profile_id)) - Number(Boolean(a.profile_id)))
})

const totalUsers = computed(() => stats.value.totalUsers)
const totalStudents = computed(() => stats.value.totalStudents)
const totalStaff = computed(() => stats.value.totalStaff)
const totalAdmins = computed(() => stats.value.totalAdmins)

// Object to store data about the popup message
const popUp = ref({ show: false, message: '', type: '' })
// Time the popup can be viewed for. Currently set to 3 seconds allow time for the user to view the message
const popUpTime = 3000

// Used to display the popup message and the type being either success or error
const showPopUp = (message, type) => {
  popUp.value = { show: true, message, type }
  setTimeout(() => popUp.value.show = false, popUpTime)
}

const usernameInput = ref(null)
const emailInput = ref(null)

// Inline field validation via the browser constraint API (same bubble UI for ID and email).
const clearUsernameValidation = () => {
  usernameInput.value?.setCustomValidity('')
}

const showUsernameValidation = (message) => {
  if (usernameInput.value) {
    usernameInput.value.setCustomValidity(message)
    usernameInput.value.reportValidity()
  }
}

const clearEmailValidation = () => {
  emailInput.value?.setCustomValidity('')
}

const showEmailValidation = (message) => {
  if (emailInput.value) {
    emailInput.value.setCustomValidity(message)
    emailInput.value.reportValidity()
  }
}

const fetchUsersOverview = async () => {
  try {
    loading.value = true
    loadError.value = ''

    // Optional `q` matches name, email, or username (ID) on the server.
    const params = {}

    const query = searchQuery.value.trim()
    if (query) {
      params.q = query
    }

    // Fetch the filtered user list and summary numbers from the admin API.
    const response = await api.get('/admin/users-overview', { params })

    // Use empty defaults if the API omits either field.
    users.value = response.data.users || []
    stats.value = response.data.stats || {
      totalUsers: 0,
      totalStudents: 0,
      totalStaff: 0,
      totalAdmins: 0,
    }
  } catch (error) {
    // Reset displayed data on failure so stale results are not shown.
    console.error('Failed to load admin users overview:', error)
    users.value = []
    stats.value = {
      totalUsers: 0,
      totalStudents: 0,
      totalStaff: 0,
      totalAdmins: 0,
    }
    // Prefer the backend error message when available.
    loadError.value = error.response?.data?.message || 'Failed to load user management data'
  } finally {
    loading.value = false
  }
}

let searchDebounceTimer = null

// Debounce search input so we do not refetch on every keystroke.
watch(searchQuery, () => {
  if (searchDebounceTimer) {
    clearTimeout(searchDebounceTimer)
  }
  searchDebounceTimer = setTimeout(() => {
    fetchUsersOverview()
  }, 300)
})

onMounted(() => {
  // Initial load; subsequent loads are triggered by the debounced search watcher.
  fetchUsersOverview()
})

const resetCreateForm = () => {
  // Keep the same default role after each successful creation.
  newUser.value = {
    role_id: 3,
    username: '',
    email: '',
    first_name: '',
    last_name: '',
    password: '',
    year_started: '',
  }
  clearUsernameValidation()
  clearEmailValidation()
}

// Check ID uniqueness on blur and before submit; local list first, then server search.
const checkUsernameAvailable = async () => {
  const id = newUser.value.username.trim()
  if (!id) {
    clearUsernameValidation()
    return true
  }

  // Fast path: already loaded in the current overview table.
  if (users.value.some((user) => user.username === id)) {
    showUsernameValidation('This ID is already in use.')
    return false
  }

  try {
    // `q` matches username on the server; catches users outside the current table page/filter.
    const response = await api.get('/admin/users-overview', { params: { q: id } })
    const taken = (response.data.users || []).some((user) => user.username === id)
    if (taken) {
      showUsernameValidation('This ID is already in use.')
      return false
    }
    clearUsernameValidation()
    return true
  } catch {
    // Do not block submit if the availability check request fails.
    clearUsernameValidation()
    return true
  }
}

// Same availability pattern as ID; email comparison is case-insensitive.
const checkEmailAvailable = async () => {
  const email = newUser.value.email.trim()
  if (!email) {
    clearEmailValidation()
    return true
  }

  if (users.value.some((user) => user.email.toLowerCase() === email.toLowerCase())) {
    showEmailValidation('This email is already in use.')
    return false
  }

  try {
    const response = await api.get('/admin/users-overview', { params: { q: email } })
    const taken = (response.data.users || []).some(
      (user) => user.email.toLowerCase() === email.toLowerCase()
    )
    if (taken) {
      showEmailValidation('This email is already in use.')
      return false
    }
    clearEmailValidation()
    return true
  } catch {
    clearEmailValidation()
    return true
  }
}

// Map Laravel validation errors to short messages for inline field feedback.
const getCreateUserErrorMessage = (error) => {
  const errors = error.response?.data?.errors
  if (errors?.username?.length) {
    return 'This ID is already in use.'
  }
  if (errors?.email?.length) {
    return 'This email is already in use.'
  }
  return error.response?.data?.message || 'Failed to create user.'
}

const createUser = async () => {
  if (newUser.value.role_id === 3 && !newUser.value.year_started) {
    showPopUp('Please select a start year for the student.', 'error')
    return
  }

  const idAvailable = await checkUsernameAvailable()
  if (!idAvailable) {
    return
  }

  const emailAvailable = await checkEmailAvailable()
  if (!emailAvailable) {
    return
  }

  try {
    creatingUser.value = true
    const payload = { ...newUser.value }
    if (payload.role_id !== 3) {
      delete payload.year_started
    }
    await api.post('/admin/users', payload)
    showPopUp("User created successfully.", "success")
    resetCreateForm()
    await fetchUsersOverview()
  } catch (error) {
    console.error('Failed to create user:', error)
    const message = getCreateUserErrorMessage(error)
    // Duplicate ID/email: show on the field; other errors use the top toast.
    if (message === 'This ID is already in use.') {
      showUsernameValidation(message)
      return
    }
    if (message === 'This email is already in use.') {
      showEmailValidation(message)
      return
    }
    showPopUp(message, "error")
  } finally {
    creatingUser.value = false
  }
}

const viewProfile = (user) => {
  if (!user.profile_id) {
    return
  }
  router.push(`/profile/${user.profile_id}`)
}

const viewGoals = (user) => {
  if (!user.profile_id) {
    return
  }
  router.push(`/student/career-development/${user.profile_id}`)
}

const escapeCsvCell = (value) => {
  const text = String(value ?? '')
  return `"${text.replace(/"/g, '""')}"`
}

const exportUsersCsv = () => {
  if (users.value.length === 0) {
    showPopUp('No users to export.', 'error')
    return
  }

  try {
    const lines = ['"----- User Management -----"']

    // Push user stats to the lines array
    lines.push(
      [
        escapeCsvCell('Total Users'),
        escapeCsvCell(stats.value.totalUsers),
        escapeCsvCell('Students'),
        escapeCsvCell(stats.value.totalStudents),
        escapeCsvCell('Staff'),
        escapeCsvCell(stats.value.totalStaff),
        escapeCsvCell('Admins'),
        escapeCsvCell(stats.value.totalAdmins)
      ].join(',')
    )
    lines.push('')

    // Filter into students, staff and admin
    const students = users.value.filter((user) => user.role === 'Student')
    const staff = users.value.filter((user) => user.role === 'Staff')
    const admins = users.value.filter((user) => user.role === 'Admin')

    // Add student stats and amount of possible competency indicators
    lines.push(`"Students: ${students.length}"`)
    lines.push(`"Student Details And Competency Level Count Out Of ${stats.value.totalIndicators ?? ''}"`)


    // Group the students by their year starting with an empty object
    const byYear = students.reduce((groups, user) => {
      // If a year does not exist then add to no year
      const year = user.year_started ?? 'No year'

      // If a year does not exist in the groups then add it
      if (!groups[year]) groups[year] = []

      // Add the user to the group and return the group
      groups[year].push(user)
      return groups
    }, {})

    // Sort the years from oldest to newest
    const sortedYears = Object.keys(byYear).sort((a, b) => {
      if (a === 'No year') return 1
      if (b === 'No year') return -1
      return Number(a) - Number(b)
    })

    // Go through each year and add the students for each year level
    sortedYears.forEach((year) => {
      // Add the year number and titles
      lines.push(`"${year}"`)
      lines.push(['Name', 'Email', 'ID', 'Not Started', 'Emerging', 'Developing', 'Proficient', 'Confident'].map(escapeCsvCell).join(','))
      
      // Add each student from that year into the file
      byYear[year].forEach((user) => {
        lines.push(
          [
            user.name,
            user.email,
            user.username || '-',
            user.notStarted,
            user.levels?.Emerging ?? 0,
            user.levels?.Developing ?? 0,
            user.levels?.Proficient ?? 0,
            user.levels?.Confident ?? 0
          ]
            .map(escapeCsvCell)
            .join(',')
        )
      })
      lines.push('')
    })

    // Add staff members section and all users who are staff
    lines.push(`"Staff members: ${staff.length}"`)
    lines.push(['Name', 'Email', 'ID'].map(escapeCsvCell).join(','))
    if (staff.length === 0) {
      lines.push(escapeCsvCell('No staff members found.'))
    } else {
      staff.forEach((user) => {
        lines.push([user.name, user.email, user.username || '-'].map(escapeCsvCell).join(','))
      })
    }
    lines.push('')

    // Add admin members sections and all members who are admins
    lines.push(`"Admins: ${admins.length}"`)
    lines.push(['Name', 'Email', 'ID'].map(escapeCsvCell).join(','))
    if (admins.length === 0) {
      lines.push(escapeCsvCell('No users in this group.'))
    } else {
      admins.forEach((user) => {
        lines.push([user.name, user.email, user.username || '-'].map(escapeCsvCell).join(','))
      })
    }

    // Create and download the CSV file
    const blob = new Blob(['\ufeff', lines.join('\n')], { type: 'text/csv;charset=utf-8;' })
    const link = document.createElement('a')
    const url = URL.createObjectURL(blob)
    link.setAttribute('href', url)
    link.setAttribute('download', 'user_management_export.csv')
    link.style.visibility = 'hidden'
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    URL.revokeObjectURL(url)

    showPopUp('Downloading exported data', 'success')

  } catch {
    showPopUp('Error downloading data', 'error')
  }

}

const exportUsersPdf = async () => {
  if (users.value.length === 0) {
    showPopUp('No users to export.', 'error')
    return
  }

  try {
    const params = {}
    const query = searchQuery.value.trim()
    if (query) {
      params.q = query
    }

    const response = await api.get('/admin/users-overview/export-pdf', {
      params,
      responseType: 'blob',
      headers: { Accept: 'application/pdf' }
    })

    const blob = new Blob([response.data], { type: 'application/pdf' })
    const url = window.URL.createObjectURL(blob)
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', 'user_management_export.pdf')
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    window.URL.revokeObjectURL(url)

    showPopUp('PDF downloaded successfully', 'success')
  } catch (error) {
    console.error('Failed to export user management PDF:', error)
    showPopUp('Error generating the PDF.', 'error')
  }
}

const deleteUser = async (user) => {
  try {
    deletingUserId.value = user.user_id
    await api.delete(`/admin/users/${user.user_id}`)
    showPopUp("User deleted successfully.", "success")
    await fetchUsersOverview()
  } catch (error) {
    console.error('Failed to delete user:', error)
    showPopUp("Failed to delete user", "error")
  } finally {
    deletingUserId.value = null
  }
}
</script>



<style scoped>
.admin-page {
  min-height: 100vh;
  background: #ffffff;
  font-family: 'Maven Pro', sans-serif;
  color: #2b2b2b;
}

.admin-main {
  max-width: 1280px;
}

.page-title {
  font-family: 'Martel', serif;
  font-size: 2.4rem;
  color: #2b2b2b;
  line-height: 1.15;
}

.page-subtitle {
  font-size: 1.08rem;
  color: #656565;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 0.9rem;
}

.stat-card {
  border: 1px solid #e5e5e5;
  border-radius: 1rem;
  padding: 1rem 1.1rem;
  background: #fbfbfb;
  text-align: center;
}

.stat-label {
  margin: 0;
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.95rem;
  color: #707070;
}

.stat-value {
  margin: 0.35rem 0 0;
  font-family: 'Martel', serif;
  font-size: 2.25rem;
}

.panel-card {
  border: 1px solid #e3e3e3;
  border-radius: 1.2rem;
  background: #fafafa;
  padding: 1rem 1.1rem;
}

.create-user-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.create-user-form .form-row {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 0.85rem 1rem;
  align-items: end;
}

.create-user-form .form-row-student {
  grid-template-columns: minmax(0, 1fr);
  padding-top: 0.15rem;
}

.create-user-form .form-field {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  min-width: 0;
}

.create-user-form .form-field label {
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.78rem;
  font-weight: 600;
  letter-spacing: 0.03em;
  text-transform: uppercase;
  color: #666666;
}

.create-user-form .form-field-year {
  max-width: 14rem;
}

.create-user-form .filter-input,
.create-user-form .filter-select {
  width: 100%;
  min-width: 0;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.create-user-form .filter-input:focus,
.create-user-form .filter-select:focus {
  outline: none;
  border-color: #8a8a8a;
  box-shadow: 0 0 0 3px rgba(43, 43, 43, 0.08);
}

.create-user-form .form-footer {
  display: flex;
  justify-content: flex-end;
  padding-top: 0.5rem;
  margin-top: 0.15rem;
  border-top: 1px solid #ececec;
}

.create-user-btn {
  padding: 0.55rem 1.5rem;
  min-height: 2.5rem;
  line-height: 1.3;
  white-space: nowrap;
}

.create-user-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.panel-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 0.8rem;
  margin-bottom: 0.85rem;
}

.panel-head-left {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 0.65rem;
}

.panel-title {
  margin: 0;
  font-family: 'Martel', serif;
  font-size: 1.35rem;
  color: #2b2b2b;
}

.filters-wrap {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 0.55rem;
}

.export-actions {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 0.45rem;
}

.btn-csv {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  background: #bdbdbd;
  border: none;
  color: #2b2b2b;
  padding: 0.45rem 1rem;
  white-space: nowrap;
}

.btn-csv:hover:not(:disabled) {
  background: #979797;
}

.btn-pdf {
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.9rem;
  color: #ffffff;
  background: #555555;
  border: none;
  padding: 0.45rem 1rem;
  white-space: nowrap;
}

.btn-pdf:hover:not(:disabled) {
  color: #ffffff;
  background: #222222;
}

.btn-csv:disabled,
.btn-pdf:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.filter-input,
.filter-select {
  min-height: 2.5rem;
  border: 1px solid #d1d1d1;
  border-radius: 0.55rem;
  background: #ffffff;
  padding: 0.5rem 0.75rem;
  font-family: 'Maven Pro', sans-serif;
}

.filter-input {
  min-width: 16rem;
}

.table-scroll {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 0.9rem;
  box-shadow: 0 0.4rem 1.2rem rgba(0, 0, 0, 0.06);
}

.admin-table {
  width: 100%;
  min-width: 920px;
  table-layout: fixed;
  border: 1px solid #dddddd;
  border-collapse: separate;
  border-spacing: 0;
  background: #ffffff;
}

.admin-table .col-user {
  width: 26%;
}

.admin-table .col-role {
  width: 9%;
}

.admin-table .col-id {
  width: 10%;
}

.admin-table .col-goals {
  width: 8%;
}

.admin-table .col-completed {
  width: 9%;
}

.admin-table .col-date {
  width: 14%;
}

.admin-table .col-actions {
  width: 9.5rem;
}

.admin-table th,
.admin-table td {
  padding: 0.82rem 0.75rem;
  border-bottom: 1px solid #e6e6e6;
  text-align: center;
  vertical-align: middle;
  font-size: 0.95rem;
}

.admin-table th {
  background: #f3f3f3;
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.95rem;
  color: #333333;
  font-weight: 500;
}

.admin-table tbody tr:nth-child(even) {
  background: #fcfcfc;
}

.user-name {
  font-weight: 600;
  text-align: center;
}

.user-email {
  color: #6c6c6c;
  font-size: 0.95rem;
  text-align: center;
}

.admin-table th:nth-child(6),
.admin-table td:nth-child(6) {
  white-space: nowrap;
}

.actions-col,
.actions-cell {
  white-space: nowrap;
}

.actions-cell {
  vertical-align: middle !important;
}

.actions-stack {
  display: flex;
  flex-direction: row;
  gap: 0.55rem;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
}

.action-icon-btn {
  width: 2rem;
  height: 2rem;
  border: none;
  background: transparent;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.action-icon-image {
  width: 2rem;
  height: 2rem;
  object-fit: contain;
}

.action-icon-btn:hover:not(:disabled) {
  transform: scale(1.1);
}

.action-icon-btn:focus-visible {
  outline: 2px solid #9db8e6;
  outline-offset: 2px;
  border-radius: 999px;
}

.action-icon-btn:active:not(:disabled) {
  transform: scale(1.05);
}

.action-icon-btn:disabled {
  cursor: not-allowed;
  opacity: 0.4;
}

.action-tooltip {
  display: inline-flex;
}

.btn {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.6rem;
  font-size: 0.9rem;
}

.page-btn-primary {
  background: #2b2b2b;
  color: #ffffff;
  border: 1px solid #2b2b2b;
}

.page-btn-primary:hover {
  background: #1a1a1a;
  color: #ffffff;
}

.empty-state {
  text-align: center;
  color: #707070;
  padding: 1rem;
}

.popUp-msg {
  position: fixed;
  top: 5rem;   
  left: 0;
  right: 0;
  margin-inline: auto;
  width: max-content;
  padding: 0.75rem 2rem;
  border-radius: 2rem; 
  font-family: 'Maven Pro', sans-serif;
  font-size: 1.15rem;
}

.popUp-msg.success {
  background: #5d5d5d;
  color: #fff;
}

.popUp-msg.error {
  background: #db7979;
  color: #fff;
}

.view-popup {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.3);
  backdrop-filter: blur(0.375rem);
  z-index: 4;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.25rem;
}

.delete-box {
  background: #ffffff;
  border-radius: 1.25rem;
  max-width: 22.5rem;
  width: 100%;
  box-shadow: 0 1.25rem 3.75rem rgba(0, 0, 0, 0.2);
}

.delete-box .btn-filter,
.delete-box .btn-add {
  padding: 0.5rem 1rem;
  font-size: 0.85rem;
}

.delete-box .btn-add {
  background: #555555;
  color: #ffffff;
}

.delete-box .btn-add:hover {
  background: #333333;
  color: #ffffff;
}

.delete-title {
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 1.1rem;
  color: #222222;
}


@media (max-width: 992px) {
  .page-title {
    font-size: 2rem;
  }

  .stats-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .panel-head {
    flex-direction: column;
    align-items: stretch;
  }

  .filters-wrap {
    flex-direction: column;
    align-items: stretch;
  }

  .create-user-form .form-row {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .create-user-form .form-field-year {
    max-width: none;
  }

  .filter-input {
    min-width: 0;
  }
}

@media (max-width: 768px) {
  .admin-main {
    padding-left: 0.9rem !important;
    padding-right: 0.9rem !important;
  }

  .page-title {
    font-size: 1.65rem;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }

  .create-user-form .form-row {
    grid-template-columns: 1fr;
  }
}
</style>
