<template>
  <Navbar />

  <main class="networking-page">
    <section class="networking-shell">
      <div class="pitch-box">
        <label class="pitch-label"> Elevator Pitch</label>
        <textarea v-model="elevatorPitch" class="pitch-textarea" placeholder="Write your elevator pitch here..."></textarea>
        <div class="pitch-actions">
          <button class="action-button small-button" @click="saveElevatorPitch" :disabled="savingPitch">{{ savingPitch ? 'Saving...' : 'Save' }}</button>
        </div>
      </div>
      <div class="page-header">
        <div>
          <p class="eyebrow">Networking Planner</p>
          <h1 class="page-title">Track events on a calendar</h1>
          <p class="page-copy">
            Tap a date to add a new event, or open an event day to review its details,
            questions, and comments.
          </p>
          <div class="networking-switch">
            <RouterLink :to="`/student/networking/${route.params.id || 1}`" class="switch-pill active"> Events Calendar</RouterLink>
            <RouterLink :to="`/student/networking/contacts/${route.params.id || 1}`" class="switch-pill"> Industry Contacts</RouterLink>
          </div>
        </div>

        <button class="action-button" @click="openCreateForm()">
          Add Event
        </button>        
      </div>

      <section class="calendar-card">
        <div class="calendar-toolbar">
          <div>
            <p class="toolbar-label">Calendar View</p>
            <h2 class="calendar-title">{{ currentCalendarTitle }}</h2>
          </div>

          <div class="calendar-actions">
            <div class="view-switcher">
              <button
                class="view-toggle"
                :class="{ 'is-active': calendarView === 'month' }"
                @click="switchCalendarView('month')"
              >
                Month
              </button>
              <button
                class="view-toggle"
                :class="{ 'is-active': calendarView === 'year' }"
                @click="switchCalendarView('year')"
              >
                Year
              </button>
            </div>

            <div class="calendar-controls">
              <button class="ghost-button" @click="goToPreviousMonth">Previous</button>
              <button class="ghost-button" @click="goToToday">Today</button>
              <button class="ghost-button" @click="goToNextMonth">Next</button>
            </div>
          </div>
        </div>

        <div v-if="calendarView === 'month'" class="calendar-scroll">
          <div class="calendar-grid">
            <div
              v-for="weekday in weekdayLabels"
              :key="weekday"
              class="weekday-cell"
            >
              {{ weekday }}
            </div>

            <button
              v-for="day in calendarDays"
              :key="day.dateKey"
              class="day-cell"
              :class="{
                'is-outside-month': !day.isCurrentMonth,
                'is-today': day.isToday,
                'has-events': day.events.length,
              }"
              @click="handleDateClick(day)"
            >
              <div class="day-cell-header">
                <span class="day-number">{{ day.dayNumber }}</span>
                <span v-if="day.events.length" class="day-pill">
                  {{ day.events.length }} event{{ day.events.length > 1 ? 's' : '' }}
                </span>
              </div>

              <div class="event-chip-list">
                <span
                  v-for="event in day.events.slice(0, 2)"
                  :key="event.event_id"
                  class="event-chip"
                >
                  {{ event.event_name }}
                </span>

                <span v-if="day.events.length > 2" class="more-chip">
                  +{{ day.events.length - 2 }} more
                </span>
              </div>
            </button>
          </div>
        </div>

        <div v-else class="year-view-shell">
          <p class="year-scroll-hint">Scroll vertically to move through years</p>

          <div ref="yearScrollContainer" class="year-scroll-frame" @scroll="handleYearViewScroll">
            <section
              v-for="yearBlock in yearViewYears"
              :key="yearBlock.key"
              class="year-section"
              :data-year="yearBlock.year"
            >
              <h3 class="year-section-title">{{ yearBlock.year }}</h3>

              <div class="year-grid">
                <article
                  v-for="monthCard in yearBlock.months"
                  :key="monthCard.key"
                  class="year-card"
                >
                  <button
                    class="year-card-title"
                    @click="openMonthFromYear(monthCard.year, monthCard.monthIndex)"
                  >
                    {{ monthCard.monthLabel }}
                  </button>

                  <div class="mini-weekdays">
                    <span
                      v-for="weekday in miniWeekdayLabels"
                      :key="`${monthCard.key}-${weekday}`"
                      class="mini-weekday"
                    >
                      {{ weekday }}
                    </span>
                  </div>

                  <div class="mini-month-grid">
                    <button
                      v-for="day in monthCard.days"
                      :key="`${monthCard.key}-${day.dateKey}`"
                      class="mini-day"
                      :class="{
                        'is-outside-month': !day.isCurrentMonth,
                        'is-today': day.isToday,
                        'has-events': day.events.length,
                      }"
                      @click="openMonthFromYear(monthCard.year, monthCard.monthIndex, day.dateKey)"
                    >
                      <span class="mini-day-number">{{ day.dayNumber }}</span>
                      <span v-if="day.events.length" class="mini-event-dot" />
                    </button>
                  </div>
                </article>
              </div>
            </section>
          </div>
        </div>
      </section>
    </section>

    <div v-if="showForm" class="modal-overlay" @click.self="closeForm">
      <div class="modal-card form-modal">
        <div class="modal-header">
          <div>
            <p class="modal-label">{{ editingEventId ? 'Update Event' : 'Create Event' }}</p>
            <h2>{{ editingEventId ? 'Edit networking event' : 'Add a networking event' }}</h2>
          </div>

          <button class="icon-button" @click="closeForm">Close</button>
        </div>

        <div class="form-grid">
          <label class="field">
            <span>Event Name</span>
            <input v-model="newEvent.name" placeholder="Company mixer, panel, workshop..." />
          </label>

          <label class="field">
            <span>Date</span>
            <input v-model="newEvent.date" type="date" />
          </label>

          <label class="field">
            <span>Location</span>
            <input v-model="newEvent.location" placeholder="Adelaide, online, campus..." />
          </label>

          <label class="field field-full">
            <span>Details</span>
            <textarea
              v-model="newEvent.details"
              rows="5"
              placeholder="What is this event about, and what do you want to remember?"
            ></textarea>
          </label>
          <div class="field field-full">
            <span>Related Contacts</span>
            <div v-if="contacts.length" class="contact-picker">
              <label v-for="contact in contacts" :key="contact.contact_id" class="contact-option">
                <input type="checkbox" :value="contact.contact_id" v-model="newEvent.contact_ids">
                <span>{{ contact.contact_name }}</span>
              </label>
            </div>
            <p v-else>No contacts available</p>
          </div>
        </div>

        <div class="modal-actions">
          <button class="ghost-button" @click="closeForm">Cancel</button>
          <button class="action-button" @click="addEvent">
            {{ editingEventId ? 'Update Event' : 'Create Event' }}
          </button>
        </div>
      </div>
    </div>

    <div v-if="showEventDetails" class="modal-overlay" @click.self="closeEventDetails">
      <div class="modal-card details-modal">
        <div class="modal-header">
          <div>
            <p class="modal-label">Events on</p>
            <h2>{{ selectedDateLabel }}</h2>
          </div>

          <button class="icon-button" @click="closeEventDetails">Close</button>
        </div>

        <div v-if="selectedDateEvents.length" class="details-stack">
          <article
            v-for="event in selectedDateEvents"
            :key="event.event_id"
            class="event-detail-card"
          >
            <div class="event-detail-top">
              <div>
                <p class="event-date">{{ formatFullDate(normalizeEventDate(event.event_datetime)) }}</p>
                <h3>{{ event.event_name }}</h3>
              </div>

              <div class="card-actions">
                <button class="ghost-button" @click="editEvent(event)">Edit</button>
                <button class="delete-button" @click="deleteEvent(event.event_id)">Delete</button>
              </div>
            </div>

            <div class="detail-row">
              <span class="detail-label">Location</span>
              <p>{{ event.location || 'No location added yet.' }}</p>
            </div>

            <div class="detail-row">
              <span class="detail-label">Details</span>
              <p>{{ event.details || 'No event details added yet.' }}</p>
            </div>

            <div class="detail-row">
              <span class="detail-label">Related Contacts</span>
              <ul v-if="event.contacts && event.contacts.length" class="contact-list">
                <li v-for="contact in event.contacts" :key="contact.contact_id" class="contact-list-item">
                  <span class="contact-name">{{ contact.contact_name }}</span>
                  <span v-if="contact.company" class="contact-company">{{ contact.company }}</span>
                </li>
              </ul>
              <p v-else class="contact-empty">No related contacts added yet.</p>
            </div>
            <div class="detail-columns">
              <div class="detail-panel">
                <div class="panel-header">
                  <h4>Questions</h4>
                  <span class="panel-count">{{ event.questions?.length || 0 }}</span>
                </div>

                <div class="inline-editor">
                  <input
                    :value="getQuestionDraft(event.event_id)"
                    placeholder="Add a question to ask at this event"
                    @input="questionDrafts[event.event_id] = $event.target.value"
                  />
                  <div class="inline-actions">
                    <button class="action-button small-button" @click="submitQuestion(event.event_id)">
                      {{ editingQuestionIds[event.event_id] ? 'Update' : 'Add' }}
                    </button>
                    <button
                      v-if="editingQuestionIds[event.event_id]"
                      class="ghost-button small-button"
                      @click="clearQuestionEditor(event.event_id)"
                    >
                      Cancel
                    </button>
                  </div>
                </div>

                <ul v-if="event.questions && event.questions.length" class="item-list">
                  <li v-for="question in event.questions" :key="question.question_id" class="list-item">
                    <span>{{ question.question_text }}</span>
                    <div class="list-actions">
                      <button class="ghost-button small-button" @click="editQuestion(event.event_id, question)">
                        Edit
                      </button>
                      <button
                        class="delete-button small-button"
                        @click="deleteQuestion(event.event_id, question.question_id)"
                      >
                        Delete
                      </button>
                    </div>
                  </li>
                </ul>
                <p v-else>No questions saved for this event yet.</p>
              </div>

              <div class="detail-panel">
                <div class="panel-header">
                  <h4>Comments</h4>
                  <span class="panel-count">{{ event.comments?.length || 0 }}</span>
                </div>

                <div class="inline-editor comment-evidence-editor">
                  <div class="comment-evidence-grid">
                    <div class="comment-evidence-field">
                      <label class="detail-label">Evidence type</label>
                      <select v-model="getCommentDraft(event.event_id).comment_type">
                        <option value="">Select evidence type</option>
                        <option value="link">Link</option>
                        <option value="image">Image</option>
                        <option value="video">Video</option>
                      </select>
                    </div>

                    <div class="comment-evidence-field comment-evidence-input">
                      <label class="detail-label">Evidence input</label>

                      <input
                        v-if="!getCommentDraft(event.event_id).comment_type"
                        disabled
                        placeholder="Select a type first"
                      />

                      <input
                        v-else-if="getCommentDraft(event.event_id).comment_type === 'link'"
                        v-model="getCommentDraft(event.event_id).link_url"
                        type="url"
                        placeholder="https://example.com"
                      />

                      <div v-else class="upload-zone">
                        <input
                          :id="`comment-file-${event.event_id}`"
                          class="upload-input"
                          type="file"
                          :accept="getCommentDraft(event.event_id).comment_type === 'image' ? 'image/*' : 'video/*'"
                          @change="handleCommentFileChange(event.event_id, $event)" />
                          <label :for="`comment-file-${event.event_id}`" class="upload-card">
                            <strong >Click to upload or drag & drop</strong>
                            <span >{{ getCommentDraft(event.event_id).comment_type === 'image' ? 'PNG, JPG, JPEG, GIF' :'MP4, MOV' }}</span>
                          </label>
                        <p class="upload-note" v-if="!getCommentDraft(event.event_id).file_name">
                          {{ getCommentDraft(event.event_id).comment_type == 'image' ? 'Upload an image file' : 'Upload a video file' }}
                        </p>
                        <p class="upload-file-name" v-else></p>
                      </div>
                    </div>
                  </div>

                  <div class="inline-actions">
                    <button
                      v-if="getCommentDraft(event.event_id).comment_type === 'link'"
                      class="action-button small-button"
                      @click="submitComment(event.event_id)"
                    >
                      {{ editingCommentIds[event.event_id] ? 'Update' : 'Add' }}
                    </button>
                    <button
                      v-if="editingCommentIds[event.event_id]"
                      class="ghost-button small-button"
                      @click="clearCommentEditor(event.event_id)"
                    >
                      Cancel
                    </button>
                  </div>
                </div>

                <ul v-if="event.comments && event.comments.length" class="item-list">
                  <li v-for="comment in event.comments" :key="comment.id" class="list-item">
                    <div class="comment-display">
                      <a
                        v-if="comment.comment_type === 'link'"
                        :href="comment.link_url"
                        target="_blank"
                        rel="noopener noreferrer"
                      >
                        {{ comment.link_url }}
                      </a>
                      <a
                        v-else-if="comment.file_path"
                        :href="`http://127.0.0.1:8000/storage/${comment.file_path}`"
                        target="_blank"
                        rel="noopener noreferrer"
                      >
                        {{ comment.file_name || 'Open file' }}
                      </a>
                      <span v-else>No file available</span>
                    </div>

                    <div class="list-actions">
                      <button
                        class="delete-button small-button"
                        @click="deleteComment(event.event_id, comment.id)"
                      >
                        Delete
                      </button>
                    </div>
                  </li>
                </ul>
                <p v-else>No comments saved for this event yet.</p>
              </div>
            </div>
          </article>
        </div>

        <div v-else class="empty-state">
          <h3>No events on this date</h3>
          <p>Select another day or add a new networking event.</p>
        </div>
      </div>
    </div>

    <div v-if="popUp.show" class="popUp-msg" :class="popUp.type">
      {{ popUp.message }}
    </div>
  </main>
