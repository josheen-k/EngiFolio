<template>
  <div class="goals-page">
    <div class="top-bar">
      <button class="new-goal-btn" @click="newGoal">New Goal</button>
    </div>

    <div v-if="showNewGoalForm" class="new-goal-form">
      <h3>Create New Goal</h3>
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
        <button type="submit">Create Goal</button>
        <button type="button" @click="cancelNewGoal">Cancel</button>
      </form>
    </div>

    <div v-if="showEditGoalForm" class="edit-goal-form">
      <h3>Edit Goal</h3>
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
        <button type="submit">Update Goal</button>
        <button type="button" @click="cancelEditGoal">Cancel</button>
      </form>
    </div>

    <div class="filter-section">
      <h2>Date Range</h2>

      <label>
        From:
        <input type="date" v-model="fromDate" />
      </label>

      <label>
        To:
        <input type="date" v-model="toDate" />
      </label>

      <button class="filter-btn" @click="loadGoals">Filter</button>
    </div>

    <div v-if="loading">Loading goals...</div>
    <div v-else-if="goals.length === 0">No goals found.</div>

    <table v-else class="goals-table">
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
            <ul v-if="goal.actionSteps && goal.actionSteps.length">
              <li v-for="step in goal.actionSteps" :key="step.step_id">
                {{ step.step_description }}
              </li>
            </ul>
            <span v-else>No steps</span>

            <button class="edit-steps-btn" @click="editSteps(goal)">Edit Steps</button>
          </td>

          <td>{{ goal.timeline }}</td>
          <td>{{ goal.progress_notes }}</td>
          <td>{{ goal.learnings }}</td>
          <td>{{ goal.start_date }}</td>
          <td>{{ goal.end_date }}</td>
          <td>{{ goal.completion_date }}</td>
          <td>{{ goal.completion_notes }}</td>

          <td class="actions-cell">
            <button @click="editGoal(goal)">Edit</button>
            <button @click="deleteGoal(goal)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import axios from 'axios'

const goals = ref([])
const loading = ref(true)
const fromDate = ref('')
const toDate = ref('')
const showNewGoalForm = ref(false)
const showEditGoalForm = ref(false)
const editingGoal = ref(null)
const planId = ref(null)
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
  alert(`Edit steps for goal: ${goal.goal_description}`)
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
  padding: 24px;
}

.top-bar {
  margin-bottom: 20px;
}

.new-goal-btn,
.filter-btn,
.edit-steps-btn {
  background-color: #1677ff;
  color: white;
  border: none;
  padding: 8px 14px;
  border-radius: 4px;
  cursor: pointer;
}

.edit-steps-btn {
  background-color: #1f9d55;
  margin-top: 10px;
}

.filter-section {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 24px;
  flex-wrap: wrap;
}

.goals-table {
  width: 100%;
  border-collapse: collapse;
}

.goals-table th,
.goals-table td {
  border: 1px solid #ddd;
  padding: 12px;
  vertical-align: top;
  text-align: left;
}

.goals-table th {
  background-color: #f5f5f5;
}

.actions-cell {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.new-goal-form {
  margin-bottom: 24px;
  padding: 16px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background-color: #f9f9f9;
}

.new-goal-form h3 {
  margin-top: 0;
}

.new-goal-form form {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.new-goal-form label {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.new-goal-form input,
.new-goal-form textarea {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.new-goal-form textarea {
  resize: vertical;
  min-height: 60px;
}

.edit-goal-form {
  margin-bottom: 24px;
  padding: 16px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background-color: #f9f9f9;
}

.edit-goal-form h3 {
  margin-top: 0;
}

.edit-goal-form form {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.edit-goal-form label {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.edit-goal-form input,
.edit-goal-form textarea {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.edit-goal-form textarea {
  resize: vertical;
  min-height: 60px;
}
</style>