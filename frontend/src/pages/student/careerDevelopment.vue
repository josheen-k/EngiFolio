<template>
  <div class="goals-page career-development-page">
    <Navbar />
    <div class="toggle">
      <div class="toggle-line">
        <button class="toggle-btn" :class="{ active: currTab === 'CAREER_PLAN' }" @click="goToCareerPlan">Career Development Plan</button>
        <button class="toggle-btn" :class="{ active: currTab === 'SMART_GOALS' }" @click="goToGoals">SMART Goals</button>
        <div class="toggle-pill" :class="currTab === 'CAREER_PLAN' ? 'pill-left' : 'pill-right'"></div>
      </div>
    </div>
    <main class="container-xl py-4 px-4 px-md-5 goals-main">
      <!-- Page header and main action, matching the SMART Goals page structure. -->
      <section class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
          <h1 class="page-title mb-2">Career Development Plan</h1>
          <p class="page-subtitle mb-0">Plan your pathway and connect your career direction with SMART goals.</p>
        </div>
        <button class="btn page-btn-primary px-4 py-2" @click="openCreatePlanForm">New Plan</button>
      </section>

      <!-- Create/edit form for plan details and linked SMART goals. -->
      <div v-if="showPlanForm" class="goal-form-card mb-4">
        <h3 class="form-title">{{ editingPlanId ? 'Edit Career Plan' : 'Create Career Plan' }}</h3>
        <form @submit.prevent="savePlan">
          <label>
            Plan Year:
            <select v-model.number="planForm.plan_year" class="plan-year-select" required>
              <option disabled :value="''">Select calendar year</option>
              <option
                v-for="opt in planYearSelectOptions"
                :key="opt.value"
                :value="opt.value"
              >
                {{ opt.label }}
              </option>
            </select>
          </label>

          <label>
            Professional Interests:
            <textarea v-model="planForm.professional_interests" placeholder="Roles, industries, or fields you are interested in"></textarea>
          </label>

          <label>
            Employers Of Interest:
            <textarea v-model="planForm.employers_of_interest" placeholder="Separate employers with commas or new lines"></textarea>
          </label>

          <label>
            Personal Values:
            <textarea v-model="planForm.personal_values" placeholder="What matters most in your future workplace"></textarea>
          </label>

          <label>
            Development Focus:
            <textarea v-model="planForm.development_focus" placeholder="Skills, competencies, or career goals for this year"></textarea>
          </label>

          <label>
            Extra-Curricular Activities:
            <textarea v-model="planForm.extracurriculars" placeholder="Clubs, societies, part-time work, volunteering"></textarea>
          </label>

          <label>
            Networking Plan:
            <textarea v-model="planForm.networking_plan" placeholder="People, events, or communities you want to connect with"></textarea>
          </label>

          <div class="goal-link-field">
            <span class="goal-link-label">Link SMART Goals:</span>
            <div v-if="allGoals.length" class="goal-select-grid">
              <button
                v-for="goal in allGoals"
                :key="goal.goal_id"
                type="button"
                class="goal-select-card"
                :class="{ selected: isGoalSelected(goal.goal_id) }"
                @click="toggleGoalSelection(goal.goal_id)"
              >
                <span class="goal-select-title">{{ goal.goal_description }}</span>
                <span class="goal-select-meta">{{ getGoalStatus(goal) }}</span>
                <span class="goal-select-check">{{ isGoalSelected(goal.goal_id) ? 'Selected' : 'Select' }}</span>
              </button>
            </div>
            <p v-else class="form-hint mb-0">No SMART goals available to link yet.</p>
            <span class="form-hint">Selected SMART goals are linked to this plan. The same goal can be linked to more than one plan.</span>
          </div>

          <p v-if="planFormError" class="form-error mb-0">{{ planFormError }}</p>

          <div class="d-flex gap-2 pt-1 form-actions">
            <button type="submit" class="btn page-btn-primary" :disabled="savingPlan">
              {{ savingPlan ? 'Saving...' : editingPlanId ? 'Update Plan' : 'Create Plan' }}
            </button>
            <button type="button" class="btn page-btn-outline" @click="cancelPlanForm">Cancel</button>
          </div>
        </form>
      </div>

      <div v-if="loading" class="status-msg">Loading career development plan...</div>
      <div v-else-if="errorMessage" class="status-msg error-msg">{{ errorMessage }}</div>
      <div v-else-if="sortedPlans.length === 0" class="status-msg">No career development plan has been added yet.</div>

      <div v-else>
        <!-- Desktop table view mirrors the SMART Goals table layout. -->
        <div class="table-scroll desktop-plan-table">
          <table class="goals-table career-table">
            <thead>
              <tr>
                <th>Year</th>
                <th>Professional Interests</th>
                <th>Employers</th>
                <th>Personal Values</th>
                <th>Development Focus</th>
                <th>Extra-Curricular</th>
                <th>Networking Plan</th>
                <th>Linked SMART Goals</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="plan in sortedPlans" :key="plan.plan_id">
                <td class="year-cell">{{ formatPlanYearLabel(plan.plan_year) }}</td>
                <td class="text-preview-cell">
                  <template v-if="!getPlanFieldRaw(plan, 'professional_interests')">Not added yet.</template>
                  <div
                    v-else-if="hasLongText(getPlanFieldRaw(plan, 'professional_interests'))"
                    class="text-preview-stack"
                  >
                    <p class="text-preview">{{ getTextPreview(getPlanFieldRaw(plan, 'professional_interests')) }}</p>
                    <button
                      type="button"
                      class="btn btn-link view-more-btn p-0"
                      @click="openTextModal('Professional Interests', getPlanFieldRaw(plan, 'professional_interests'))"
                    >
                      View more
                    </button>
                  </div>
                  <span v-else>{{ getPlanFieldRaw(plan, 'professional_interests') }}</span>
                </td>
                <td>
                  <template v-if="!splitList(plan.employers_of_interest).length">Not added yet.</template>
                  <div v-else-if="hasLongText(employersJoined(plan))" class="text-preview-stack">
                    <p class="text-preview">{{ getTextPreview(employersJoined(plan)) }}</p>
                    <button
                      type="button"
                      class="btn btn-link view-more-btn p-0"
                      @click="openTextModal('Employers', employersModalBody(plan))"
                    >
                      View more
                    </button>
                  </div>
                  <ul v-else class="compact-list">
                    <li v-for="employer in splitList(plan.employers_of_interest)" :key="employer">{{ employer }}</li>
                  </ul>
                </td>
                <td class="text-preview-cell">
                  <template v-if="!getPlanFieldRaw(plan, 'personal_values')">Not added yet.</template>
                  <div v-else-if="hasLongText(getPlanFieldRaw(plan, 'personal_values'))" class="text-preview-stack">
                    <p class="text-preview">{{ getTextPreview(getPlanFieldRaw(plan, 'personal_values')) }}</p>
                    <button
                      type="button"
                      class="btn btn-link view-more-btn p-0"
                      @click="openTextModal('Personal Values', getPlanFieldRaw(plan, 'personal_values'))"
                    >
                      View more
                    </button>
                  </div>
                  <span v-else>{{ getPlanFieldRaw(plan, 'personal_values') }}</span>
                </td>
                <td class="text-preview-cell">
                  <template v-if="!getPlanFieldRaw(plan, 'development_focus')">Not added yet.</template>
                  <div v-else-if="hasLongText(getPlanFieldRaw(plan, 'development_focus'))" class="text-preview-stack">
                    <p class="text-preview">{{ getTextPreview(getPlanFieldRaw(plan, 'development_focus')) }}</p>
                    <button
                      type="button"
                      class="btn btn-link view-more-btn p-0"
                      @click="openTextModal('Development Focus', getPlanFieldRaw(plan, 'development_focus'))"
                    >
                      View more
                    </button>
                  </div>
                  <span v-else>{{ getPlanFieldRaw(plan, 'development_focus') }}</span>
                </td>
                <td class="text-preview-cell">
                  <template v-if="!getPlanFieldRaw(plan, 'extracurriculars')">Not added yet.</template>
                  <div v-else-if="hasLongText(getPlanFieldRaw(plan, 'extracurriculars'))" class="text-preview-stack">
                    <p class="text-preview">{{ getTextPreview(getPlanFieldRaw(plan, 'extracurriculars')) }}</p>
                    <button
                      type="button"
                      class="btn btn-link view-more-btn p-0"
                      @click="openTextModal('Extra-Curricular Activities', getPlanFieldRaw(plan, 'extracurriculars'))"
                    >
                      View more
                    </button>
                  </div>
                  <span v-else>{{ getPlanFieldRaw(plan, 'extracurriculars') }}</span>
                </td>
                <td class="text-preview-cell">
                  <template v-if="!getPlanFieldRaw(plan, 'networking_plan')">Not added yet.</template>
                  <div v-else-if="hasLongText(getPlanFieldRaw(plan, 'networking_plan'))" class="text-preview-stack">
                    <p class="text-preview">{{ getTextPreview(getPlanFieldRaw(plan, 'networking_plan')) }}</p>
                    <button
                      type="button"
                      class="btn btn-link view-more-btn p-0"
                      @click="openTextModal('Networking Plan', getPlanFieldRaw(plan, 'networking_plan'))"
                    >
                      View more
                    </button>
                  </div>
                  <span v-else>{{ getPlanFieldRaw(plan, 'networking_plan') }}</span>
                </td>
                <td>
                  <div v-if="getPlanGoals(plan).length" class="goal-stack">
                    <article v-for="goal in getPlanGoals(plan)" :key="goal.goal_id" class="linked-goal">
                      <div v-if="hasLongText(goal.goal_description || '')" class="text-preview-stack linked-goal-preview">
                        <p class="text-preview">{{ getTextPreview(goal.goal_description || '') }}</p>
                        <span class="status-pill">{{ getGoalStatus(goal) }}</span>
                        <button
                          type="button"
                          class="btn btn-link view-more-btn p-0"
                          @click="openTextModal('SMART Goal', goal.goal_description || '')"
                        >
                          View more
                        </button>
                      </div>
                      <div v-else class="linked-goal-head">
                        <span>{{ goal.goal_description }}</span>
                        <span class="status-pill">{{ getGoalStatus(goal) }}</span>
                      </div>
                    </article>
                  </div>
                  <span v-else>No SMART goals linked yet.</span>
                </td>
                <td>
                  <div class="actions-stack">
                    <button
                      type="button"
                      class="action-icon-btn"
                      aria-label="Edit career plan"
                      title="Edit"
                      @click="openEditPlanForm(plan)"
                    >
                      <img :src="editIcon" alt="" class="action-icon-image" aria-hidden="true" />
                    </button>
                    <button
                      type="button"
                      class="action-icon-btn"
                      aria-label="Delete career plan"
                      title="Delete"
                      @click="deletePlan(plan)"
                    >
                      <img :src="deleteIcon" alt="" class="action-icon-image" aria-hidden="true" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Mobile card view keeps the same data readable on narrow screens. -->
        <div class="mobile-plan-list">
          <article v-for="plan in sortedPlans" :key="`mobile-${plan.plan_id}`" class="mobile-plan-card">
            <div class="mobile-plan-head">
              <h2 class="mobile-plan-title">{{ formatPlanYearLabel(plan.plan_year) }}</h2>
              <span class="mobile-status-badge">{{ getPlanGoals(plan).length }} SMART goals</span>
            </div>

            <section class="mobile-section">
              <p class="mobile-label">Professional Interests</p>
              <template v-if="!getPlanFieldRaw(plan, 'professional_interests')">
                <p class="mobile-value">Not added yet.</p>
              </template>
              <div v-else-if="hasLongText(getPlanFieldRaw(plan, 'professional_interests'))" class="text-preview-stack">
                <p class="mobile-value text-preview">{{ getTextPreview(getPlanFieldRaw(plan, 'professional_interests')) }}</p>
                <button
                  type="button"
                  class="btn btn-link view-more-btn p-0"
                  @click="openTextModal('Professional Interests', getPlanFieldRaw(plan, 'professional_interests'))"
                >
                  View more
                </button>
              </div>
              <p v-else class="mobile-value">{{ getPlanFieldRaw(plan, 'professional_interests') }}</p>
            </section>

            <section class="mobile-section">
              <p class="mobile-label">Employers</p>
              <template v-if="!splitList(plan.employers_of_interest).length">
                <p class="mobile-value">Not added yet.</p>
              </template>
              <div v-else-if="hasLongText(employersJoined(plan))" class="text-preview-stack">
                <p class="mobile-value text-preview">{{ getTextPreview(employersJoined(plan)) }}</p>
                <button
                  type="button"
                  class="btn btn-link view-more-btn p-0"
                  @click="openTextModal('Employers', employersModalBody(plan))"
                >
                  View more
                </button>
              </div>
              <ul v-else class="compact-list">
                <li v-for="employer in splitList(plan.employers_of_interest)" :key="`mobile-employer-${employer}`">{{ employer }}</li>
              </ul>
            </section>

            <div class="mobile-grid">
              <section>
                <p class="mobile-label">Personal Values</p>
                <template v-if="!getPlanFieldRaw(plan, 'personal_values')">
                  <p class="mobile-value">Not added yet.</p>
                </template>
                <div v-else-if="hasLongText(getPlanFieldRaw(plan, 'personal_values'))" class="text-preview-stack">
                  <p class="mobile-value text-preview">{{ getTextPreview(getPlanFieldRaw(plan, 'personal_values')) }}</p>
                  <button
                    type="button"
                    class="btn btn-link view-more-btn p-0"
                    @click="openTextModal('Personal Values', getPlanFieldRaw(plan, 'personal_values'))"
                  >
                    View more
                  </button>
                </div>
                <p v-else class="mobile-value">{{ getPlanFieldRaw(plan, 'personal_values') }}</p>
              </section>
              <section>
                <p class="mobile-label">Development Focus</p>
                <template v-if="!getPlanFieldRaw(plan, 'development_focus')">
                  <p class="mobile-value">Not added yet.</p>
                </template>
                <div v-else-if="hasLongText(getPlanFieldRaw(plan, 'development_focus'))" class="text-preview-stack">
                  <p class="mobile-value text-preview">{{ getTextPreview(getPlanFieldRaw(plan, 'development_focus')) }}</p>
                  <button
                    type="button"
                    class="btn btn-link view-more-btn p-0"
                    @click="openTextModal('Development Focus', getPlanFieldRaw(plan, 'development_focus'))"
                  >
                    View more
                  </button>
                </div>
                <p v-else class="mobile-value">{{ getPlanFieldRaw(plan, 'development_focus') }}</p>
              </section>
            </div>

            <section class="mobile-section">
              <p class="mobile-label">Extra-Curricular Activities</p>
              <template v-if="!getPlanFieldRaw(plan, 'extracurriculars')">
                <p class="mobile-value">Not added yet.</p>
              </template>
              <div v-else-if="hasLongText(getPlanFieldRaw(plan, 'extracurriculars'))" class="text-preview-stack">
                <p class="mobile-value text-preview">{{ getTextPreview(getPlanFieldRaw(plan, 'extracurriculars')) }}</p>
                <button
                  type="button"
                  class="btn btn-link view-more-btn p-0"
                  @click="openTextModal('Extra-Curricular Activities', getPlanFieldRaw(plan, 'extracurriculars'))"
                >
                  View more
                </button>
              </div>
              <p v-else class="mobile-value">{{ getPlanFieldRaw(plan, 'extracurriculars') }}</p>
            </section>

            <section class="mobile-section">
              <p class="mobile-label">Networking Plan</p>
              <template v-if="!getPlanFieldRaw(plan, 'networking_plan')">
                <p class="mobile-value">Not added yet.</p>
              </template>
              <div v-else-if="hasLongText(getPlanFieldRaw(plan, 'networking_plan'))" class="text-preview-stack">
                <p class="mobile-value text-preview">{{ getTextPreview(getPlanFieldRaw(plan, 'networking_plan')) }}</p>
                <button
                  type="button"
                  class="btn btn-link view-more-btn p-0"
                  @click="openTextModal('Networking Plan', getPlanFieldRaw(plan, 'networking_plan'))"
                >
                  View more
                </button>
              </div>
              <p v-else class="mobile-value">{{ getPlanFieldRaw(plan, 'networking_plan') }}</p>
            </section>

            <section class="mobile-section">
              <p class="mobile-label">Linked SMART Goals</p>
              <div v-if="getPlanGoals(plan).length" class="goal-stack">
                <article v-for="goal in getPlanGoals(plan)" :key="`mobile-goal-${goal.goal_id}`" class="linked-goal">
                  <div v-if="hasLongText(goal.goal_description || '')" class="text-preview-stack linked-goal-preview">
                    <p class="mobile-value text-preview">{{ getTextPreview(goal.goal_description || '') }}</p>
                    <span class="status-pill">{{ getGoalStatus(goal) }}</span>
                    <button
                      type="button"
                      class="btn btn-link view-more-btn p-0"
                      @click="openTextModal('SMART Goal', goal.goal_description || '')"
                    >
                      View more
                    </button>
                  </div>
                  <div v-else class="linked-goal-head">
                    <span>{{ goal.goal_description }}</span>
                    <span class="status-pill">{{ getGoalStatus(goal) }}</span>
                  </div>
                </article>
              </div>
              <p v-else class="mobile-value">No SMART goals linked yet.</p>
            </section>

            <div class="mobile-actions">
              <button class="btn page-btn-outline w-100" @click="openEditPlanForm(plan)">Edit</button>
              <button class="btn page-btn-danger w-100" @click="deletePlan(plan)">Delete</button>
            </div>
          </article>
        </div>
      </div>

      <div v-if="showTextModal" class="modal-backdrop" @click.self="closeTextModal">
        <div class="text-modal-card">
          <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
            <div>
              <h3 class="form-title mb-1">{{ textModalTitle }}</h3>
            </div>
            <button type="button" class="btn page-btn-outline" @click="closeTextModal">Close</button>
          </div>
          <div class="text-modal-body">{{ textModalContent }}</div>
        </div>
      </div>
    </main>
  </div>