</template>

<script setup>
import { computed, nextTick, onMounted, ref } from 'vue'
import axios from 'axios'
import Navbar from '@/components/Navbar.vue'
import { useRoute } from 'vue-router'


const route = useRoute()
const weekdayLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
const miniWeekdayLabels = ['M', 'T', 'W', 'T', 'F', 'S', 'S']
const monthOptions = [
  'January',
  'February',
  'March',
  'April',
  'May',
  'June',
  'July',
  'August',
  'September',
  'October',
  'November',
  'December',
]
const apiBaseUrl = 'http://127.0.0.1:8000/api'
const YEAR_VIEW_START = 1900
const YEAR_VIEW_END = 2100

const events = ref([])
const currentMonth = ref(startOfMonth(new Date()))
const calendarView = ref('month')
const elevatorPitch = ref('')
const savingPitch = ref(false)
const showForm = ref(false)
const showEventDetails = ref(false)
const showConfirmDialog = ref(false)
const editingEventId = ref(null)
const selectedDate = ref('')
const questionDrafts = ref({})
const editingQuestionIds = ref({})
const commentDrafts = ref({})
const editingCommentIds = ref({})
const eventEditSnapshot = ref(null)
const questionEditSnapshots = ref({})
const commentEditSnapshots = ref({})
const yearScrollContainer = ref(null)

