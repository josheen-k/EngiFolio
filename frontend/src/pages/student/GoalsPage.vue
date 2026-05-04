<template>
  <div class="goals-page">
    <Navbar />
    <main class="container-xl py-4 px-4 px-md-5 goals-main">
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
          Progress Status:
          <select v-model="newGoalData.status">
            <option v-for="status in progressStatusOptions" :key="status.value" :value="status.value">{{ status.label }}</option>
          </select>
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
        <div class="d-flex gap-2 pt-1 form-actions">
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
          Progress Status:
          <select v-model="editGoalData.status">
            <option v-for="status in progressStatusOptions" :key="status.value" :value="status.value">{{ status.label }}</option>
          </select>
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
        <div class="d-flex gap-2 pt-1 form-actions">
          <button type="submit" class="btn page-btn-primary">Update Goal</button>
          <button type="button" class="btn page-btn-outline" @click="cancelEditGoal">Cancel</button>
        </div>
      </form>
    </div>

    <div class="filter-section mb-4">
      <div class="filter-title-wrap">
        <h2 class="filter-title mb-0">Date Range</h2>
        <p class="filter-hint mb-0">Filtering is based on Start Date.</p>
      </div>

      <label class="filter-field">
        <span class="filter-label">From</span>
        <input type="date" v-model="fromDate" />
      </label>

      <label class="filter-field">
        <span class="filter-label">To</span>
        <input type="date" v-model="toDate" />
      </label>

      <button class="btn page-btn-outline filter-action-btn" @click="loadGoals">Filter</button>
    </div>

    <div v-if="loading" class="status-msg">Loading goals...</div>
    <div v-else-if="goals.length === 0" class="status-msg">No goals found.</div>

    <div v-else>
      <!-- Desktop/tablet table view -->
      <div class="table-scroll desktop-goals-table">
        <table class="goals-table">
        <thead>
          <tr>
            <th class="drag-col-header"></th>
            <th>SMART Goal</th>
            <th>Action Steps</th>
            <th>Progress</th>
            <th>Learnings</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Completion Notes</th>
            <th>Actions</th>
          </tr>
          </thead>

          <tbody>
          <tr
            v-for="goal in goals"
            :key="goal.goal_id"
            :class="{
              'goal-row-dragging': draggedGoalId === goal.goal_id,
              'goal-row-drop-target': hoveredGoalId === goal.goal_id && draggedGoalId !== goal.goal_id
            }"
            @dragenter.prevent="onGoalDragEnter(goal)"
            @dragleave="onGoalDragLeave(goal)"
            @dragover.prevent
            @drop="onGoalDrop(goal)"
          >
            <td class="drag-handle-cell">
              <button
                type="button"
                class="drag-handle-btn"
                draggable="true"
                aria-label="Drag to reorder goal"
                @dragstart="onGoalDragStart($event, goal)"
                @dragend="onGoalDragEnd"
              >
                <span class="drag-handle-icon" aria-hidden="true">⋮⋮</span>
              </button>
            </td>
            <td>{{ goal.goal_description }}</td>

            <td class="steps-cell">
              <div class="steps-stack">
                <ul v-if="getGoalSteps(goal).length" class="steps-list">
                  <li v-for="step in getVisibleSteps(goal)" :key="step.step_id">
                    {{ step.step_description }}
                  </li>
                </ul>
                <p v-else class="no-steps-text">No steps</p>

                <button
                  v-if="getHiddenStepsCount(goal) > 0"
                  class="btn btn-link view-more-btn p-0"
                  @click="toggleSteps(goal.goal_id)"
                >
                  {{ isStepsExpanded(goal.goal_id) ? 'Show less' : `View more (${getHiddenStepsCount(goal)})` }}
                </button>

                <button
                  type="button"
                  class="action-icon-btn steps-edit-icon-btn"
                  aria-label="Edit action steps"
                  title="Edit Steps"
                  @click="editSteps(goal)"
                >
                  <img :src="editIcon" alt="" class="action-icon-image" aria-hidden="true" />
                </button>
              </div>
            </td>

            <td class="progress-cell">
              <select
                class="status-select"
                v-model="goal.status.status"
                @focus="goal._previousStatus = goal.status.status"
                @change="updateGoalStatus(goal)"
              >
                <option v-for="status in progressStatusOptions" :key="status.value" :value="status.value">{{ status.label }}</option>
              </select>
            </td>
            <td>{{ goal.learnings }}</td>
            <td>{{ goal.start_date }}</td>
            <td>{{ goal.end_date }}</td>
            <td class="completion-notes-cell">{{ goal.completion_notes || '-' }}</td>

            <td class="actions-cell">
              <div class="actions-stack">
                <button
                  type="button"
                  class="action-icon-btn"
                  aria-label="Edit goal"
                  title="Edit"
                  @click="editGoal(goal)"
                >
                  <img :src="editIcon" alt="" class="action-icon-image" aria-hidden="true" />
                </button>
                <button
                  type="button"
                  class="action-icon-btn"
                  aria-label="Delete goal"
                  title="Delete"
                  @click="deleteGoal(goal)"
                >
                  <img :src="deleteIcon" alt="" class="action-icon-image" aria-hidden="true" />
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="goals.length > 1" class="drop-to-end-row">
            <td
              class="drop-to-end-cell"
              :class="{ 'drop-to-end-active': isEndDropZoneActive }"
              :colspan="9"
              @dragenter.prevent="onEndDropDragEnter"
              @dragleave="onEndDropDragLeave"
              @dragover.prevent
              @drop="onGoalDropToEnd"
            >
              Drop here to move goal to bottom
            </td>
          </tr>
          </tbody>
        </table>
      </div>

      <!-- Mobile card view (enabled at <= 768px) -->
      <div class="mobile-goals-list">
        <article v-for="goal in goals" :key="`mobile-${goal.goal_id}`" class="mobile-goal-card">
          <div class="mobile-goal-head">
            <h3 class="mobile-goal-title">{{ goal.goal_description }}</h3>
            <span class="mobile-status-badge" :class="getStatusClass(goal.status.status)">
              {{ getStatusLabel(goal.status.status) }}
            </span>
          </div>

          <div class="mobile-section">
            <p class="mobile-label">Progress</p>
            <select
              class="status-select mobile-status-select"
              v-model="goal.status.status"
              @focus="goal._previousStatus = goal.status.status"
              @change="updateGoalStatus(goal)"
            >
              <option v-for="status in progressStatusOptions" :key="status.value" :value="status.value">{{ status.label }}</option>
            </select>
          </div>

          <div class="mobile-section">
            <p class="mobile-label">Action Steps</p>
            <ul v-if="getGoalSteps(goal).length" class="steps-list mobile-steps-list">
              <li v-for="step in getVisibleSteps(goal)" :key="`mobile-step-${step.step_id}`">{{ step.step_description }}</li>
            </ul>
            <p v-else class="no-steps-text">No steps</p>
            <button
              v-if="getHiddenStepsCount(goal) > 0"
              class="btn btn-link view-more-btn p-0"
              @click="toggleSteps(goal.goal_id)"
            >
              {{ isStepsExpanded(goal.goal_id) ? 'Show less' : `View more (${getHiddenStepsCount(goal)})` }}
            </button>
            <button
              type="button"
              class="action-icon-btn steps-edit-icon-btn mt-2"
              aria-label="Edit action steps"
              title="Edit Steps"
              @click="editSteps(goal)"
            >
              <img :src="editIcon" alt="" class="action-icon-image" aria-hidden="true" />
            </button>
          </div>

          <div class="mobile-grid">
            <div>
              <p class="mobile-label">Start Date</p>
              <p class="mobile-value">{{ goal.start_date || '-' }}</p>
            </div>
            <div>
              <p class="mobile-label">End Date</p>
              <p class="mobile-value">{{ goal.end_date || '-' }}</p>
            </div>
          </div>

          <div class="mobile-section">
            <p class="mobile-label">Learnings</p>
            <p class="mobile-value">{{ goal.learnings || '-' }}</p>
          </div>

          <div class="mobile-section">
            <p class="mobile-label">Completion Notes</p>
            <p class="mobile-value">{{ goal.completion_notes || '-' }}</p>
          </div>

          <div class="mobile-actions">
            <button class="btn page-btn-outline w-100" @click="editGoal(goal)">Edit</button>
            <button class="btn page-btn-danger w-100" @click="deleteGoal(goal)">Delete</button>
          </div>
        </article>
      </div>
    </div>

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
import Navbar from '@/components/Navbar.vue'
import api from "@/services/api";
import editIcon from '@/assets/edit.png'
import deleteIcon from '@/assets/delete.png'

