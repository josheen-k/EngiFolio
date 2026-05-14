<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import Navbar from '@/components/Navbar.vue'
import api from '@/services/api'

const router = useRouter()
const searchQuery = ref('')
const users = ref([])
const loading = ref(false)
const loadError = ref('')
const creatingUser = ref(false)
const deletingUserId = ref(null)
const actionError = ref('')
const actionSuccess = ref('')

const newUser = ref({
  // Role mapping in this project: 1 = Admin, 2 = Staff, 3 = Student.
  // Defaulting to Student because that's the most common account type to create.
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
  totalGoals: 0,
  totalCompletedGoals: 0
})

const filteredUsers = computed(() => {
  // Users with student profiles can be viewed/edited, so keep them at the top.
  return [...users.value].sort((a, b) => Number(Boolean(b.profile_id)) - Number(Boolean(a.profile_id)))
})

const totalUsers = computed(() => stats.value.totalUsers)
const totalGoals = computed(() => stats.value.totalGoals)
const totalCompletedGoals = computed(() => stats.value.totalCompletedGoals)

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

    const response = await api.get('/admin/users-overview', { params })
    users.value = response.data.users || []
    stats.value = response.data.stats || {
      totalUsers: 0,
      totalGoals: 0,
      totalCompletedGoals: 0
    }
  } catch (error) {
    console.error('Failed to load admin users overview:', error)
    users.value = []
    stats.value = {
      totalUsers: 0,
      totalGoals: 0,
      totalCompletedGoals: 0
    }
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
    actionError.value = ''
    actionSuccess.value = ''
    // Backend expects role_id, names, email, and plaintext password for hashing server-side.
    await api.post('/admin/users', newUser.value)
    actionSuccess.value = 'User created successfully.'
    resetCreateForm()
    await fetchUsersOverview()
  } catch (error) {
    console.error('Failed to create user:', error)
    actionError.value = error.response?.data?.message || 'Failed to create user'
  } finally {
    creatingUser.value = false
  }
}

const viewUser = (user) => {
  if (!user.profile_id) {
    // Admin and staff users do not have student profile pages.
    return
  }
  router.push(`/goals/${user.profile_id}`)
}

const editUser = (user) => {
  if (!user.profile_id) {
    // Admin and staff users do not have student profile pages.
    return
  }
  router.push(`/settings/profile/${user.profile_id}`)
}

const deleteUser = async (user) => {
  // Simple confirmation guard for a destructive operation.
  const confirmed = window.confirm(`Delete user ${user.username || user.email}? This cannot be undone.`)
  if (!confirmed) {
    return
  }

  try {
    deletingUserId.value = user.user_id
    actionError.value = ''
    actionSuccess.value = ''
    await api.delete(`/admin/users/${user.user_id}`)
    actionSuccess.value = 'User deleted successfully.'
    await fetchUsersOverview()
  } catch (error) {
    console.error('Failed to delete user:', error)
    actionError.value = error.response?.data?.message || 'Failed to delete user'
  } finally {
    deletingUserId.value = null
  }
}
</script>

