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
          <!-- Role IDs align with backend seed data: 1=Admin, 2=Staff, 3=Student. -->
          <select v-model.number="newUser.role_id" class="filter-select" required>
            <option :value="1">Admin</option>
            <option :value="2">Staff</option>
            <option :value="3">Student</option>
          </select>
          <input v-model.trim="newUser.username" type="text" class="filter-input" placeholder="ID (max 9 chars)" maxlength="9" required />
          <input v-model.trim="newUser.email" type="email" class="filter-input" placeholder="Email" required />
          <input v-model.trim="newUser.first_name" type="text" class="filter-input" placeholder="First name" required />
          <input v-model.trim="newUser.last_name" type="text" class="filter-input" placeholder="Last name" required />
          <input v-model="newUser.password" type="password" class="filter-input" placeholder="Password (min 6 chars)" minlength="6" required />
          <button type="submit" class="btn page-btn-primary" :disabled="creatingUser">
            {{ creatingUser ? 'Creating...' : 'Create User' }}
          </button>
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

const newUser = ref({
  // Role mapping in this project: 1 = Admin, 2 = Staff, 3 = Student.
  // Defaulting to Student
  role_id: 3,
  username: '',
  email: '',
  first_name: '',
  last_name: '',
  password: ''
})

// Populated from the same /admin/users-overview response as the table (totals are sums over returned rows).
const stats = ref({
  totalUsers: 0,
  totalStudents: 0,
  totalStaff: 0,
  totalAdmins: 0,
  totalGoals: 0,
  totalCompletedGoals: 0
})

const filteredUsers = computed(() => {
  // Users with student profiles can be viewed/edited, so keep them at the top.
  return [...users.value].sort((a, b) => Number(Boolean(b.profile_id)) - Number(Boolean(a.profile_id)))
})

const totalUsers = computed(() => stats.value.totalUsers)
const totalStudents = computed(() => stats.value.totalStudents)
const totalStaff = computed(() => stats.value.totalStaff)
const totalAdmins = computed(() => stats.value.totalAdmins)
const totalGoals = computed(() => stats.value.totalGoals)
const totalCompletedGoals = computed(() => stats.value.totalCompletedGoals)

// Object to store data about the popup message
const popUp = ref({ show: false, message: '', type: '' })
// Time the popup can be viewed for. Currently set to 3 seconds allow time for the user to view the message
const popUpTime = 3000

// Used to display the popup message and the type being either success or error
const showPopUp = (message, type) => {
  popUp.value = { show: true, message, type }
  setTimeout(() => popUp.value.show = false, popUpTime)
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
      totalGoals: 0,
      totalCompletedGoals: 0
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
      totalGoals: 0,
      totalCompletedGoals: 0
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
    password: ''
  }
}

const createUser = async () => {
  try {
    creatingUser.value = true
    // Backend expects role_id, names, email, and plaintext password for hashing server-side.
    await api.post('/admin/users', newUser.value)
    showPopUp("User created successfully.", "success")
    resetCreateForm()
    await fetchUsersOverview()
  } catch (error) {
    console.error('Failed to create user:', error)
    showPopUp("Failed to create user", "error")
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

  const lines = [
    '"----- User Management -----"',
    [
      'Name',
      'Email',
      'Role',
      'ID',
      'Goals',
      'Completed',
      'Last Updated'
    ]
      .map(escapeCsvCell)
      .join(',')
  ]

  users.value.forEach((user) => {
    lines.push(
      [
        user.name,
        user.email,
        user.role,
        user.username || '-',
        user.goals,
        user.completedGoals,
        user.updatedAt || ''
      ]
        .map(escapeCsvCell)
        .join(',')
    )
  })

  lines.push('')
  lines.push(
    [
      escapeCsvCell('Total Users'),
      escapeCsvCell(stats.value.totalUsers),
      escapeCsvCell('Total Goals'),
      escapeCsvCell(stats.value.totalGoals),
      escapeCsvCell('Completed Goals'),
      escapeCsvCell(stats.value.totalCompletedGoals)
    ].join(',')
  )

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
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 0.6rem;
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

.page-btn-outline {
  background: #ffffff;
  color: #2b2b2b;
  border: 1px solid #cfcfcf;
}

.page-btn-outline:hover {
  background: #f3f3f3;
}

.page-btn-danger {
  background: #b42318;
  color: #ffffff;
  border: 1px solid #b42318;
}

.page-btn-danger:hover {
  background: #912018;
  color: #ffffff;
}

.action-feedback {
  font-size: 0.92rem;
}

.success-text {
  color: #166534;
}

.error-text {
  color: #b42318;
}

.empty-state {
  text-align: center;
  color: #707070;
  padding: 1rem;
}

.quick-actions .panel-title {
  margin-bottom: 0.7rem;
}

.quick-action-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 0.6rem;
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

  .create-user-form {
    grid-template-columns: repeat(2, minmax(0, 1fr));
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

  .create-user-form {
    grid-template-columns: 1fr;
  }

  .quick-action-grid {
    grid-template-columns: 1fr;
  }
}
</style>