// Page-level reactive state used by forms, filters, and modal dialogs.
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
const expandedStepsByGoal = ref({})
// Tracks the currently dragged goal to drive reorder and visual states.
const draggedGoalId = ref(null)
// Prevents concurrent reorder requests from overlapping.
const isReorderingGoals = ref(false)
// Highlights the row currently hovered as a potential drop target.
const hoveredGoalId = ref(null)
// Highlights the dedicated drop zone that moves a goal to the end.
const isEndDropZoneActive = ref(false)
// value is sent to backend, label is displayed in UI.
const progressStatusOptions = [
  { value: 'planned', label: 'Planned' },
  { value: 'in_progress', label: 'In Progress' },
  { value: 'completed', label: 'Completed' },
]
const newGoalData = reactive({
  plan_id: null,
  goal_description: '',
  progress_notes: '',
  learnings: '',
  start_date: '',
  end_date: '',
  completion_notes: '',
  status: 'planned'
})
const editGoalData = reactive({
  plan_id: null,
  goal_description: '',
  progress_notes: '',
  learnings: '',
  start_date: '',
  end_date: '',
  completion_notes: '',
  status: 'planned'
})

// Backend can return relationship keys in snake_case or camelCase depending on serializer/config.
const getGoalSteps = (goal) => goal.action_steps || goal.actionSteps || []
const getStatusLabel = (statusValue) => {
  const matched = progressStatusOptions.find((item) => item.value === statusValue)
  return matched ? matched.label : statusValue
}
const getStatusClass = (statusValue) => {
  if (statusValue === 'completed') return 'status-completed'
  if (statusValue === 'in_progress') return 'status-in-progress'
  if (statusValue === 'on_hold') return 'status-on-hold'
  return 'status-planned'
}
const isStepsExpanded = (goalId) => Boolean(expandedStepsByGoal.value[goalId])
// Show only the first 3 steps by default unless the goal is expanded.
const getVisibleSteps = (goal) => {
  const steps = getGoalSteps(goal)
  if (isStepsExpanded(goal.goal_id)) {
    return steps
  }
  return steps.slice(0, 3)
}
// Compute how many steps are hidden behind the "View more" button.
const getHiddenStepsCount = (goal) => {
  const hidden = getGoalSteps(goal).length - 3
  return hidden > 0 ? hidden : 0
}
// Toggle expanded/collapsed state for a specific goal's step list.
const toggleSteps = (goalId) => {
  expandedStepsByGoal.value = {
    ...expandedStepsByGoal.value,
    [goalId]: !isStepsExpanded(goalId)
  }
}