const newEvent = ref(createEmptyEvent())
const confirmDialog = ref(createConfirmDialog())

const contacts = ref([])
let confirmResolver = null
let syncingYearScroll = false
let yearScrollFrameId = null

function createEmptyEvent(date = '') {
  return {
    name: '',
    date,
    location: '',
    details: '',
    contact_ids: [],
  }
}

function createEmptyCommentDraft(){
  return{
    comment_type: '',
    link_url: '',
    file: null,
    file_name: '',
  }
}

function createConfirmDialog() {
  return {
    title: '',
    message: '',
    confirmLabel: 'Confirm',
    cancelLabel: 'Cancel',
    variant: 'default',
  }
}

function startOfMonth(date) {
  return new Date(date.getFullYear(), date.getMonth(), 1)
}

function dateKeyToDate(dateKey) {
  const [year, month, day] = dateKey.split('-').map(Number)
  return new Date(year, month - 1, day)
}

function normalizeEventDate(dateTime) {
  if (!dateTime) return ''
  return String(dateTime).slice(0, 10)
}

function formatDateKey(date) {
  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const day = String(date.getDate()).padStart(2, '0')
  return `${year}-${month}-${day}`
}

function formatFullDate(dateKey) {
  if (!dateKey) return ''

  return new Intl.DateTimeFormat('en-AU', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  }).format(dateKeyToDate(dateKey))
}

function setCurrentMonth(year, month) {
  currentMonth.value = new Date(year, month, 1)
}

function createMonthGrid(baseDate) {
  const year = baseDate.getFullYear()
  const month = baseDate.getMonth()
  const firstDayOfMonth = new Date(year, month, 1)
  const mondayFirstOffset = (firstDayOfMonth.getDay() + 6) % 7
  const gridStartDate = new Date(year, month, 1 - mondayFirstOffset)
  //
  return Array.from({ length: 42 }, (_, index) => {
    const date = new Date(gridStartDate)
    date.setDate(gridStartDate.getDate() + index)

    const dateKey = formatDateKey(date)

    return {
      date,
      dateKey,
      dayNumber: date.getDate(),
      isCurrentMonth: date.getMonth() === month,
      isToday: dateKey === todayKey.value,
      events: eventsByDate.value[dateKey] || [],
    }
  })
}

function ensureMonthForDate(dateKey) {
  if (!dateKey) return
  currentMonth.value = startOfMonth(dateKeyToDate(dateKey))
}

function getQuestionDraft(eventId) {
  return questionDrafts.value[eventId] || ''
}

function getCommentDraft(eventId) {
  if(!commentDrafts.value[eventId]) {
    commentDrafts.value[eventId] = createEmptyCommentDraft()
  }
  return commentDrafts.value[eventId]
}

function clearQuestionEditor(eventId) {
  questionDrafts.value[eventId] = ''
  editingQuestionIds.value[eventId] = null
  questionEditSnapshots.value[eventId] = null
}

function clearCommentEditor(eventId) {
  commentDrafts.value[eventId] = createEmptyCommentDraft()
  editingCommentIds.value[eventId] = null
  commentEditSnapshots.value[eventId] = null
}