</template>



<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Navbar from '@/components/Navbar.vue'
import api from '@/services/api'
import editIcon from '@/assets/edit.png'
import deleteIcon from '@/assets/delete.png'

// Page state for loaded plans, available SMART goals, and create/edit form status.
const route = useRoute()
const router = useRouter()
const plans = ref([])
const allGoals = ref([])
const loading = ref(false)
const errorMessage = ref('')
const showPlanForm = ref(false)
const editingPlanId = ref(null)
const savingPlan = ref(false)
const planFormError = ref('')
const selectedGoalIds = ref([])

const CALENDAR_YEAR_MIN = 2020

const buildCalendarYearRange = () => {
  const currentYear = new Date().getFullYear()
  const start = Math.min(currentYear - 1, 2026)
  const end = currentYear + 4
  const years = []
  for (let year = start; year <= end; year += 1) {
    years.push(year)
  }
  return years
}

const formatPlanYearLabel = (year) => {
  const y = Number(year)
  return Number.isFinite(y) ? String(y) : '—'
}

const planYearSelectOptions = computed(() => {
  const years = new Set(buildCalendarYearRange())
  plans.value.forEach((plan) => {
    const year = Number(plan.plan_year)
    if (Number.isFinite(year) && year >= CALENDAR_YEAR_MIN) {
      years.add(year)
    }
  })

  return [...years]
    .sort((a, b) => a - b)
    .map((year) => ({ value: year, label: String(year) }))
})

