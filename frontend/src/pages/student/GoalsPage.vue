<template>
  <div class="goals-page">
    <Navbar />
    <main class="container-xl py-4 px-4 px-md-5">
      <section class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
          <h1 class="page-title mb-2">SMART Goals</h1>
          <p class="page-subtitle mb-0">Track your progress and keep your career plan on course.</p>
        </div>
        <button class="btn page-btn-primary px-4 py-2" @click="newGoal">New Goal</button>
      </section>

    <div v-if="showNewGoalForm" class="goal-form-card mb-4">
      <h3 class="form-title">Create New Goal</h3>
      <form @submit.prevent="createGoal">
        <label>
          Goal Description:
          <textarea v-model="newGoalData.goal_description" required></textarea>
        </label>
        <label>
          Timeline:
          <input type="text" v-model="newGoalData.timeline" />
        </label>
        <label>
          Progress Notes:
          <textarea v-model="newGoalData.progress_notes"></textarea>
        </label>
        <label>
          Learnings:
          <textarea v-model="newGoalData.learnings"></textarea>
        </label>
        <label>
          Start Date:
          <input type="date" v-model="newGoalData.start_date" />
        </label>
        <label>
          End Date:
          <input type="date" v-model="newGoalData.end_date" />
        </label>
        <label>
          Completion Notes:
          <textarea v-model="newGoalData.completion_notes"></textarea>
        </label>
        <div class="d-flex gap-2 pt-1">
          <button type="submit" class="btn page-btn-primary">Create Goal</button>
          <button type="button" class="btn page-btn-outline" @click="cancelNewGoal">Cancel</button>
        </div>
      </form>
    </div>

    <div v-if="showEditGoalForm" class="goal-form-card mb-4">
      <h3 class="form-title">Edit Goal</h3>
      <form @submit.prevent="updateGoal">
        <label>
          Goal Description:
          <textarea v-model="editGoalData.goal_description" required></textarea>
        </label>
        <label>
          Timeline:
          <input type="text" v-model="editGoalData.timeline" />
        </label>
        <label>
          Progress Notes:
          <textarea v-model="editGoalData.progress_notes"></textarea>
        </label>
        <label>
          Learnings:
          <textarea v-model="editGoalData.learnings"></textarea>
        </label>
        <label>
          Start Date:
          <input type="date" v-model="editGoalData.start_date" />
        </label>
        <label>
          End Date:
          <input type="date" v-model="editGoalData.end_date" />
        </label>
        <label>
          Completion Notes:
          <textarea v-model="editGoalData.completion_notes"></textarea>
        </label>
        <div class="d-flex gap-2 pt-1">
          <button type="submit" class="btn page-btn-primary">Update Goal</button>
          <button type="button" class="btn page-btn-outline" @click="cancelEditGoal">Cancel</button>
        </div>
      </form>
    </div>

    <div class="filter-section mb-4">
      <h2 class="filter-title mb-0">Date Range</h2>

      <label>
        From:
        <input type="date" v-model="fromDate" />
      </label>

      <label>
        To:
        <input type="date" v-model="toDate" />
      </label>

      <button class="btn page-btn-outline" @click="loadGoals">Filter</button>
    </div>

    <div v-if="loading" class="status-msg">Loading goals...</div>
    <div v-else-if="goals.length === 0" class="status-msg">No goals found.</div>

    <table v-else class="table goals-table">
      <thead>
        <tr>
          <th>SMART Goal</th>
          <th>Action Steps</th>
          <th>Timeline</th>
          <th>Progress</th>
          <th>Learnings</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Completed</th>
          <th>Completion Notes</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="goal in goals" :key="goal.goal_id">
          <td>{{ goal.goal_description }}</td>

          <td>
            <ul v-if="getGoalSteps(goal).length">
              <li v-for="step in getGoalSteps(goal)" :key="step.step_id">
                {{ step.step_description }}
              </li>
            </ul>
            <span v-else>No steps</span>

            <button class="btn page-btn-success mt-2" @click="editSteps(goal)">Edit Steps</button>
          </td>

          <td>{{ goal.timeline }}</td>
          <td>{{ goal.progress_notes }}</td>
          <td>{{ goal.learnings }}</td>
          <td>{{ goal.start_date }}</td>
          <td>{{ goal.end_date }}</td>
          <td>{{ goal.completion_date }}</td>
          <td>{{ goal.completion_notes }}</td>

          <td class="actions-cell">
            <button class="btn page-btn-outline" @click="editGoal(goal)">Edit</button>
            <button class="btn page-btn-danger" @click="deleteGoal(goal)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="showStepModal" class="modal-backdrop" @click.self="closeStepModal">
      <div class="step-modal-card">
        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
          <div>
            <h3 class="form-title mb-1">Edit Action Steps</h3>
            <p class="modal-subtitle mb-0">{{ stepModalGoalTitle }}</p>
          </div>
          <button type="button" class="btn page-btn-outline" @click="closeStepModal">Close</button>
        </div>

        <div class="steps-editor">
          <div v-for="(step, index) in stepDrafts" :key="step.localKey" class="step-row">
            <label class="step-label">
              <span>Step {{ index + 1 }}</span>
              <textarea
                v-model="step.step_description"
                class="step-input"
                placeholder="Describe this action step"
              ></textarea>
            </label>
            <button type="button" class="btn page-btn-danger align-self-start" @click="removeStep(index)">
              Remove
            </button>
          </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-3">
          <button type="button" class="btn page-btn-success" @click="addStep">Add Step</button>
          <button type="button" class="btn page-btn-primary" :disabled="savingSteps" @click="saveSteps">
            {{ savingSteps ? 'Saving...' : 'Save Steps' }}
          </button>
        </div>
      </div>
    </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive, computed } from 'vue'
