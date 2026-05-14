<template>
  <div class="page">
    <Navbar />

    <!-- MAIN CONTENT  -->
    <section class="container-xl py-4 px-4 px-md-5 main">
      <section class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
          <h1 class="page-title mb-2">SMART Goals Feedback</h1>
          <p class="page-subtitle mb-0">Provide feedback to students' SMART Goals.</p>
        </div>
        <!-- <button class="btn page-btn-primary px-4 py-2" @click="newGoal">New Goal</button> -->
      </section>

      <!-- RENDER NEW FEEDBACK FORM  -->
      <!-- <div v-if="showNewGoalForm" class="goal-form-card mb-4">
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
      </div> -->

      <!-- RENDER EDIT FEEDBACK FORM -->
      <!-- <div v-if="showEditGoalForm" class="goal-form-card mb-4">
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
      </div> -->
      <div class="filter-section mb-4">
        <div class="filter-title-wrap">
          <h2 class="filter-title mb-0">Student select</h2>
          <p class="filter-hint mb-0">Filter student</p>
        </div>

        <label class="filter-field">
          <span class="filter-label">Selected student</span>
          <select class="student-select">
                <option v-for="mapping in mappedStudents" :key="mapping.value" :value="mapping.value"
                >{{ mapping.first_name }} {{ mapping.last_name }}</option>
          </select>
        </label>

        <button class="btn page-btn-outline filter-action-btn" @click="loadGoals">Filter</button>
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

      <!-- <div v-else> -->
      <!-- Desktop/tablet table view -->
      <div class="table-scroll desktop-goals-table">
        <!-- <table class="goals-table">
          <thead>
            <tr>
              <th>feedback</th>
            </tr>
          </thead>

          <tbody>
            
          </tbody>
        </table> -->
        <table class="goals-table">
          <thead>
            <tr>
              <th>SMART Goal</th>
              <th>Action Steps</th>
              <th>Progress</th>
              <th>Learnings</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Completion Notes</th>
              <th>Feedback</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="goal in goals" :key="goal.goal_id">
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
                </div>
              </td>

              <td>{{ getStatusLabel(goal.goal_status_id) }}</td>
              <td>{{ goal.learnings }}</td>
              <td>{{ goal.start_date }}</td>
              <td>{{ goal.end_date }}</td>
              <td class="completion-notes-cell">{{ goal.completion_notes || '-' }}</td>

              <td>
                <div v-for="f in feedback" :key="f.goal_id">
                  <div v-if="f.goal_id==goal.goal_id">{{ f.feedback_content }}</div>
                </div>

                <div v-if="showForm" class="form-box">
                <!-- <div class="form-box"> -->
                  <h3>{{ editMode ? 'Edit Entry' : 'Add Entry' }}</h3>

                  <input v-model="form.feedback_content" placeholder="Feedback content" />

                  <div class="btn-row">
                      <button class="btn btn-dark" @click="saveEntry">
                        {{ editMode ? 'Update' : 'Create' }}
                      </button>

                      <button class="btn btn-light" @click="closeForm">
                        Cancel
                      </button>
                    </div>
                </div>

                <button
                  type="button"
                  class="action-icon-btn"
                  aria-label="Edit feedback"
                  title="Edit"
                  @click="editFeedback(goal)"
                >
                  <img :src="editIcon" alt="" class="action-icon-image" aria-hidden="true" />
                </button>

              </td>
            </tr>
          </tbody>
        </table>

      </div>

      
    </section>


  

  <!-- <main class="container-xl py-4 px-4 px-md-5 goals-main"> -->
      <!-- <section class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
          <h1 class="page-title mb-2">Provide student feedback</h1>
          <p class="page-subtitle mb-0">Provide feedback for students' competency development and goals.</p>
        </div>
      </section> -->

      <!-- category sections -->
      <!-- <div class="mb-4" v-for="c in students" :key="c.key">
        <div class="d-flex align-items-center gap-2 mb-3 category" @click="c.open = !c.open">
          <img class="triangle" :class="{ open: c.open }" src="@/assets/triangle.png"/>
          <span class="c-label">{{ c.label }}</span>
          <span class="txt">{{ filteredCompts(c).length }}</span>
        </div>
      </div> -->

      <!-- TABLE -->
      <!-- <div class="table-box">
        <table>
          <thead>
            <tr>
              <th>Student name</th>
              <th>Company</th>
              <th>Progress Notes</th>
              <th>Date Met</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>aaa</td>
              <td>bbb</td>
              <td>ccc</td>
              <td>ddd</td>
            </tr>
            <tr>
              <td>aaa</td>
              <td>bbb</td>
              <td>ccc</td>
              <td>ddd</td>
            </tr>
          </tbody>
        </table>
      </div> -->

  <!-- </main> -->

  <!-- <Footer /> -->
  </div>