const defaultPlanYearForCreate = () => {
  const currentYear = new Date().getFullYear()
  const options = planYearSelectOptions.value
  const match = options.find((opt) => opt.value === currentYear)
  return match?.value ?? options[0]?.value ?? currentYear
}

const emptyPlanForm = () => ({
  plan_year: '',
  professional_interests: '',
  employers_of_interest: '',
  personal_values: '',
  development_focus: '',
  extracurriculars: '',
  networking_plan: ''
})

const planForm = ref(emptyPlanForm())

// Display helpers keep plan ordering and handle backend relation naming differences.
const sortedPlans = computed(() => {
  return [...plans.value].sort((a, b) => {
    const yearDifference = Number(a.plan_year) - Number(b.plan_year)
    if (yearDifference !== 0) {
      return yearDifference
    }
    return new Date(a.created_at || 0) - new Date(b.created_at || 0)
  })
})

const splitList = (value) => {
  if (!value) {
    return []
  }

  return String(value)
    .split(/\r?\n|,/)
    .map((item) => item.trim())
    .filter(Boolean)
}

const getPlanFieldRaw = (plan, field) => {
  const v = plan[field]
  if (v == null || v === '') {
    return ''
  }
  return String(v).trim()
}

const getPlanField = (plan, field) => {
  const raw = getPlanFieldRaw(plan, field)
  return raw || 'Not added yet.'
}