const loadGoals = async () => {
  try {
    loading.value = true

    // Build optional date-range query params from filter inputs.
    const params = {}

    if (fromDate.value) {
      params.from = fromDate.value
    }

    if (toDate.value) {
      params.to = toDate.value
    }
    const response = await api.get('/smart-goals', {
      params
    })

    // Respect persisted backend order, with created_at as a stable tiebreaker.
    goals.value = [...response.data].sort((a, b) => {
      const aOrder = a.goal_order ?? Number.MAX_SAFE_INTEGER
      const bOrder = b.goal_order ?? Number.MAX_SAFE_INTEGER
      if (aOrder !== bOrder) {
        return aOrder - bOrder
      }
      return new Date(b.created_at || 0) - new Date(a.created_at || 0)
    })
  } catch (error) {
    console.error('Error while fetching goals:', error)
  } finally {
    loading.value = false
  }
}

const onGoalDragStart = (event, goal) => {
  if (isReorderingGoals.value) {
    event.preventDefault()
    return
  }

  draggedGoalId.value = goal.goal_id
  hoveredGoalId.value = null
  isEndDropZoneActive.value = false
  event.dataTransfer.effectAllowed = 'move'
  event.dataTransfer.setData('text/plain', String(goal.goal_id))
}

const onGoalDragEnter = (goal) => {
  if (isReorderingGoals.value || draggedGoalId.value === null) {
    return
  }
  hoveredGoalId.value = goal.goal_id
  isEndDropZoneActive.value = false
}