</template>

<script setup>
import { ref, onMounted, reactive, computed } from 'vue'
import { useRoute } from 'vue-router'
import Navbar from '@/components/Navbar.vue'
import api from "@/services/api";
import editIcon from '@/assets/edit.png'
import deleteIcon from '@/assets/delete.png'
import FeedbackReflections from '@/components/FeedbackReflections.vue';
// import { formGroupKey } from 'node_modules/bootstrap-vue-next/dist/utils/keys';
// import CompetencyPage from './competencyPage.vue';

const goals = ref([])
const feedback = ref([])
const loading = ref(true)
const fromDate = ref('')
const toDate = ref('')
const showNewGoalForm = ref(false)
const showStepModal = ref(false)
const editingFeedback = ref(null)

const showForm = ref(false)
const editMode = ref(false)

const planId = ref(null)
const stepModalGoal = ref(null)
const savingSteps = ref(false)
const feedbackDrafts = ref([])
const expandedStepsByGoal = ref({})
const route = useRoute()
const feedbackModalGoal = ref(null)
const profileId = computed(() => Number(route.params.id)) //computed(() => Number(route.params.id))
const mappedStudents = ref([])
const studentNames = ref([])
const studentName = ref('')
const coolTest = ref(null)

const progressStatusOptions = [
  { value: 1, label: 'Planned' },
  { value: 2, label: 'In Progress' },
  { value: 3, label: 'Completed' },
]

const form = reactive({
  goal_id: null,
  staff_id: null,
  feedback_content: '',
})

const openForm = () => {
  editMode.value = false
  form.value = {
    feedback_content: null,
  }
  showForm.value = true
}

const closeForm = () => {
  showForm.value = false
}

const editForm = (entry) => {
  editMode.value = true
  form.value = { ...entry }
  showForm.value = true
}