const employersJoined = (plan) => splitList(plan.employers_of_interest).join(', ')
const employersModalBody = (plan) => splitList(plan.employers_of_interest).join('\n')

const showTextModal = ref(false)
const textModalTitle = ref('')
const textModalContent = ref('')
const TEXT_PREVIEW_LIMIT = 90

const normalizeDisplayText = (value) => String(value ?? '').trim()
const hasLongText = (value) => normalizeDisplayText(value).length > TEXT_PREVIEW_LIMIT
const getTextPreview = (value) => {
  const text = normalizeDisplayText(value)
  if (text.length <= TEXT_PREVIEW_LIMIT) {
    return text || '-'
  }
  return `${text.slice(0, TEXT_PREVIEW_LIMIT).trimEnd()}...`
}
const openTextModal = (title, content) => {
  textModalTitle.value = title
  textModalContent.value = normalizeDisplayText(content)
  showTextModal.value = true
}
const closeTextModal = () => {
  showTextModal.value = false
  textModalTitle.value = ''
  textModalContent.value = ''
}

const getPlanGoals = (plan) => plan.smart_goals || plan.smartGoals || []
const getGoalStatus = (goal) => goal.status?.status || 'No status'
const currTab = computed(() =>
  route.name === 'careerDevelopment' ? 'CAREER_PLAN' : 'SMART_GOALS',
)
const goToGoals = () => {
  if (route.name !== 'GoalsPage') {
    router.push(`/goals/${route.params.id}`)
  }
}
const goToCareerPlan = () => {
  if (route.name !== 'careerDevelopment') {
    router.push(`/student/career-development/${route.params.id}`)
  }
}
const isGoalSelected = (goalId) => selectedGoalIds.value.includes(goalId)
const toggleGoalSelection = (goalId) => {
  selectedGoalIds.value = isGoalSelected(goalId)
    ? selectedGoalIds.value.filter((id) => id !== goalId)
    : [...selectedGoalIds.value, goalId]
}