const onGoalDragLeave = (goal) => {
  if (hoveredGoalId.value === goal.goal_id) {
    hoveredGoalId.value = null
  }
}

const onGoalDrop = async (targetGoal) => {
  if (isReorderingGoals.value || draggedGoalId.value === null || draggedGoalId.value === targetGoal.goal_id) {
    return
  }

  const previousGoals = [...goals.value]
  // Reorder in memory first for immediate feedback, then persist to backend.
  const reorderedGoals = moveGoalBefore(draggedGoalId.value, targetGoal.goal_id)
  draggedGoalId.value = null
  hoveredGoalId.value = null
  isEndDropZoneActive.value = false

  if (!reorderedGoals) {
    return
  }

  goals.value = reorderedGoals
  await persistGoalOrder(previousGoals)
}

const onGoalDropToEnd = async () => {
  if (isReorderingGoals.value || draggedGoalId.value === null) {
    return
  }

  const previousGoals = [...goals.value]
  // Dedicated drop zone enables moving a goal to the absolute bottom.
  const reorderedGoals = moveGoalToEnd(draggedGoalId.value)
  draggedGoalId.value = null
  hoveredGoalId.value = null
  isEndDropZoneActive.value = false

  if (!reorderedGoals) {
    return
  }

  goals.value = reorderedGoals
  await persistGoalOrder(previousGoals)
}

const onEndDropDragEnter = () => {
  if (isReorderingGoals.value || draggedGoalId.value === null) {
    return
  }
  isEndDropZoneActive.value = true
  hoveredGoalId.value = null
}

const onEndDropDragLeave = () => {
  isEndDropZoneActive.value = false
}

const onGoalDragEnd = () => {
  draggedGoalId.value = null
  hoveredGoalId.value = null
  isEndDropZoneActive.value = false
}

const moveGoalBefore = (sourceGoalId, targetGoalId) => {
  const sourceIndex = goals.value.findIndex((goal) => goal.goal_id === sourceGoalId)
  const targetIndex = goals.value.findIndex((goal) => goal.goal_id === targetGoalId)

  if (sourceIndex === -1 || targetIndex === -1) {
    return null
  }

  const nextGoals = [...goals.value]
  const [movedGoal] = nextGoals.splice(sourceIndex, 1)
  const insertIndex = sourceIndex < targetIndex ? targetIndex : targetIndex
  nextGoals.splice(insertIndex, 0, movedGoal)

  // Rebuild sequential order values expected by the reorder API.
  return nextGoals.map((goal, index) => ({
    ...goal,
    goal_order: index + 1
  }))
}

const moveGoalToEnd = (sourceGoalId) => {
  const sourceIndex = goals.value.findIndex((goal) => goal.goal_id === sourceGoalId)
  if (sourceIndex === -1) {
    return null
  }

  const nextGoals = [...goals.value]
  const [movedGoal] = nextGoals.splice(sourceIndex, 1)
  nextGoals.push(movedGoal)

  // Rebuild sequential order values expected by the reorder API.
  return nextGoals.map((goal, index) => ({
    ...goal,
    goal_order: index + 1
  }))
}