function openConfirmDialog(options) {
  confirmDialog.value = {
    ...createConfirmDialog(),
    ...options,
  }
  showConfirmDialog.value = true

  return new Promise((resolve) => {
    confirmResolver = resolve
  })
}

function resolveConfirmDialog(result) {
  showConfirmDialog.value = false

  if (confirmResolver) {
    confirmResolver(result)
    confirmResolver = null
  }
}

const fetchEvents = async () => {
  const response = await axios.get(`${apiBaseUrl}/networking-events`)
  events.value = response.data
}

const fetchContacts = async () => {
  const response = await axios.get(`${apiBaseUrl}/users/${route.params.id}/industry-contacts`)
  contacts.value = response.data
}

onMounted(async () => {
  await fetchEvents()
  fetchContacts()
  fetchElevatorPitch()

  if (route.query.eventId) {
    const event = events.value.find(e=> e.event_id === Number(route.query.eventId))
    if (event) {
      selectedDate.value = normalizeEventDate(event.event_datetime)
      openEventDetails(selectedDate.value)
    }
  }
})

const fetchElevatorPitch = async() => {
  const response = await axios.get(
    `${apiBaseUrl}/profile/${route.params.id}/elevator-pitch`
  )
  elevatorPitch.value = response.data.pitch_text || ''
}

const saveElevatorPitch = async() => {
  const trimmedPitch = elevatorPitch.value.trim();

  if(!trimmedPitch) {
    showPopUp("Elevator Pitch is empty. Enter in your pitch.", "error");
    return;
  }
  savingPitch.value = true
  try {
    await axios.put(`${apiBaseUrl}/profile/${route.params.id}/elevator-pitch`,{pitch_text: elevatorPitch.value,});

    showPopUp("Your elevator pitch has been saved.", "success");
  } finally {
    savingPitch.value = false
  }
}

// Set up a pop up notification instead of having an alert
const popUp = ref({ show: false, message: '', type: '' })

const showPopUp = (message, type) => {
  popUp.value = { show: true, message, type }
  setTimeout(() => popUp.value.show = false, 3000)
}


const eventsByDate = computed(() => {
  return events.value.reduce((grouped, event) => {
    const dateKey = normalizeEventDate(event.event_datetime)

    if (!dateKey) {
      return grouped
    }

    if (!grouped[dateKey]) {
      grouped[dateKey] = []
    }

    grouped[dateKey].push(event)
    return grouped
  }, {})
})

const currentMonthLabel = computed(() => {
  return new Intl.DateTimeFormat('en-AU', {
    month: 'long',
    year: 'numeric',
  }).format(currentMonth.value)
})

const currentYearLabel = computed(() => String(currentMonth.value.getFullYear()))
const currentCalendarTitle = computed(() => {
  return calendarView.value === 'year' ? currentYearLabel.value : currentMonthLabel.value
})

const todayKey = computed(() => formatDateKey(new Date()))

const calendarDays = computed(() => createMonthGrid(currentMonth.value))
function buildYearMonthCards(year) {
  return monthOptions.map((monthLabel, monthIndex) => {
    const monthDate = new Date(year, monthIndex, 1)

    return {
      key: `${year}-${monthIndex}`,
      year,
      monthIndex,
      monthLabel,
      days: createMonthGrid(monthDate),
    }
  })
}

const yearViewYears = computed(() => {
  return Array.from({ length: YEAR_VIEW_END - YEAR_VIEW_START + 1 }, (_, index) => {
    const year = YEAR_VIEW_START + index

    return {
      key: String(year),
      year,
      months: buildYearMonthCards(year),
    }
  })
})

const selectedDateEvents = computed(() => {
  if (!selectedDate.value) {
    return []
  }

  return eventsByDate.value[selectedDate.value] || []
})

const selectedDateLabel = computed(() => formatFullDate(selectedDate.value))

function openCreateForm(date = '') {
  editingEventId.value = null
  newEvent.value = createEmptyEvent(date)
  showEventDetails.value = false
  showForm.value = true
}

function closeForm() {
  showForm.value = false
  editingEventId.value = null
  eventEditSnapshot.value = null
  newEvent.value = createEmptyEvent()
}

function openEventDetails(dateKey) {
  selectedDate.value = dateKey
  showEventDetails.value = true
}

function closeEventDetails() {
  showEventDetails.value = false
}

function switchCalendarView(view) {
  calendarView.value = view

  if (view === 'year') {
    centerYearScroll()
  }
}

function handleDateClick(day) {
  ensureMonthForDate(day.dateKey)
  selectedDate.value = day.dateKey

  if (day.events.length) {
    openEventDetails(day.dateKey)
    return
  }

  openCreateForm(day.dateKey)
}

function openMonthFromYear(year, monthIndex, dateKey = '') {
  setCurrentMonth(year, monthIndex)
  calendarView.value = 'month'

  if (dateKey) {
    selectedDate.value = dateKey
  }
}

async function centerYearScroll() {
  await nextTick()

  const container = yearScrollContainer.value

  if (!container) {
    return
  }

  const targetSection = container.querySelector(`[data-year="${currentMonth.value.getFullYear()}"]`)

  if (!targetSection) {
    return
  }

  syncingYearScroll = true
  const previousScrollBehavior = container.style.scrollBehavior
  container.style.scrollBehavior = 'auto'
  container.scrollTop = targetSection.offsetTop

  requestAnimationFrame(() => {
    container.style.scrollBehavior = previousScrollBehavior
    syncingYearScroll = false
  })
}