// Form helpers support both creating a new plan and editing an existing one.
const resetPlanForm = () => {
  planForm.value = emptyPlanForm()
  selectedGoalIds.value = []
  editingPlanId.value = null
  planFormError.value = ''
}

const openCreatePlanForm = () => {
  resetPlanForm()
  planForm.value.plan_year = defaultPlanYearForCreate()
  showPlanForm.value = true
}

const openEditPlanForm = (plan) => {
  editingPlanId.value = plan.plan_id
  planForm.value = {
    plan_year: plan.plan_year || '',
    professional_interests: plan.professional_interests || '',
    employers_of_interest: plan.employers_of_interest || '',
    personal_values: plan.personal_values || '',
    development_focus: plan.development_focus || '',
    extracurriculars: plan.extracurriculars || '',
    networking_plan: plan.networking_plan || ''
  }
  selectedGoalIds.value = getPlanGoals(plan).map((goal) => goal.goal_id)
  planFormError.value = ''
  showPlanForm.value = true
}

const cancelPlanForm = () => {
  showPlanForm.value = false
  resetPlanForm()
}

const normalizePlanPayload = () => {
  const planYear = Number(planForm.value.plan_year)
  if (!Number.isInteger(planYear) || planYear < CALENDAR_YEAR_MIN) {
    throw new Error('Please select a calendar year (e.g. 2026).')
  }

  return {
    profile_id: Number(route.params.id),
    plan_year: planYear,
    professional_interests: planForm.value.professional_interests || null,
    employers_of_interest: planForm.value.employers_of_interest || null,
    personal_values: planForm.value.personal_values || null,
    development_focus: planForm.value.development_focus || null,
    extracurriculars: planForm.value.extracurriculars || null,
    networking_plan: planForm.value.networking_plan || null
  }
}