<template>
  <div class="admin-page">
    <Navbar />

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
          <p class="stat-label">Total Users</p>
          <p class="stat-value">{{ totalUsers }}</p>
        </article>
        <article class="stat-card">
          <p class="stat-label">Total Goals Logged</p>
          <p class="stat-value">{{ totalGoals }}</p>
        </article>
        <article class="stat-card">
          <p class="stat-label">Completed Goals</p>
          <p class="stat-value">{{ totalCompletedGoals }}</p>
        </article>
        <article class="stat-card">
          <p class="stat-label">Open Goals</p>
          <!-- Derived on the client; backend exposes completed count via goal_status_id = 3. -->
          <p class="stat-value">{{ Math.max(0, totalGoals - totalCompletedGoals) }}</p>
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
          <input v-model.trim="newUser.first_name" type="text" class="filter-input" placeholder="First name (optional)" />
          <input v-model.trim="newUser.last_name" type="text" class="filter-input" placeholder="Last name" required />
          <input v-model="newUser.password" type="password" class="filter-input" placeholder="Password (min 6 chars)" minlength="6" required />
          <button type="submit" class="btn page-btn-primary" :disabled="creatingUser">
            {{ creatingUser ? 'Creating...' : 'Create User' }}
          </button>
        </form>
        <p v-if="actionSuccess" class="action-feedback success-text mb-0 mt-2">{{ actionSuccess }}</p>
        <p v-if="actionError" class="action-feedback error-text mb-0 mt-2">{{ actionError }}</p>
      </section>

      <!-- `user.id` is a display key from the API (e.g. STU-0001); goal columns count SMART goals for linked student profiles only. -->
      <section class="panel-card mb-4">
        <div class="panel-head">
          <h2 class="panel-title mb-0">User Management</h2>
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
            <thead>
              <tr>
                <th>User</th>
                <th>Role</th>
                <th>ID</th>
                <th>Goals</th>
                <th>Completed</th>
                <th>Last Updated</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="7" class="empty-state">Loading users...</td>
              </tr>
              <tr v-else-if="loadError">
                <td colspan="7" class="empty-state">{{ loadError }}</td>
              </tr>
              <tr v-for="user in filteredUsers" :key="user.id">
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
                <td>
                  <div class="action-buttons">
                    <span class="action-tooltip" :title="!user.profile_id ? 'Only student users have profile pages.' : ''">
                      <button
                        type="button"
                        class="btn page-btn-outline"
                        :disabled="!user.profile_id"
                        @click="viewUser(user)"
                      >
                        View
                      </button>
                    </span>
                    <span class="action-tooltip" :title="!user.profile_id ? 'Only student users have profile pages.' : ''">
                      <button
                        type="button"
                        class="btn page-btn-primary"
                        :disabled="!user.profile_id"
                        @click="editUser(user)"
                      >
                        Edit
                      </button>
                    </span>
                    <button type="button" class="btn page-btn-danger" :disabled="deletingUserId === user.user_id" @click="deleteUser(user)">
                      {{ deletingUserId === user.user_id ? 'Deleting...' : 'Delete' }}
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

      <!-- UI placeholders only — wire to routes or modals when those features exist. -->
      <section class="panel-card quick-actions">
        <h2 class="panel-title">Quick Actions</h2>
        <div class="quick-action-grid">
          <button type="button" class="btn page-btn-primary">Create Announcement</button>
          <button type="button" class="btn page-btn-outline">Export Summary</button>
          <button type="button" class="btn page-btn-outline">Send Reminder Emails</button>
        </div>
      </section>
    </main>
  </div>
</template>

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
}

.stat-label {
  margin: 0;
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.82rem;
  color: #707070;
}

.stat-value {
  margin: 0.35rem 0 0;
  font-family: 'Martel', serif;
  font-size: 1.9rem;
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

.panel-title {
  margin: 0;
  font-family: 'Martel', serif;
  font-size: 1.35rem;
  color: #2b2b2b;
}

.filters-wrap {
  display: flex;
  align-items: center;
  gap: 0.55rem;
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
  min-width: 860px;
  border: 1px solid #dddddd;
  border-collapse: separate;
  border-spacing: 0;
  background: #ffffff;
}

.admin-table th,
.admin-table td {
  padding: 0.82rem 0.75rem;
  border-bottom: 1px solid #e6e6e6;
  vertical-align: middle;
}

.admin-table th {
  background: #f3f3f3;
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.86rem;
  color: #333333;
  font-weight: 500;
}

.admin-table tbody tr:nth-child(even) {
  background: #fcfcfc;
}

.user-name {
  font-weight: 600;
}

.user-email {
  color: #6c6c6c;
  font-size: 0.92rem;
}

.action-buttons {
  display: flex;
  gap: 0.45rem;
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