function handleYearViewScroll() {
  if (calendarView.value !== 'year' || syncingYearScroll) {
    return
  }

  const container = yearScrollContainer.value

  if (!container) {
    return
  }

  if (yearScrollFrameId) {
    cancelAnimationFrame(yearScrollFrameId)
  }

  yearScrollFrameId = requestAnimationFrame(() => {
    const sections = Array.from(container.querySelectorAll('.year-section'))

    if (!sections.length) {
      return
    }

    const containerTop = container.getBoundingClientRect().top
    const targetLine = containerTop + 60

    let closestSection = sections[0]
    let closestDistance = Math.abs(closestSection.getBoundingClientRect().top - targetLine)

    sections.forEach((section) => {
      const distance = Math.abs(section.getBoundingClientRect().top - targetLine)

      if (distance < closestDistance) {
        closestSection = section
        closestDistance = distance
      }
    })

    const visibleYear = Number(closestSection.dataset.year)

    if (Number.isFinite(visibleYear) && visibleYear !== currentMonth.value.getFullYear()) {
      currentMonth.value = new Date(visibleYear, currentMonth.value.getMonth(), 1)
    }
  })
}

async function addEvent() {
  const isUpdate = Boolean(editingEventId.value)

  if (isUpdate) {
    const shouldUpdate = await openConfirmDialog({
      title: 'Confirm update',
      message: 'Save these changes to this event?',
      confirmLabel: 'Update',
      cancelLabel: 'Undo',
    })

    if (!shouldUpdate) {
      if (eventEditSnapshot.value) {
        newEvent.value = { ...eventEditSnapshot.value }
      }
      return
    }
  }

  const payload ={
    ...newEvent.value,
    profile_id: Number(route.params.id),
  }

  if (isUpdate) {
    await axios.put(`${apiBaseUrl}/networking-events/${editingEventId.value}`, payload)
  } else {
    await axios.post(`${apiBaseUrl}/networking-events`, payload)
  }

  const savedDate = newEvent.value.date

  await fetchEvents()
  closeForm()

  if (savedDate) {
    selectedDate.value = savedDate
    ensureMonthForDate(savedDate)
    openEventDetails(savedDate)
  }
}

function editEvent(event) {
  editingEventId.value = event.event_id
  eventEditSnapshot.value = {
    name: event.event_name,
    date: normalizeEventDate(event.event_datetime),
    location: event.location,
    details: event.details,
    contact_ids: event.contacts ? event.contacts.map(contact => contact.contact_id) : [],
  }
  newEvent.value = { ...eventEditSnapshot.value }
  showEventDetails.value = false
  showForm.value = true
}

async function deleteEvent(id) {
  const shouldDelete = await openConfirmDialog({
    title: 'Confirm delete',
    message: 'Delete this event? This action cannot be undone.',
    confirmLabel: 'Delete',
    cancelLabel: 'Keep',
    variant: 'danger',
  })

  if (!shouldDelete) {
    return
  }

  await axios.delete(`${apiBaseUrl}/networking-events/${id}`)
  await fetchEvents()

  if (selectedDate.value && !selectedDateEvents.value.length) {
    closeEventDetails()
  }
}

function editQuestion(eventId, question) {
  questionDrafts.value[eventId] = question.question_text
  editingQuestionIds.value[eventId] = question.question_id
  questionEditSnapshots.value[eventId] = question.question_text
}

async function submitQuestion(eventId) {
  const questionText = getQuestionDraft(eventId).trim()
  const editingId = editingQuestionIds.value[eventId]

  if (!questionText) {
    return
  }

  if (editingId) {
    const shouldUpdate = await openConfirmDialog({
      title: 'Confirm update',
      message: 'Save these changes to this question?',
      confirmLabel: 'Update',
      cancelLabel: 'Undo',
    })

    if (!shouldUpdate) {
      questionDrafts.value[eventId] = questionEditSnapshots.value[eventId] || ''
      return
    }

    await axios.put(`${apiBaseUrl}/questions/${editingId}`, {
      question: questionText,
    })
  } else {
    await axios.post(`${apiBaseUrl}/networking-events/${eventId}/questions`, {
      question: questionText,
    })
  }

  await fetchEvents()
  clearQuestionEditor(eventId)
}

async function deleteQuestion(eventId, questionId) {
  const shouldDelete = await openConfirmDialog({
    title: 'Confirm delete',
    message: 'Delete this question from the event?',
    confirmLabel: 'Delete',
    cancelLabel: 'Keep',
    variant: 'danger',
  })

  if (!shouldDelete) {
    return
  }

  await axios.delete(`${apiBaseUrl}/questions/${questionId}`)
  await fetchEvents()

  if (editingQuestionIds.value[eventId] === questionId) {
    clearQuestionEditor(eventId)
  }
}

function editComment(eventId, comment) {
  const draft = {
    comment_type: comment.comment_type || '',
    link_url: comment.link_url || '',
    file: null,
    file_name: comment.file_name || '',
  }
  commentDrafts.value[eventId] = {...draft}
  editingCommentIds.value[eventId] = comment.id
  commentEditSnapshots.value[eventId] = {...draft}
}

async function handleCommentFileChange(eventId, event) {
  const draft = getCommentDraft(eventId)
  const file = event.target.files?.[0] || null

  draft.file = file
  draft.file_name = file ? file.name : ''

  if(!file) return

  try {
    await submitComment(eventId)
  } finally {
    event.target.value = ''
  }
}

