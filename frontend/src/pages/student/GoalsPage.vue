<template>
  <div class="goals-page">
    <div class="top-bar">
      <button class="new-goal-btn">New Goal</button>
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

            <button class="edit-steps-btn">Edit Steps</button>
          </td>

          <td>{{ goal.timeline }}</td>
          <td>{{ goal.progress_notes }}</td>
          <td>{{ goal.learnings }}</td>
          <td>{{ goal.start_date }}</td>
          <td>{{ goal.end_date }}</td>
          <td>{{ goal.completion_date }}</td>
          <td>{{ goal.completion_notes }}</td>

          <td class="actions-cell">
            <button>Edit</button>
            <button>Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const goals = ref([])
const loading = ref(true)
const fromDate = ref('')
const toDate = ref('')

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
    const response = await axios.get('http://127.0.0.1:9000/api/smart-goals', {
      params
    })

    goals.value = response.data
  } catch (error) {
    console.error('Error while fetching goals:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadGoals()
})
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
</style>