import axios from 'axios'
import Navbar from '@/components/Navbar.vue'

const goals = ref([])
const loading = ref(true)
const fromDate = ref('')
const toDate = ref('')
const showNewGoalForm = ref(false)
const showEditGoalForm = ref(false)
const showStepModal = ref(false)
const editingGoal = ref(null)
const planId = ref(null)
const stepModalGoal = ref(null)
const savingSteps = ref(false)
const stepDrafts = ref([])
const newGoalData = reactive({
  plan_id: null,
  goal_description: '',
  timeline: '',
  progress_notes: '',
  learnings: '',
  start_date: '',
  end_date: '',
  completion_date: '',
  completion_notes: '',
  status: 'planned'
})
const editGoalData = reactive({
  plan_id: null,
  goal_description: '',
  timeline: '',
  progress_notes: '',
  learnings: '',
  start_date: '',
  end_date: '',
  completion_date: '',
  completion_notes: '',
  status: 'planned'
})

const getGoalSteps = (goal) => goal.action_steps || goal.actionSteps || []

const loadGoals = async () => {
  try {
    loading.value = true

    const params = {}

    if (fromDate.value) {
      params.from = fromDate.value
    }

    if (toDate.value) {
      params.to = toDate.value
    }
    // For testing, I cannot use port 8000 for some reason, so I am using 9000 instead. Please change it back to 8000 when you test it.
    const response = await axios.get('http://127.0.0.1:8000/api/smart-goals', {
      params
    })

    goals.value = response.data
  } catch (error) {
    console.error('Error while fetching goals:', error)
  } finally {
    loading.value = false
  }
}

const loadPlanId = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/career-plans')
    if (response.data && response.data.length > 0) {
      planId.value = response.data[0].plan_id
      newGoalData.plan_id = response.data[0].plan_id
    } else {
      console.warn('No career development plan found')
      alert('Please create a Career Development Plan first')
    }
  } catch (error) {
    console.error('Error loading plan ID:', error)
    alert('Failed to load Career Development Plan')
  }
}

onMounted(() => {
  loadPlanId()
  loadGoals()
})

const newGoal = () => {
  showNewGoalForm.value = true
}

const createGoal = async () => {
  if (!newGoalData.plan_id) {
    alert('Please create a Career Development Plan first')
    return
  }

  try {
    const payload = normalizeGoalPayload(newGoalData)
    await axios.post('http://127.0.0.1:8000/api/smart-goals', payload)
    showNewGoalForm.value = false
    // Reset form
    Object.assign(newGoalData, {
      plan_id: planId.value,
      goal_description: '',
      timeline: '',
      progress_notes: '',
      learnings: '',
      start_date: '',
      end_date: '',
      completion_date: '',
      completion_notes: '',
      status: 'planned'
    })
    loadGoals() // Refresh the list
    alert('Goal created successfully!')
  } catch (error) {
    console.error('Error creating goal:', error)
    const errorMessage = error.response?.data?.message || 
                        Object.values(error.response?.data?.errors || {}).flat()[0] ||
                        'Failed to create goal'
    alert(`Failed to create goal: ${errorMessage}`)
  }
}