async function submitComment(eventId) {
  const draft = getCommentDraft(eventId)
  const editingId = editingCommentIds.value[eventId]
  if(!draft.comment_type) {
    return
  }
  if(draft.comment_type === 'link' && !draft.link_url.trim()) {
    return
  }
  if((draft.comment_type === 'image' || draft.comment_type === 'video') && !draft.file && !draft.file_name){
    return
  }
  if(editingId){
    const shouldUpdate = await openConfirmDialog({
      title: 'Confirm update',
      message: 'Save these changes to this comment?',
      confirmLabel: 'Update',
      cancelLabel: 'Undo',
    })
  if (!shouldUpdate){
    commentDrafts.value[eventId] = commentEditSnapshots.value[eventId]?{...commentEditSnapshots.value[eventId]}:createEmptyCommentDraft()
    return
    }
  }

  const formData = new FormData()
  formData.append('comment_type', draft.comment_type)

  if((draft.comment_type === 'link')){
    formData.append('link_url', draft.link_url.trim())
  }
  if((draft.comment_type === 'image' || draft.comment_type === 'video') && draft.file) {
    formData.append('file', draft.file)
  }

  if(editingId){
    await axios.post(`${apiBaseUrl}/comments/${editingId}?_method=PUT`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
  } else {
    await axios.post(`${apiBaseUrl}/networking-events/${eventId}/comments`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
  }
  await fetchEvents()
  clearCommentEditor(eventId)
}

async function deleteComment(eventId, commentId) {
  const shouldDelete = await openConfirmDialog({
    title: 'Confirm delete',
    message: 'Delete this comment from the event?',
    confirmLabel: 'Delete',
    cancelLabel: 'Keep',
    variant: 'danger',
  })

  if (!shouldDelete) {
    return
  }

  await axios.delete(`${apiBaseUrl}/comments/${commentId}`)
  await fetchEvents()

  if (editingCommentIds.value[eventId] === commentId) {
    clearCommentEditor(eventId)
  }
}

function goToPreviousMonth() {
  if (calendarView.value === 'year') {
    currentMonth.value = new Date(currentMonth.value.getFullYear() - 1, 0, 1)
    centerYearScroll()
    return
  }

  currentMonth.value = new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth() - 1, 1)
}

function goToNextMonth() {
  if (calendarView.value === 'year') {
    currentMonth.value = new Date(currentMonth.value.getFullYear() + 1, 0, 1)
    centerYearScroll()
    return
  }

  currentMonth.value = new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth() + 1, 1)
}

function goToToday() {
  currentMonth.value = startOfMonth(new Date())

  if (calendarView.value === 'year') {
    centerYearScroll()
  }
}

</script>

<style scoped>
.networking-page {
  min-height: 100vh;
  background: #ffffff;
  font-family: 'Maven Pro', sans-serif;
}

.networking-shell {
  max-width: 78rem;
  margin: 0 auto;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  gap: 1.5rem;
  align-items: end;
  margin-bottom: 1.5rem;
}

.page-header > .action-button{
  flex: 0 0 auto;
  width: auto;
  white-space: nowrap;
}
.eyebrow,
.toolbar-label,
.modal-label,
.event-date,
.detail-label {
  text-transform: uppercase;
  letter-spacing: 0.08em;
  font-size: 0.8rem;
  color: #587489;
  margin: 0 0 0.4rem;
}

.page-title,
.calendar-title,
.modal-header h2,
.event-detail-card h3 {
  font-family: 'Maven Pro', sans-serif;
  margin: 0;
  color: #13202c;
}

.page-title {
  font-size: clamp(2rem, 3vw, 3.2rem);
  line-height: 1.05;
}

.page-copy {
  max-width: 36rem;
  margin: 0.8rem 0 0;
  color: #4e6577;
  line-height: 1.6;
  font-size: 1rem;
}

.calendar-card,
.modal-card {
  background: rgba(255, 255, 255, 0.94);
  border: 1px solid rgba(150, 168, 184, 0.28);
  border-radius: 1.75rem;
  box-shadow: 0 1.2rem 3rem rgba(22, 34, 51, 0.08);
  backdrop-filter: blur(0.4rem);
}

.calendar-card {
  padding: 1.5rem;
}

.calendar-toolbar,
.calendar-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
}

.calendar-toolbar {
  margin-bottom: 1.25rem;
}

.view-switcher,
.calendar-controls,
.modal-actions,
.card-actions,
.inline-actions,
.list-actions {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.calendar-scroll {
  overflow-x: auto;
}

.view-switcher {
  background: #f5f6f8;
  padding: 0.3rem;
  border-radius: 999px;
  border: 1px solid #dde5ec;
}

.view-toggle {
  border: 0;
  background: transparent;
  color: #526979;
  font: inherit;
  padding: 0.7rem 1.2rem;
  border-radius: 999px;
  cursor: pointer;
  transition: background-color 0.18s ease, color 0.18s ease;
}

.view-toggle.is-active {
  background: #ffffff;
  color: #13202c;
  box-shadow: 0 0.35rem 1rem rgba(19, 32, 44, 0.08);
}

.calendar-grid {
  display: grid;
  grid-template-columns: repeat(7, minmax(9rem, 1fr));
  min-width: 63rem;
  border-radius: 1.25rem;
  overflow: hidden;
  border: 1px solid #d6e0ea;
}

.year-view-shell {
  display: flex;
  flex-direction: column;
  gap: 0.9rem;
}

.year-scroll-hint {
  margin: 0;
  color: #7c91a2;
  font-size: 0.9rem;
}

.year-scroll-frame {
  max-height: calc(100vh - 12rem);
  min-height: 78vh;
  overflow-y: auto;
  padding-right: 0.35rem;
}

.year-section {
  padding-bottom: 1.6rem;
}

.year-section + .year-section {
  border-top: 1px solid #e6edf3;
  padding-top: 1.6rem;
}

.year-section-title {
  margin: 0 0 1rem;
  color: #13202c;
  font-family: 'Maven Pro', sans-serif;
  font-size: clamp(2rem, 3vw, 2.6rem);
}

.weekday-cell {
  padding: 1rem 0.75rem;
  background: #edf3f8;
  text-align: center;
  font-size: 0.9rem;
  color: #60788c;
  border-bottom: 1px solid #d6e0ea;
}

.year-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 1.5rem;
}

.year-card {
  background: #ffffff;
  border: 1px solid #e1e8ef;
  border-radius: 1.35rem;
  padding: 1rem;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.75);
}

.year-card-title {
  border: 0;
  background: transparent;
  color: #f05c48;
  font-family: 'Maven Pro', sans-serif;
  font-size: 1.2rem;
  padding: 0;
  margin-bottom: 0.8rem;
  cursor: pointer;
  text-align: left;
}

.mini-weekdays,
.mini-month-grid {
  display: grid;
  grid-template-columns: repeat(7, minmax(0, 1fr));
}

.mini-weekdays {
  margin-bottom: 0.35rem;
}

.mini-weekday {
  font-size: 0.72rem;
  text-align: center;
  color: #8ba0af;
}

.mini-day {
  border: 0;
  background: transparent;
  min-height: 2rem;
  padding: 0.2rem 0.1rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.12rem;
  cursor: pointer;
  color: #22313f;
  border-radius: 0.6rem;
  transition: background-color 0.18s ease, transform 0.18s ease;
}