// API loading functions fetch plans and all goals that can be linked to a plan.
const fetchCareerPlans = async () => {
  try {
    loading.value = true
    errorMessage.value = ''

    const response = await api.get(`/career-plans/${route.params.id}`)
    plans.value = Array.isArray(response.data) ? response.data : [response.data]
  } catch (error) {
    console.error('Failed to load career development plans:', error)
    errorMessage.value = error.response?.data?.message || 'Failed to load career development plan.'
  } finally {
    loading.value = false
  }
}

const fetchSmartGoals = async () => {
  try {
    const response = await api.get('/smart-goals', {
      params: {
        profile_id: route.params.id
      }
    })
    allGoals.value = Array.isArray(response.data) ? response.data : []
  } catch (error) {
    console.error('Failed to load SMART goals for linking:', error)
    allGoals.value = []
  }
}

// Save creates or updates the plan, then syncs linked SMART goals for this plan only (goals may link to multiple plans).
const savePlan = async () => {
  try {
    savingPlan.value = true
    planFormError.value = ''

    let payload
    try {
      payload = normalizePlanPayload()
    } catch (validationError) {
      planFormError.value = validationError.message
      return
    }

    const response = editingPlanId.value
      ? await api.put(`/career-plans/${editingPlanId.value}`, payload)
      : await api.post('/career-plans', payload)

    const savedPlan = response.data

    await api.put(`/career-plans/${savedPlan.plan_id}/smart-goals`, {
      profile_id: Number(route.params.id),
      goal_ids: selectedGoalIds.value
    })

    showPlanForm.value = false
    resetPlanForm()
    await Promise.all([fetchCareerPlans(), fetchSmartGoals()])
  } catch (error) {
    console.error('Failed to save career development plan:', error)
    const serverMessage =
      error.response?.data?.message ||
      error.response?.data?.error ||
      Object.values(error.response?.data?.errors || {}).flat()[0]

    planFormError.value = serverMessage || 'Failed to save career development plan.'
  } finally {
    savingPlan.value = false
  }
}

const deletePlan = async (plan) => {
  const confirmed = window.confirm(`Delete ${formatPlanYearLabel(plan.plan_year)} career plan? This cannot be undone.`)
  if (!confirmed) {
    return
  }

  try {
    await api.delete(`/career-plans/${plan.plan_id}`)
    await Promise.all([fetchCareerPlans(), fetchSmartGoals()])
  } catch (error) {
    console.error('Failed to delete career development plan:', error)
    alert(error.response?.data?.message || 'Failed to delete career development plan.')
  }
}

onMounted(() => {
  fetchCareerPlans()
  fetchSmartGoals()
})
</script>