const normalizeGoalPayload = (goal) => {
  return {
    ...goal,
    timeline: goal.timeline || null,
    progress_notes: goal.progress_notes || null,
    learnings: goal.learnings || null,
    start_date: goal.start_date || null,
    end_date: goal.end_date || null,
    completion_date: goal.completion_date || null,
    completion_notes: goal.completion_notes || null,
  }
}

const cancelNewGoal = () => {
  showNewGoalForm.value = false
  // Reset form
  Object.assign(newGoalData, {
    plan_id: planId.value,
    goal_description: '',
    timeline: '',
    progress_notes: '',
    learnings: '',
    start_date: '',
    end_date: '',
    completion_date: '',
    completion_notes: '',
    status: 'planned'
  })
}

const cancelEditGoal = () => {
  showEditGoalForm.value = false
  editingGoal.value = null
  // Reset form
  Object.assign(editGoalData, {
    plan_id: null,
    goal_description: '',
    timeline: '',
    progress_notes: '',
    learnings: '',
    start_date: '',
    end_date: '',
    completion_date: '',
    completion_notes: '',
    status: 'planned'
  })
}

const editSteps = (goal) => {
  stepModalGoal.value = goal
  // Laravel serializes relations as snake_case in JSON responses.
  const sortedSteps = [...getGoalSteps(goal)].sort((a, b) => (a.step_order ?? 0) - (b.step_order ?? 0))
  stepDrafts.value = sortedSteps.map((step, index) => ({
    step_id: step.step_id,
    step_description: step.step_description || '',
    step_order: step.step_order ?? index + 1,
    localKey: `existing-${step.step_id}`
  }))

  if (stepDrafts.value.length === 0) {
    addStep()
  }

  showStepModal.value = true
}

const stepModalGoalTitle = computed(() => stepModalGoal.value?.goal_description || '')

const addStep = () => {
  stepDrafts.value.push({
    step_id: null,
    step_description: '',
    step_order: stepDrafts.value.length + 1,
    localKey: `new-${Date.now()}-${Math.random()}`
  })
}

const removeStep = (index) => {
  stepDrafts.value.splice(index, 1)
  stepDrafts.value.forEach((step, orderIndex) => {
    step.step_order = orderIndex + 1
  })
}

const closeStepModal = () => {
  if (savingSteps.value) {
    return
  }

  showStepModal.value = false
  stepModalGoal.value = null
  stepDrafts.value = []
}

const saveSteps = async () => {
  if (!stepModalGoal.value) {
    return
  }

  const normalizedSteps = stepDrafts.value
    .map((step) => step.step_description.trim())
    .filter((stepDescription) => stepDescription)

  try {
    savingSteps.value = true

    await axios.put(`http://127.0.0.1:8000/api/smart-goals/${stepModalGoal.value.goal_id}/action-steps`, {
      steps: normalizedSteps.map((step_description) => ({ step_description }))
    })

    await loadGoals()
    closeStepModal()
    alert('Action steps updated successfully!')
  } catch (error) {
    console.error('Error updating action steps:', error)
    const errorMessage = error.response?.data?.message ||
      Object.values(error.response?.data?.errors || {}).flat()[0] ||
      'Failed to update action steps'
    alert(`Failed to update action steps: ${errorMessage}`)
  } finally {
    savingSteps.value = false
  }
}

const editGoal = (goal) => {
  editingGoal.value = goal
  Object.assign(editGoalData, {
    plan_id: goal.plan_id,
    goal_description: goal.goal_description || '',
    timeline: goal.timeline || '',
    progress_notes: goal.progress_notes || '',
    learnings: goal.learnings || '',
    start_date: goal.start_date || '',
    end_date: goal.end_date || '',
    completion_date: goal.completion_date || '',
    completion_notes: goal.completion_notes || '',
    status: goal.status || 'planned'
  })
  showEditGoalForm.value = true
}