.mini-day:hover {
  background: #f7fbff;
  transform: translateY(-1px);
}

.mini-day-number {
  font-size: 0.78rem;
  line-height: 1;
}

.mini-day.is-outside-month .mini-day-number {
  color: #b3c0ca;
}

.mini-day.is-today .mini-day-number {
  background: #ff4d4f;
  color: #ffffff;
  width: 1.45rem;
  height: 1.45rem;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.mini-event-dot {
  width: 0.32rem;
  height: 0.32rem;
  border-radius: 50%;
  background: #cf7de4;
}

.day-cell {
  min-height: 9.5rem;
  background: #ffffff;
  border: 0;
  border-right: 1px solid #d6e0ea;
  border-bottom: 1px solid #d6e0ea;
  padding: 0.9rem 0.85rem;
  text-align: left;
  display: flex;
  flex-direction: column;
  gap: 0.8rem;
  cursor: pointer;
  transition:
    transform 0.18s ease,
    background-color 0.18s ease,
    box-shadow 0.18s ease;
}

.day-cell:hover {
  background: #f7fbff;
  box-shadow: inset 0 0 0 1px #91cbe0;
}

.day-cell:nth-child(7n) {
  border-right: 0;
}

.day-cell-header {
  display: flex;
  justify-content: space-between;
  gap: 0.5rem;
  align-items: center;
}

.day-number {
  font-size: 1.05rem;
  color: #22313f;
}

.day-pill,
.event-chip,
.more-chip,
.ghost-button,
.action-button,
.delete-button,
.icon-button {
  border-radius: 999px;
  font-family: 'Montserrat Alternates', sans-serif;
}

.day-pill {
  background: #def4ea;
  color: #217a58;
  font-size: 0.7rem;
  padding: 0.22rem 0.55rem;
}

.event-chip-list {
  display: flex;
  flex-direction: column;
  gap: 0.45rem;
}

.event-chip,
.more-chip {
  display: block;
  max-width: 100%;
  padding: 0.35rem 0.6rem;
  font-size: 0.8rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.event-chip {
  background: #dbf1fb;
  color: #185a72;
}

.more-chip {
  background: #eff3f6;
  color: #60788c;
}

.has-events {
  background: linear-gradient(180deg, #ffffff 0%, #f8fdff 100%);
}

.is-outside-month {
  background: #f7f9fc;
}

.is-outside-month .day-number,
.is-outside-month .event-chip,
.is-outside-month .more-chip {
  opacity: 0.55;
}

.is-today .day-number {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 2.2rem;
  height: 2.2rem;
  background: #13202c;
  color: #ffffff;
  border-radius: 50%;
}

.action-button,
.ghost-button,
.delete-button,
.icon-button {
  border: 1px solid transparent;
  cursor: pointer;
  transition:
    transform 0.18s ease,
    background-color 0.18s ease,
    border-color 0.18s ease;
}

.action-button:hover,
.ghost-button:hover,
.delete-button:hover,
.icon-button:hover {
  transform: translateY(-1px);
}

.action-button {
  background: #13202c;
  color: #ffffff;
  padding: 0.85rem 1.4rem;
}

.ghost-button,
.icon-button {
  background: #f5f8fb;
  border-color: #d4dfe9;
  color: #2d4658;
  padding: 0.8rem 1.2rem;
}

.delete-button {
  background: #fff1f1;
  border-color: #f3c6c6;
  color: #a63f3f;
  padding: 0.8rem 1.2rem;
}

.small-button {
  padding: 0.55rem 0.95rem;
  font-size: 0.85rem;
}

.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(9, 17, 28, 0.48);
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1.2rem;
  z-index: 1000;
}

.confirm-overlay {
  position: fixed;
  inset: 0;
  background: rgba(9, 17, 28, 0.28);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  z-index: 1100;
}

.confirm-widget {
  width: min(28rem, 100%);
  background: rgba(255, 255, 255, 0.98);
  border: 1px solid #d6e0ea;
  border-radius: 1.15rem;
  box-shadow: 0 1rem 2.5rem rgba(18, 30, 45, 0.18);
  padding: 1.25rem;
}

.confirm-title {
  margin: 0 0 0.45rem;
  color: #13202c;
  font-size: 1.05rem;
  font-weight: 700;
}

.confirm-message {
  margin: 0;
  color: #4e6577;
  line-height: 1.5;
}

.confirm-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  margin-top: 1rem;
}

.modal-card {
  width: min(46rem, 100%);
  max-height: 90vh;
  overflow: auto;
  padding: 1.5rem;
}

.details-modal {
  width: min(66rem, 100%);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
  margin-bottom: 1.25rem;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 1rem;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 0.45rem;
  color: #2d4658;
}

.field span {
  font-size: 0.92rem;
}

.field input,
.field textarea,
.inline-editor input,
.inline-editor textarea {
  border: 1px solid #ccd8e2;
  border-radius: 1rem;
  padding: 0.9rem 1rem;
  font: inherit;
  background: #fbfdff;
}

.field-full {
  grid-column: 1 / -1;
}

.modal-actions {
  justify-content: flex-end;
  margin-top: 1.4rem;
}

.details-stack {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.event-detail-card {
  border: 1px solid #d6e0ea;
  border-radius: 1.3rem;
  padding: 1.25rem;
  background: #fbfdff;
}

.event-detail-top {
  display: flex;
  justify-content: space-between;
  gap: 1rem;
  align-items: flex-start;
  margin-bottom: 1rem;
}

.detail-row p,
.detail-panel p,
.detail-panel li {
  color: #4e6577;
  line-height: 1.6;
}

.detail-row {
  margin-bottom: 1rem;
}

.detail-row p {
  margin: 0;
}

.detail-columns {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 1rem;
}

.detail-panel {
  border: 1px solid #dce5ed;
  border-radius: 1rem;
  background: #ffffff;
  padding: 1rem;
}

.panel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  margin-bottom: 0.8rem;
}

.detail-panel h4 {
  margin: 0;
  color: #13202c;
}

.panel-count {
  min-width: 2rem;
  height: 2rem;
  border-radius: 50%;
  background: #edf3f8;
  color: #2d4658;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.85rem;
}

.inline-editor {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  margin-bottom: 1rem;
}

.item-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.list-item {
  border: 1px solid #dce5ed;
  border-radius: 0.95rem;
  padding: 0.9rem 1rem;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
}

.empty-state {
  text-align: center;
  padding: 2rem 1rem;
  color: #4e6577;
}

.empty-state h3 {
  margin-bottom: 0.6rem;
  color: #13202c;
}

@media (max-width: 1100px) {
  .calendar-toolbar,
  .calendar-actions {
    flex-direction: column;
    align-items: stretch;
  }

  .year-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 900px) {
  .page-header,
  .event-detail-top,
  .list-item {
    flex-direction: column;
    align-items: stretch;
  }

  .calendar-controls,
  .card-actions,
  .modal-actions,
  .inline-actions,
  .list-actions {
    justify-content: stretch;
  }

  .calendar-controls button,
  .card-actions button,
  .modal-actions button,
  .inline-actions button,
  .list-actions button,
  .action-button {
    width: 100%;
  }

  .confirm-actions {
    flex-direction: column;
  }

  .confirm-actions button {
    width: 100%;
  }

  .detail-columns,
  .form-grid {
    grid-template-columns: 1fr;
  }

  .networking-page {
    background: #ffffff;
  }
}

@media (max-width: 640px) {
  .calendar-card,
  .modal-card {
    padding: 1rem;
    border-radius: 1.25rem;
  }

  .day-cell {
    min-height: 8.4rem;
    padding: 0.7rem;
  }

  .event-chip,
  .more-chip {
    font-size: 0.75rem;
  }

  .view-switcher,
  .calendar-controls {
    width: 100%;
  }

  .view-toggle {
    flex: 1;
  }

  .year-grid {
    grid-template-columns: 1fr;
  }

  .year-scroll-frame {
    max-height: calc(100vh - 10rem);
    min-height: 70vh;
  }
}

.contact-list {
  list-style: none;
  margin: 0.6rem 0 0;
  padding: 0;
  color: #4e6577;
}

.contact-list-item {
  display: flex;
  flex-wrap: wrap;
  gap: 0.4rem;
  align-items: baseline;
  padding: 0.45rem 0;
  border-bottom: 1px solid #e7eef5;
}

.contact-list-item:last-child {
  border-bottom: 0;
}

.contact-name {
  font-weight: 600;
  color: #243746;
}

.contact-company {
  color: #6b8293;
}

.contact-company::before {
  content: "\2014";
  margin-right: 0.35rem;
}

.contact-empty {
  color: #6b8293;
  font-style: italic;
}

.networking-switch {
  display: inline-flex;
  gap: 0.75rem;
  margin-top: 1rem;
}

.switch-pill {
  padding: 0.6rem 1rem;
  border-radius: 999px;
  border: 1px solid #d6e0ea;
  text-decoration: none;
  color: #4e6577;
  background: #fff;
  font-size: 0.95rem;
}

.switch-pill.active {
  background:#172334;
  color: #fff;
  border-color: #172334;
}

.pitch-box {
  background: #fff;
  border: 1px solid #d9e0e7;
  border-radius: 12px;
  padding: 16px;
  margin-bottom: 20px;
  width: 100%;
  box-sizing: border-box;

}

.pitch-label {
  display: block;
  font-weight: 700;
  margin-bottom: 8px;
  color: #24364b;
}

.pitch-textarea{
  width: 100%;
  min-height: 120px;
  border: 1px solid #cfd8e3;
  border-radius: 10px;
  padding: 12px;
  resize: vertical;
  font-size: 1rem;
  line-height: 1.5;
}

.pitch-actions{
  display: flex;
  justify-content: flex-end;
  margin-top: 10px;
}
.confirm-overlay {
  position: fixed;
  inset: 0;
  background: rgba(9, 17, 28, 0.48);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  z-index: 1100;
}

.confirm-widget {
  width: min(28rem, 100%);
  background: rgba(255, 255, 255, 0.98);
  border: 1px solid #d6e0ea;
  border-radius: 1.15rem;
  box-shadow: 0 1rem 2.5rem rgba(18, 30, 45, 0.18);
  padding: 1.25rem;
}

.confirm-title {
  margin: 0 0 0.45rem;
  color: #13202c;
  font-size: 1.05rem;
  font-weight: 700;
}

.comment-evidence-grid{
  display: grid;
  grid-template-columns: minmax(180px, 240px) minmax(0, 1fr);
  gap: 1.25rem 1.5rem;
  align-items: start;
}

.comment-evidence-field{
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.comment-evidence-field select{
  border: 1px solid #ccd8e2;
  border-radius: 1rem;
  padding: 0.9rem 1rem;
  font: inherit;
  background: #ffffff;
  color: #13202c;
}

.comment-evidence-input input[disabled] {
  border: 1px solid #ccd8e2;
  border-radius: 1.25rem;
  padding: 1rem 1.2rem;
  background: #fbfdff;
  color: #7a8fa3;
}

.upload-zone {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.upload-input {
  display: none;
}

.upload-card {
  min-height: 150px;
  border: 3px dashed #d6d6d6;
  border-radius: 1.4rem;
  background: #ffffff;
  color: #50606f;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.9rem;
  text-align: center;
  padding: 1.25rem;
  cursor: pointer;
  transition: transform 0.18s ease, background-color 0.18s ease, border-color 0.18s ease;
}


.upload-card:hover {
  transform: translateY(-1px);
  background: #fafafa;
  border-color: #c8c8c8;
}

.upload-card strong {
  color: #4c555d;
  font-size: 1.05rem;
}

.upload-card span {
  color: #5f6a73;
  font-size: 0.95rem;
}

.upload-note {
  margin: 0;
  color: #5a738a;
  font-size: 0.95rem;
}

.upload-file-name {
  margin: 0;
  color: #13202c;
  font-weight: 600;
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

.field-input.form-control.field-error {
  border-color: #db7979;
  background: #fff5f5;
  box-shadow: #db7979;
}

.error-message {
  color:  #db7979;
}
</style>