const persistGoalOrder = async (previousGoals) => {
  try {
    isReorderingGoals.value = true
    // Persist only IDs in their final order; backend maps index -> goal_order.
    await api.put('/smart-goals/reorder', {
      goal_ids: goals.value.map((goal) => goal.goal_id)
    })
  } catch (error) {
    // Roll back UI order if save fails so client/server stay consistent.
    goals.value = previousGoals
    console.error('Error reordering goals:', error)
    const errorMessage = error.response?.data?.message || 'Failed to reorder goals'
    alert(`Failed to reorder goals: ${errorMessage}`)
  } finally {
    isReorderingGoals.value = false
  }
}

const loadPlanId = async () => {
  try {
    const response = await api.get('/career-plans')
    if (response.data && response.data.length > 0) {
      // Use the first available career plan as the parent plan for new goals.
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

// Initialize required data as soon as the page is mounted.
onMounted(() => {
  loadPlanId()
  loadGoals()
})

const newGoal = () => {
  showNewGoalForm.value = true
}

// Create a new goal, then reset the form and refresh list state.
const createGoal = async () => {
  if (!newGoalData.plan_id) {
    alert('Please create a Career Development Plan first')
    return
  }

  try {
    // Normalize form values (e.g., empty optional fields) before sending to API.
    const payload = normalizeGoalPayload(newGoalData)
    await api.post('/smart-goals', payload)
    showNewGoalForm.value = false
    // Reset form
    Object.assign(newGoalData, {
      plan_id: planId.value,
      goal_description: '',
      progress_notes: '',
      learnings: '',
      start_date: '',
      end_date: '',
      completion_notes: '',
      goal_status_id: 1,
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
  // Convert optional empty form fields to null so backend validation/database handling stays consistent.
  return {
    ...goal,
    progress_notes: goal.progress_notes || null,
    learnings: goal.learnings || null,
    start_date: goal.start_date || null,
    end_date: goal.end_date || null,
    completion_notes: goal.completion_notes || null,
  }
}

const cancelNewGoal = () => {
  showNewGoalForm.value = false
  // Reset form
  Object.assign(newGoalData, {
    plan_id: planId.value,
    goal_description: '',
    progress_notes: '',
    learnings: '',
    start_date: '',
    end_date: '',
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
    progress_notes: '',
    learnings: '',
    start_date: '',
    end_date: '',
    completion_notes: '',
    status: 'planned'
  })
}

// Open step editor modal and clone/sort existing step data into local draft state.
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

// Keep step order sequential after deleting a draft row.
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

// Save all steps in one request so backend can safely replace the set atomically.
const saveSteps = async () => {
  if (!stepModalGoal.value) {
    return
  }

  const normalizedSteps = stepDrafts.value
    .map((step) => step.step_description.trim())
    .filter((stepDescription) => stepDescription)

  try {
    savingSteps.value = true

    await api.put(`/smart-goals/${stepModalGoal.value.goal_id}/action-steps`, {
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

// Optimistically update status from dropdown; revert on API failure.
const updateGoalStatus = async (goal) => {
  const previousStatus = goal._previousStatus ?? 'planned'
  try {
    await api.put(`/smart-goals/${goal.goal_id}`, {
      status: goal.status.status
    })

    // When a goal is marked completed, place it at the end of the list.
    if (goal.status === 'completed') {
      const previousGoals = [...goals.value]
      const reorderedGoals = moveGoalToEnd(goal.goal_id)
      if (reorderedGoals) {
        goals.value = reorderedGoals
        await persistGoalOrder(previousGoals)
      }
    }
  } catch (error) {
    goal.status.status = previousStatus
    console.error('Error updating goal status:', error)
    const errorMessage = error.response?.data?.message ||
      Object.values(error.response?.data?.errors || {}).flat()[0] ||
      'Failed to update goal status'
    alert(`Failed to update goal status: ${errorMessage}`)
  }
}

// Populate edit form with existing row data and open edit section.
const editGoal = (goal) => {
  editingGoal.value = goal
  Object.assign(editGoalData, {
    plan_id: goal.plan_id,
    goal_description: goal.goal_description || '',
    progress_notes: goal.progress_notes || '',
    learnings: goal.learnings || '',
    start_date: goal.start_date || '',
    end_date: goal.end_date || '',
    completion_notes: goal.completion_notes || '',
    goal_status_id: goal.status.status || 'planned'
  })
  showEditGoalForm.value = true
}

// Persist edited goal, then close editor and refresh table data.
const updateGoal = async () => {
  try {
    const payload = normalizeGoalPayload(editGoalData)
    await api.put(`/smart-goals/${editingGoal.value.goal_id}`, payload)
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

// Delete selected goal after user confirmation and refresh table.
const deleteGoal = async (goal) => {
  if (confirm(`Are you sure you want to delete this goal: ${goal.goal_description}?`)) {
    try {
      await api.delete(`/smart-goals/${goal.goal_id}`)
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
  width: 100%;
  max-width: 100%;
  overflow-x: hidden;
}

.goals-main {
  width: 100%;
  /* max-width: 100%; */
  max-width: 1280px; 
  overflow-x: hidden;
  box-sizing: border-box;
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
  display: grid;
  grid-template-columns: auto minmax(12rem, 14.5rem) minmax(12rem, 14.5rem) auto;
  align-items: end;
  gap: 0.85rem 1rem;
  flex-wrap: wrap;
  padding: 1.1rem 1.25rem;
  border: 1px solid #e5e5e5;
  border-radius: 1.2rem;
  background: #fbfbfb;
}

.filter-field {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  margin-bottom: 0;
}

.filter-label {
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.82rem;
  color: #707070;
  line-height: 1;
}

.filter-title {
  font-family: 'Martel', serif;
  font-size: 1.95rem;
  color: #2b2b2b;
  line-height: 1;
  margin-right: 0;
  align-self: flex-start;
}

.filter-title-wrap {
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
}

.filter-hint {
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.78rem;
  color: #6d6d6d;
  line-height: 1.3;
}

.status-msg {
  padding: 1rem 1.25rem;
  border-radius: 0.9rem;
  background: #f5f5f5;
  color: #555555;
}

.goals-table {
  /* Wide desktop table is intentionally scrollable in its wrapper to preserve readable column widths. */
  width: 100%;
  min-width: 1220px;
  table-layout: auto;
  border: 1px solid #dddddd;
  border-collapse: separate;
  border-spacing: 0;
  background: #ffffff;
}

.goals-table th,
.goals-table td {
  border-color: #e0e0e0;
  padding: 0.9rem 0.8rem;
  vertical-align: middle;
  text-align: left;
  overflow-wrap: anywhere;
  word-break: break-word;
  line-height: 1.4;
}

.goals-table th {
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.88rem;
  font-weight: 500;
  background-color: #f3f3f3;
  color: #333333;
  letter-spacing: 0.02em;
}

.goals-table tbody tr:nth-child(even) {
  background: #fcfcfc;
}

.goals-table tbody tr:hover {
  background: #f8f8f8;
}

.goal-row-dragging {
  opacity: 0.55;
}

.goals-table tbody tr.goal-row-drop-target td {
  background: #eef5ff;
}

.drop-to-end-row td {
  border-bottom: none;
}

.drop-to-end-cell {
  text-align: center;
  color: #7a7a7a;
  font-size: 0.86rem;
  font-family: 'Montserrat Alternates', sans-serif;
  padding: 0.6rem 0.8rem;
  border-top: 1px dashed #d8d8d8;
  background: #fafafa;
  transition: all 0.15s ease;
}

.drop-to-end-active {
  background: #eef5ff;
  border-top-color: #99bfff;
  color: #3c5d9e;
}

.goals-table tbody td {
  border-bottom: 1px solid #e6e6e6;
  background: transparent;
}

.goals-table tbody tr:last-child td {
  border-bottom: none;
}

.goals-table th:nth-child(1),
.goals-table td:nth-child(1) {
  /* Column 1: Drag Handle */
  min-width: 3rem;
  width: 3rem;
}

.goals-table th:nth-child(2),
.goals-table td:nth-child(2) {
  /* Column 2: SMART Goal */
  min-width: 15rem;
}

.goals-table th:nth-child(3),
.goals-table td:nth-child(3) {
  /* Column 3: Action Steps */
  min-width: 16rem;
}

.goals-table th:nth-child(4),
.goals-table td:nth-child(4),
.goals-table th:nth-child(5),
.goals-table td:nth-child(5),
.goals-table th:nth-child(8),
.goals-table td:nth-child(8) {
  /* Columns 4,5,8: Progress, Learnings, Completion Notes */
  min-width: 10rem;
}

.goals-table th:nth-child(6),
.goals-table td:nth-child(6) {
  /* Columns 6,7: Start Date, End Date */
  min-width: 8.5rem;
  white-space: nowrap;
}

.goals-table th:nth-child(7),
.goals-table td:nth-child(7) {
  min-width: 8.5rem;
  white-space: nowrap;
}

.goals-table th:last-child,
.goals-table td:last-child {
  min-width: 9rem;
}

.steps-list {
  margin: 0 0 0.4rem 0;
  padding-left: 1.1rem;
  max-height: 6.8rem;
  overflow-y: auto;
}

.drag-col-header {
  width: 3rem;
}

.drag-handle-cell {
  text-align: center !important;
  vertical-align: middle !important;
}

.drag-handle-btn {
  border: none;
  background: transparent;
  padding: 0.2rem 0.35rem;
  border-radius: 0.35rem;
  cursor: grab;
  color: #7a7a7a;
  line-height: 1;
}

.drag-handle-btn:hover {
  background: #f0f0f0;
  color: #5f5f5f;
}

.drag-handle-btn:active {
  cursor: grabbing;
}

.drag-handle-icon {
  font-size: 1.05rem;
  letter-spacing: -0.1rem;
}

.steps-cell {
  vertical-align: top !important;
}

.steps-stack {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 0.35rem;
}

.steps-edit-btn {
  margin-top: 0.15rem;
}

.steps-edit-icon-btn {
  margin-top: 0.15rem;
}

.view-more-btn {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.92rem;
  color: #3f7ccf;
  text-decoration: none;
}

.view-more-btn:hover {
  color: #245ea8;
  text-decoration: underline;
}

.no-steps-text {
  margin: 0;
}

.status-select {
  width: min(100%, 13rem);
  min-width: 9rem;
  padding: 0.45rem 0.55rem;
  border: 1px solid #d0d0d0;
  border-radius: 0.55rem;
  background: #ffffff;
  font-family: 'Maven Pro', sans-serif;
  display: block;
  margin: 0 auto;
}

.progress-cell {
  text-align: center !important;
  vertical-align: middle !important;
}

.completion-notes-cell {
  min-width: 10rem;
  vertical-align: middle !important;
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

.action-icon-btn:hover {
  transform: scale(1.1);
}

.action-icon-btn:focus-visible {
  outline: 2px solid #9db8e6;
  outline-offset: 2px;
  border-radius: 999px;
}

.action-icon-btn:active {
  transform: scale(1.05);
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
.goal-form-card select,
.filter-section input {
  padding: 0.58rem 0.75rem;
  border: 1px solid #d1d1d1;
  border-radius: 0.55rem;
  background: #ffffff;
  min-height: 2.65rem;
}

.filter-action-btn {
  min-height: 2.65rem;
  padding-left: 1.1rem;
  padding-right: 1.1rem;
  align-self: end;
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

.table-scroll {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 0.9rem;
  box-shadow: 0 0.4rem 1.2rem rgba(0, 0, 0, 0.06);
}

.desktop-goals-table {
  display: block;
}

/* Keep mobile cards hidden on larger screens. */
.mobile-goals-list {
  display: none;
}

.mobile-goal-card {
  border: 1px solid #e3e3e3;
  border-radius: 1rem;
  background: #ffffff;
  padding: 0.95rem;
  margin-bottom: 0.85rem;
}

.mobile-goal-head {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 0.75rem;
  margin-bottom: 0.7rem;
}

.mobile-goal-title {
  margin: 0;
  font-size: 1.1rem;
  line-height: 1.35;
  font-family: 'Martel', serif;
}

.mobile-status-badge {
  border-radius: 999px;
  padding: 0.2rem 0.65rem;
  font-size: 0.76rem;
  white-space: nowrap;
  border: 1px solid #d7d7d7;
}

.status-planned {
  background: #f5f5f5;
  color: #555555;
}

.status-in-progress {
  background: #e8f3ff;
  color: #1f5ea8;
}

.status-completed {
  background: #e8f7ed;
  color: #256942;
}

.status-on-hold {
  background: #fff3e2;
  color: #8d5b1e;
}

.mobile-section {
  margin-bottom: 0.75rem;
}

.mobile-label {
  margin: 0 0 0.22rem 0;
  font-size: 0.77rem;
  color: #747474;
  font-family: 'Montserrat Alternates', sans-serif;
}

.mobile-value {
  margin: 0;
  color: #2b2b2b;
}

.mobile-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.6rem;
  margin-bottom: 0.75rem;
}

.mobile-steps-list {
  max-height: 5.5rem;
}

.mobile-actions {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.55rem;
  margin-top: 0.6rem;
}

@media (max-width: 992px) {
  .page-title {
    font-size: 2rem;
  }

  .page-subtitle {
    font-size: 1rem;
  }

  .filter-section {
    grid-template-columns: 1fr 1fr;
    align-items: end;
  }

  .filter-title {
    grid-column: 1 / -1;
    margin-right: 0;
    margin-bottom: 0.25rem !important;
  }

  .filter-title-wrap {
    grid-column: 1 / -1;
  }

  .filter-action-btn {
    grid-column: 1 / -1;
    justify-self: start;
  }
}

@media (max-width: 768px) {
  /* Phone layout: switch from table to card-based rendering. */
  .goals-main {
    padding-left: 0.9rem !important;
    padding-right: 0.9rem !important;
  }

  .page-title {
    font-size: 1.65rem;
    margin-bottom: 0.4rem !important;
  }

  .form-title {
    font-size: 1.15rem;
  }

  .filter-title {
    width: 100%;
    font-size: 1.45rem;
    margin-bottom: 0.35rem !important;
  }

  .filter-section {
    grid-template-columns: 1fr;
    gap: 0.65rem;
    padding: 0.95rem;
  }

  .filter-section .filter-field,
  .filter-section .btn {
    width: 100%;
  }

  .filter-action-btn {
    grid-column: auto;
    justify-self: stretch;
  }

  .form-actions {
    flex-direction: column;
  }

  .form-actions .btn {
    width: 100%;
  }

  .btn {
    font-size: 0.88rem;
    padding-top: 0.42rem;
    padding-bottom: 0.42rem;
  }

  .actions-cell {
    min-width: 6.5rem;
  }

  .actions-cell .btn {
    width: 100%;
  }

  .modal-backdrop {
    padding: 0.75rem;
  }

  .step-modal-card {
    width: 100%;
    max-height: 92vh;
    padding: 1rem;
    border-radius: 1rem;
  }

  .step-row {
    flex-direction: column;
  }

  .step-row .btn {
    width: 100%;
  }

  .table-scroll {
    border-radius: 0.75rem;
    box-shadow: none;
  }

  .desktop-goals-table {
    display: none;
  }

  .mobile-goals-list {
    display: block;
  }

  .mobile-status-select {
    width: 100%;
    max-width: 100%;
    min-width: 0;
  }
}
</style>