const updateGoal = async () => {
  try {
    const payload = normalizeGoalPayload(editGoalData)
    await axios.put(`http://127.0.0.1:8000/api/smart-goals/${editingGoal.value.goal_id}`, payload)
    showEditGoalForm.value = false
    editingGoal.value = null
    loadGoals() // Refresh the list
    alert('Goal updated successfully!')
  } catch (error) {
    console.error('Error updating goal:', error)
    const errorMessage = error.response?.data?.message || 
                        Object.values(error.response?.data?.errors || {}).flat()[0] ||
                        'Failed to update goal'
    alert(`Failed to update goal: ${errorMessage}`)
  }
}

const deleteGoal = async (goal) => {
  if (confirm(`Are you sure you want to delete this goal: ${goal.goal_description}?`)) {
    try {
      await axios.delete(`http://127.0.0.1:8000/api/smart-goals/${goal.goal_id}`)
      loadGoals() // Refresh the list
      alert('Goal deleted successfully!')
    } catch (error) {
      console.error('Error deleting goal:', error)
      const errorMessage = error.response?.data?.message || 'Failed to delete goal'
      alert(`Failed to delete goal: ${errorMessage}`)
    }
  }
}
</script>

<style scoped>
.goals-page {
  min-height: 100vh;
  background: #ffffff;
  font-family: 'Maven Pro', sans-serif;
  color: #2b2b2b;
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

.btn {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.6rem;
  font-size: 0.95rem;
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

.page-btn-success {
  background: #4f9d69;
  color: #ffffff;
  border: 1px solid #4f9d69;
}

.page-btn-success:hover {
  background: #3f8657;
  color: #ffffff;
}

.page-btn-danger {
  background: #ff746c;
  color: #ffffff;
  border: 1px solid #ff746c;
}

.page-btn-danger:hover {
  background: #e7635b;
  color: #ffffff;
}

.filter-section {
  display: flex;
  align-items: center;
  gap: 16px;
  flex-wrap: wrap;
  padding: 1.25rem;
  border: 1px solid #e5e5e5;
  border-radius: 1.2rem;
  background: #fafafa;
}

.filter-title {
  font-family: 'Martel', serif;
  font-size: 1.35rem;
  color: #2b2b2b;
}

.status-msg {
  padding: 1rem 1.25rem;
  border-radius: 0.9rem;
  background: #f5f5f5;
  color: #555555;
}

.goals-table {
  width: 100%;
  border: 1px solid #dedede;
}

.goals-table th,
.goals-table td {
  border-color: #e0e0e0;
  padding: 0.85rem;
  vertical-align: top;
  text-align: left;
}

.goals-table th {
  font-family: 'Martian Mono', monospace;
  font-size: 0.9rem;
  font-weight: 400;
  background-color: #f1f1f1;
  color: #333333;
}

.actions-cell {
  display: flex;
  flex-direction: column;
  gap: 8px;
  min-width: 7.5rem;
}

.goal-form-card {
  padding: 1.1rem 1.25rem;
  border: 1px solid #e3e3e3;
  border-radius: 1.2rem;
  background-color: #fafafa;
}

.form-title {
  font-family: 'Martel', serif;
  font-size: 1.35rem;
  color: #2b2b2b;
  margin-top: 0;
}

.goal-form-card form {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.goal-form-card label {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.goal-form-card input,
.goal-form-card textarea,
.filter-section input {
  padding: 0.55rem 0.75rem;
  border: 1px solid #d1d1d1;
  border-radius: 0.55rem;
  background: #ffffff;
}

.goal-form-card textarea {
  resize: vertical;
  min-height: 72px;
}

.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(20, 20, 20, 0.45);
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1.5rem;
  z-index: 1050;
}

.step-modal-card {
  width: min(100%, 48rem);
  max-height: 85vh;
  overflow-y: auto;
  background: #ffffff;
  border-radius: 1.4rem;
  padding: 1.4rem;
  box-shadow: 0 1rem 2.5rem rgba(0, 0, 0, 0.14);
}

.modal-subtitle {
  color: #6a6a6a;
}

.steps-editor {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.step-row {
  display: flex;
  gap: 0.75rem;
  align-items: flex-start;
  padding: 1rem;
  border: 1px solid #e4e4e4;
  border-radius: 1rem;
  background: #fafafa;
}

.step-label {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.45rem;
}

.step-input {
  width: 100%;
  min-height: 5.5rem;
  padding: 0.7rem 0.8rem;
  border: 1px solid #d1d1d1;
  border-radius: 0.75rem;
  resize: vertical;
  background: #ffffff;
}
</style>