<style scoped>
/* Shared page styling copied from the SMART Goals visual language. */
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
  max-width: 1280px;
  overflow-x: hidden;
  box-sizing: border-box;
}

.toggle {
  display: flex;
  justify-content: center;
  padding: 1.5rem 0 0.5rem;
}

.toggle-line {
  position: relative;
  display: flex;
  width: min(100%, 42rem);
  background: #f0f0f0;
  border-radius: 2rem;
  padding: 0.3rem;
  gap: 0;
}

.toggle-pill {
  position: absolute;
  top: 0.3rem;
  bottom: 0.3rem;
  width: calc(50% - 0.3rem);
  background: #ffffff;
  border-radius: 2rem;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.25s ease;
  pointer-events: none;
}

.pill-left {
  transform: translateX(0);
}

.pill-right {
  transform: translateX(100%);
}

.toggle-btn {
  position: relative;
  z-index: 1;
  flex: 1;
  min-width: 0;
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.95rem;
  color: #888888;
  background: transparent;
  border: none;
  padding: 0.45rem 1.5rem;
  cursor: pointer;
  transition: color 0.2s ease;
  white-space: nowrap;
}

.toggle-btn.active {
  color: #222222;
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

.page-btn-danger {
  background: #ff746c;
  color: #ffffff;
  border: 1px solid #ff746c;
}

.page-btn-danger:hover {
  background: #e7635b;
  color: #ffffff;
}

/* Create/edit plan form. */
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
.goal-form-card select {
  padding: 0.58rem 0.75rem;
  border: 1px solid #d1d1d1;
  border-radius: 0.55rem;
  background: #ffffff;
  min-height: 2.65rem;
}

.goal-form-card textarea {
  resize: vertical;
  min-height: 72px;
}

.goal-form-card select[multiple] {
  min-height: 8rem;
}

/* Clickable SMART goal cards used instead of a native multi-select. */
.goal-link-field {
  display: flex;
  flex-direction: column;
  gap: 0.45rem;
}

.goal-link-label {
  color: #2b2b2b;
}

.goal-select-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr));
  gap: 0.7rem;
}

.goal-select-card {
  text-align: left;
  border: 1px solid #d8d8d8;
  border-radius: 0.95rem;
  background: #ffffff;
  padding: 0.85rem;
  cursor: pointer;
  transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
}

.goal-select-card:hover {
  border-color: #a8a8a8;
  box-shadow: 0 0.35rem 0.9rem rgba(0, 0, 0, 0.07);
  transform: translateY(-1px);
}

.goal-select-card.selected {
  border-color: #2b2b2b;
  box-shadow: inset 0 0 0 1px #2b2b2b, 0 0.35rem 0.9rem rgba(0, 0, 0, 0.08);
  background: #fafafa;
}

.goal-select-title {
  display: block;
  color: #2b2b2b;
  font-weight: 700;
  line-height: 1.35;
}

.goal-select-meta {
  display: inline-block;
  color: #666666;
  font-size: 0.82rem;
  margin-top: 0.45rem;
}

.goal-select-check {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-top: 0.65rem;
  border-radius: 999px;
  border: 1px solid #d0d0d0;
  color: #555555;
  font-size: 0.76rem;
  padding: 0.18rem 0.6rem;
}

.goal-select-card.selected .goal-select-check {
  background: #2b2b2b;
  border-color: #2b2b2b;
  color: #ffffff;
}

.form-hint {
  color: #6d6d6d;
  font-size: 0.82rem;
}

.form-error {
  color: #b42318;
}

.status-msg {
  padding: 1rem 1.25rem;
  border-radius: 0.9rem;
  background: #f5f5f5;
  color: #555555;
}