// Backend can return relationship keys in snake_case or camelCase depending on serializer/config.
const getGoalSteps = (goal) => goal.action_steps || goal.actionSteps || []
const getStatusLabel = (goalStatusId) => {
  const matched = progressStatusOptions.find((item) => item.value === Number(goalStatusId))
  return matched ? matched.label : '—'
}
const getStatusClass = (goalStatusId) => {
  const id = Number(goalStatusId)
  if (id === 3) return 'status-completed'
  if (id === 2) return 'status-in-progress'
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

// loadGoals taken from GoalsPage.vue
const loadGoals = async () => {
  try {
    loading.value = true

    // Build optional date-range query params from filter inputs.
    const params = {
      profile_id: 2//profileId.value
    }

    if (fromDate.value) {
      params.from = fromDate.value
    }

    if (toDate.value) {
      params.to = toDate.value
    }

    const response = await api.get('/smart-goals', {
      params
    })

    // Update table data with the latest goals from API.
    goals.value = response.data
  } catch (error) {
    console.error('Error while fetching goals:', error)
  } 
  finally {
    loading.value = false
  }
}

const loadMappedStudents = async () => {
  try {
    const response = await api.get(`/staff/my-students`)
    mappedStudents.value = response.data
  } catch (error) {
    console.error('Error fetching mapped students', error)
  }
}

const loadFeedback = async () => {
  try {
    const response = await api.get(`/smart-goals/all/feedback/${profileId.value}`);
    feedback.value = response.data
  } catch (error) {
    console.error('Error fetching feedback', error)
    // feedback.value = []
  }
}

const loadPlanId = async () => {
  try {
    const response = await api.get('/career-plans', {
      params: {
        profile_id: 2//profileId.value
      }
    })
    if (response.data && response.data.length > 0) {
      // Use the first available career plan as the parent plan for new goals.
      planId.value = response.data[0].plan_id
      newGoalData.plan_id = response.data[0].plan_id
    } else {
      console.warn('No career development plan found')
      alert('Please create a Career Development Plan first')
    }
  } catch (error) {
    // console.error('Error loading plan ID:', error)
    // alert('Failed to load Career Development Plan')
  }
}

onMounted(() => {
  loadMappedStudents()
  loadPlanId()
  loadFeedback()
  loadGoals()
})

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

const addFeedback = () => {
  feedbackDrafts.value.push({
    goal_id: null,
    staff_id: null,
    feedback_content: ''
  })
}

const editFeedback = (feedback) => {
  editingFeedback.value = feedback
  Object.assign(editFeedbackData, {
    goal_id: feedback.goal_id,
    staff_id: feedback.staff_id,
    feedback_content: feedback.feedback_content || '',
  })

  // if()

  showEditFeedbackForm.value = true
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

const updateFeedback = (goal) => {}

const deleteFeedback = (goal) => {}


</script>

<style scoped>
.page {
  min-height: 100vh;
  background: #ffffff;
  font-family: 'Maven Pro', sans-serif;
  color: #2b2b2b;
  width: 100%;
  max-width: 100%;
  overflow-x: hidden;
}

.main {
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

.student-select {
  width: min(100%, 14rem);
  min-width: 14.5rem;
  padding: 0.65rem 0.55rem;
  border: 1px solid #d0d0d0;
  border-radius: 0.55rem;
  background: #ffffff;
  font-family: 'Maven Pro', sans-serif;
  display: block;
  margin: 0 auto;
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
  vertical-align: middle;
}

.goals-table tbody tr:nth-child(even) {
  background: #fcfcfc;
}

.goals-table tbody tr:hover {
  background: #f8f8f8;
}

/*
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
}*/

.goals-table tbody td {
  border-bottom: 1px solid #e6e6e6;
  background: transparent;
}

.goals-table tbody tr:last-child td {
  border-bottom: none;
}

.goals-table th:nth-child(1),
.goals-table td:nth-child(1) {
  /* Column 1: SMART Goal */
  min-width: 15rem;
}

.goals-table th:nth-child(2),
.goals-table td:nth-child(2) {
  /* Column 2: Action Steps */
  min-width: 16rem;
}

.goals-table th:nth-child(3),
.goals-table td:nth-child(3) {
  /* Column 3: Progress */
  min-width: 8rem;
}
.goals-table th:nth-child(4),
.goals-table td:nth-child(4),
.goals-table th:nth-child(7),
.goals-table td:nth-child(7){
  /* Columns 4,7: Learnings, Completion Notes */
  min-width: 10rem;
}

.goals-table th:nth-child(5),
.goals-table td:nth-child(5),
.goals-table th:nth-child(6),
.goals-table td:nth-child(6) {
  /* Columns 5,6: Start Date, End Date */
  min-width: 8.5rem;
  white-space: nowrap;
}

.goals-table th:last-child,
.goals-table td:last-child {
  /* Last column: Feedback */
  min-width: 14rem;
}

.steps-list {
  margin: 0 0 0.4rem 0;
  padding-left: 1.1rem;
  max-height: 6.8rem;
  overflow-y: auto;
}
/*
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
}*/

.steps-cell {
  vertical-align: middle !important;
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