.error-msg {
  color: #b42318;
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

.text-preview-cell {
  vertical-align: middle !important;
}

.text-preview-stack {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.35rem;
}

.text-preview {
  display: -webkit-box;
  margin: 0;
  max-width: min(18rem, 100%);
  overflow: hidden;
  line-clamp: 3;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  font: 400 0.92rem/1.35 'Maven Pro', sans-serif;
  color: #6d6d6d;
  text-align: center;
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

.text-modal-card {
  width: min(100%, 42rem);
  max-height: 82vh;
  overflow-y: auto;
  background: #ffffff;
  border-radius: 1.4rem;
  padding: 1.4rem;
  box-shadow: 0 1rem 2.5rem rgba(0, 0, 0, 0.14);
}

.text-modal-body {
  padding: 1rem;
  border: 1px solid #e4e4e4;
  border-radius: 1rem;
  background: #fafafa;
  color: #2b2b2b;
  line-height: 1.55;
  white-space: pre-wrap;
  overflow-wrap: anywhere;
}

.table-scroll {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 0.9rem;
  box-shadow: 0 0.4rem 1.2rem rgba(0, 0, 0, 0.06);
}

.goals-table {
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
  vertical-align: top;
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

.goals-table tbody td {
  border-bottom: 1px solid #e6e6e6;
  background: transparent;
}

.goals-table tbody tr:last-child td {
  border-bottom: none;
}

/* Career plan table: headers and cell copy stay centered. */
.career-development-page .career-table th,
.career-development-page .career-table td {
  text-align: center;
  vertical-align: middle;
}

.career-development-page .career-table .compact-list {
  list-style-position: inside;
  padding-left: 0;
  margin-left: auto;
  margin-right: auto;
  display: inline-block;
  text-align: center;
}

.career-development-page .career-table .linked-goal-head {
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  text-align: center;
}

.career-development-page .career-table .goal-stack {
  justify-items: center;
}

/* Career plan table column widths. */
.career-table th:nth-child(1),
.career-table td:nth-child(1) {
  min-width: 7rem;
  white-space: nowrap;
}

.career-table th:nth-child(2),
.career-table td:nth-child(2),
.career-table th:nth-child(5),
.career-table td:nth-child(5),
.career-table th:nth-child(8),
.career-table td:nth-child(8) {
  min-width: 15rem;
}

.career-table th:nth-child(3),
.career-table td:nth-child(3),
.career-table th:nth-child(4),
.career-table td:nth-child(4),
.career-table th:nth-child(6),
.career-table td:nth-child(6),
.career-table th:nth-child(7),
.career-table td:nth-child(7) {
  min-width: 12rem;
}

.career-table th:nth-child(9),
.career-table td:nth-child(9) {
  min-width: 7rem;
  vertical-align: middle;
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

.year-cell {
  font-weight: 800;
  color: #2b2b2b;
}

.compact-list {
  margin: 0;
  padding-left: 1.1rem;
  color: #5f5f5f;
}

.goal-stack {
  display: grid;
  gap: 0.65rem;
}

.linked-goal {
  border: 1px solid #e3e3e3;
  border-radius: 0.85rem;
  background: #ffffff;
  padding: 0.75rem;
}

.linked-goal-head {
  display: flex;
  justify-content: space-between;
  gap: 0.75rem;
  align-items: flex-start;
}

.status-pill,
.mobile-status-badge {
  border-radius: 999px;
  padding: 0.2rem 0.65rem;
  font-size: 0.76rem;
  white-space: nowrap;
  border: 1px solid #d7d7d7;
  background: #f5f5f5;
  color: #555555;
}

.mobile-plan-list {
  display: none;
}

/* Mobile career cards: same centered copy as the desktop table. */
.career-development-page .mobile-plan-card {
  text-align: center;
}

.career-development-page .mobile-plan-head {
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.career-development-page .mobile-section .compact-list {
  list-style-position: inside;
  padding-left: 0;
  margin-left: auto;
  margin-right: auto;
  display: inline-block;
  text-align: center;
}

.career-development-page .mobile-section .linked-goal-head {
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  text-align: center;
}

.career-development-page .mobile-section .goal-stack {
  justify-items: center;
}

/* Mobile-only card layout for career plans. */
.mobile-plan-card {
  border: 1px solid #e3e3e3;
  border-radius: 1rem;
  background: #ffffff;
  padding: 0.95rem;
  margin-bottom: 0.85rem;
}

.mobile-plan-head {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 0.75rem;
  margin-bottom: 0.7rem;
}

.mobile-plan-title {
  margin: 0;
  font-size: 1.1rem;
  line-height: 1.35;
  font-family: 'Martel', serif;
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
}

@media (max-width: 768px) {
  .goals-main {
    padding-left: 0.9rem !important;
    padding-right: 0.9rem !important;
  }

  .toggle {
    padding: 1rem 0.9rem 0.25rem;
  }

  .toggle-btn {
    padding: 0.45rem 0.5rem;
    font-size: clamp(0.72rem, 2.8vw, 0.82rem);
  }

  .page-title {
    font-size: 1.65rem;
    margin-bottom: 0.4rem !important;
  }

  .form-title {
    font-size: 1.15rem;
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

  .desktop-plan-table {
    display: none;
  }

  .mobile-plan-list {
    display: block;
  }

  .mobile-grid {
    grid-template-columns: 1fr;
  }

  .goal-select-grid {
    grid-template-columns: 1fr;
  }

  .modal-backdrop {
    padding: 0.75rem;
  }

  .text-modal-card {
    width: 100%;
    max-height: 92vh;
    padding: 1rem;
    border-radius: 1rem;
  }
}
</style>