<template>
  <div v-if="show" class="view-popup" @click.self="$emit('close')">
    <div class="view-popup-box">

      <!-- viewing mode-->
      <template v-if="!editing">
        <!-- header with back, title, delete/edit -->
        <div class="d-flex align-items-center justify-content-between border-bottom p-3">
          <img class="plus-btn" src="@/assets/back.png" @click="$emit('close')">

          <h2 class="text-center view-title mb-0">{{ reflec.title }}</h2>
          <div class="d-flex gap-2">
            <img class="plus-btn" src="@/assets/del.png" @click="showDeleteConfirm = true" title="Delete">
            <img class="plus-btn" src="@/assets/edit.png" @click="enterEdit" title="Edit">
          </div>
        </div>

        <!-- competency, level, year-->
        <div class="d-flex justify-content-center gap-2 pt-3 pb-2">
          <span class="pill-tag">Competency {{ compt.id }}</span>
          <span class="pill-tag">{{ reflec.year===0 ? 'PRIOR':'YEAR ' + reflec.year }}</span>
          <span class="pill-tag">{{ reflec.level }}</span>
        </div>
        <!-- date range-->
        <p class="text-center date-txt pb-2">{{ reflec.startDate }} – {{ reflec.endDate}}</p>

        <div class="view-popup-scroll px-4 py-3 d-flex flex-column gap-4">
          <!-- experience & tasks -->
          <div>
            <p class="section-label">Experience & tasks:</p>
            <p class="body-txt">{{ reflec.tasks}}</p>
          </div>

          <!-- key learnings -->
          <div>
            <p class="section-label">Key learnings:</p>
            <p class="body-txt">{{ reflec.learnings}}</p>
          </div>

          <!-- future application -->
          <div>
            <p class="section-label">Future Application:</p>
            <p class="body-txt">{{ reflec.future}}</p>
          </div>

          <!-- evidence-->
          <div>
            <p class="section-label">Evidence:</p>
            <div v-for="(ev, i) in reflec.evidenceEntries.filter(e=> e.value || e.fileName)" :key="i"
            class="d-flex align-items-center gap-3 mb-2">

              <span class="ev-label">{{ evLabel(ev.type) }}:</span>
              <span class="evidence-pill">
                <a v-if="ev.type==='url'" :href="ev.value">{{ ev.value }}</a>
                <span v-else>{{ ev.fileName || ev.value }}</span>
              </span>
            </div>
          </div>

          <!-- feedback received -->
          <div>
            <p class="section-label">Feedback Received:</p>
            <div v-if="reflec.feedback" class="feedback-received">
              <span class="feedback-author">{{ reflec.feedbackAuthor }} commented:</span>
              <p class="feedback-received-txt">{{ reflec.feedback }}</p>
            </div>
            <!-- no feedback yet-->
            <p v-else class="body-txt">No feedback received yet</p>
          </div>
        </div>

        <!-- footer -->
        <div class="d-flex justify-content-between align-items-center p-3 border-top date-txt">
          <span><u>Scroll to see full reflection</u></span>
          <span>Last updated on {{ reflec.date }}</span>
        </div>
      </template>

      <!--edit mode-->
      <template v-else>

        <!-- editable title in box-->
        <div class="border-bottom p-3">
          <input v-model="ef.title" class="form-control rounded-3 text-center edit-title-input"/>

          <!-- compt, level and year -->
          <div class="d-flex justify-content-center gap-2 mt-3">
            <select v-model="ef.comptId" class="pill-select">
              <option v-for="c in allCompts" :key="c.id" :value="c.id">Competency {{ c.id }}</option>
            </select>

            <select v-model="ef.year" class="pill-select">
              <option value="0">Prior to degree</option>
              <option value="1">Year 1</option>
              <option value="2">Year 2</option>
              <option value="3">Year 3</option>
              <option value="4">Year 4</option>
            </select>

            <select v-model="ef.level" class="pill-select">
              <option>Emerging</option>
              <option>Developing</option>
              <option>Proficient</option>
              <option>Confident</option>
            </select>
          </div>

          <!-- date range-->
          <div class="d-flex justify-content-center align-items-center gap-2 mt-3">
            <input v-model="ef.startDate" type="date" class="form-control field-input rounded-3 text-center date-picker"/>
            <span class="body-txt">–</span>
            <input v-model="ef.endDate" type="date" class="form-control field-input rounded-3 text-center date-picker"/>
          </div>
        </div>

        <!-- scrollable edit body -->
        <div class="view-popup-scroll px-4 py-4 d-flex flex-column gap-4">

          <!-- experience & tasks -->
          <div>
            <label class="form-label field-label">Experience &amp; tasks</label>
            <textarea v-model="ef.tasks" class="form-control field-input rounded-3" rows="4"
              placeholder="Describe the experience and tasks you undertook"></textarea>
          </div>

          <!-- key learnings -->
          <div>
            <label class="form-label field-label">Key learnings</label>
            <textarea v-model="ef.learnings" class="form-control field-input rounded-3" rows="4"
              placeholder="What did you learn that was most valuable?"></textarea>
          </div>

          <!-- future application -->
          <div>
            <label class="form-label field-label">Future application</label>
            <textarea v-model="ef.future" class="form-control field-input rounded-3" rows="4"
              placeholder="How will you apply these learnings in the future?"></textarea>
          </div>

          <!-- editable evidence entries -->
          <div>
            <div v-for="(ev, idx) in ef.evidenceEntries" :key="idx" class="d-flex gap-3 align-items-end mb-3 pb-3" 
            :class="{ 'border-bottom': idx < ef.evidenceEntries.length-1 }">

              <!-- evidence type -->
              <div>
                <label class="form-label field-label mb-3">Evidence type</label>
                <select v-model="ev.type" class="form-select field-select rounded-3" @change="ev.value = ''; ev.fileName = ''">
                  <option value="">Select evidence type</option>
                  <option value="url">Link</option>
                  <option value="document">Document</option>
                  <option value="image">Image</option>
                  <option value="video">Video</option>
                </select>
              </div>

              <!-- evidence input -->
              <div>
                <label class="form-label field-label mb-3">Evidence input</label>

                <!-- nothing selected -->
                <input v-if="!ev.type" class="form-control field-input rounded-3"
                  disabled placeholder="Select a type first"/>

                <!-- link -->
                <input v-else-if="ev.type === 'url'" v-model="ev.value" type="url"
                  class="form-control field-input rounded-3" placeholder="https://example.com"/>

                <!-- file upload -->
                <div v-else>
                  <div class="upload-zone rounded-3 p-3" :class="{ 'upload-zone-filled': ev.fileName }">
                    <input type="file" :accept="fileAccept(ev.type)" class="position-absolute opacity-0" @change="e=> handleFile(e, ev)"/>

                    <div v-if="!ev.fileName">
                      <p><b>Click to upload or drag & drop</b></p>
                      <p class="mb-0">{{ uploadHint(ev.type) }}</p>
                    </div>

                    <div v-else class="d-flex align-items-center gap-2">
                      <span>{{ ev.fileName }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- remove evidence row -->
              <button v-if="ef.evidenceEntries.length>1" class="del-btn mb-1"
              @click="ef.evidenceEntries.splice(idx, 1)" title="Remove">
                <img src="@/assets/delete.png">
              </button>
            </div>

            <button class="btn btn-filter rounded-pill px-3 py-1"
            @click="ef.evidenceEntries.push({ type: '', value: '', fileName: '' })">+ Add another</button>
          </div>

        </div>

        <!-- edit footer -->
        <div class="d-flex justify-content-between align-items-center px-4 pb-4 pt-3 border-top">
          <span class="date-txt"><u>Scroll to see full reflection</u></span>
          <div class="d-flex gap-2">
            <button class="btn btn-filter" @click="saveAsDraft">Save as draft</button>
            <button class="btn btn-filter" @click="editing = false">Cancel</button>
            <button class="btn btn-add" @click="saveEdit">Done</button>
          </div>
        </div>
      </template>
    </div>
  </div>

  <!--delete confirm -->
  <div v-if="showDeleteConfirm" class="view-popup" @click.self="showDeleteConfirm = false">
    <div class="delete-box text-center p-4">

      <h5 class="fw-bold mb-2 field-label">Delete this reflection?</h5>
      <p class="field-desc mb-4">This action cannot be undone. The reflection will be permanently removed.</p>

      <div class="d-flex gap-2 justify-content-center">
        <button class="btn btn-filter" @click="showDeleteConfirm = false">Cancel</button>
        <button class="btn btn-add rounded-pill px-4" @click="doDelete">Delete</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { getAllCompts, todayStr } from '@/useCompetencies.js'

const props = defineProps({
  show: Boolean,
  reflec: Object,
  compt: Object,
  index: Number
})
const emit = defineEmits(['close', 'delete', 'save'])

// local state
const editing = ref(false)
const showDeleteConfirm = ref(false)
const allCompts = computed(()=> getAllCompts())

// edit form
const ef = ref({
  title: '',
  comptId: '',
  year: '',
  level: '',
  startDate: '',
  endDate: '',
  tasks: '',
  learnings: '',
  future: '',
  evidenceEntries: []
})

const hasEvidence = computed(() =>
  props.reflec.evidenceEntries.some(e => e.value || e.fileName)
)

// reset edit state when popup closes
watch(() => props.show, (v) => {
  if (!v) {
    editing.value = false
    showDeleteConfirm.value = false
  }
})

// evidence helpers
function evLabel(type) {
  switch (type) {
    case 'url':
      return 'URL'
    case 'document':
      return 'File'
    case 'image':
      return 'Image'
    case 'video':
      return 'Video'
    default:
      return type || 'File'
  }
}

function fileAccept(type) {
  switch (type) {
    case 'image':
      return 'image/*'
    case 'video':
      return 'video/*'
    case 'document':
      return '.pdf,.doc,.docx,.txt,.ppt,.pptx'
    default:
      return '*'
  }
}

function uploadHint(type) {
  switch (type) {
    case 'image':
      return 'PNG, JPG, JPEG, GIF'
    case 'video':
      return 'MP4, MOV'
    case 'document':
      return 'PDF, DOC, DOCX, TXT, PPT, PPTX'
    default:
      return ''
  }
}

function handleFile(e, ev) {
  const file = e.target.files[0]
  if (file) {
    ev.fileName = file.name
    ev.value = file.name
  }
}

// enter edit
function enterEdit() {
  ef.value = {
    title: props.reflec.title || '',
    comptId: props.compt.id || '',
    year: props.reflec.year ?? '',
    level: props.reflec.level ?? '',
    startDate: props.reflec.startDate || '',
    endDate: props.reflec.endDate || '',
    tasks: props.reflec.tasks || '',
    learnings: props.reflec.learnings || '',
    future: props.reflec.future || '',
    evidenceEntries: JSON.parse(JSON.stringify(props.reflec.evidenceEntries || []))
  }
  editing.value = true
}

function saveEdit(asDraft) {
  const keepAsDraft = asDraft === true
  emit('save', {
    index: props.index,
    updated: {
      title: ef.value.title,
      year: Number(ef.value.year),
      level: ef.value.level,
      startDate: ef.value.startDate,
      endDate: ef.value.endDate,
      tasks: ef.value.tasks,
      learnings: ef.value.learnings,
      future: ef.value.future,
      evidenceEntries: ef.value.evidenceEntries,
      date: todayStr(),
      isDraft: keepAsDraft
    }
  })
  editing.value = false
}

function saveAsDraft() {
  saveEdit(true)
  emit('close')
}

function doDelete() {
  emit('delete', props.index)
  showDeleteConfirm.value = false
}
</script>

<style scoped>
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

.view-popup-box {
  background: #ffffff;
  border-radius: 1.25rem;
  width: 100%;
  max-width: 45rem;
  max-height: 88vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 1.25rem 3.75rem rgba(0, 0, 0, 0.2);
}

.view-popup-scroll {
  overflow-y: auto;
  flex: 1;
}

.view-popup-scroll::-webkit-scrollbar {
  width: 0.375rem;
}

.view-popup-scroll::-webkit-scrollbar-thumb {
  background: #e0e0e0;
  border-radius: 2px;
}

.view-title {
  font-family: 'Martel', serif;
  font-size: 1.6rem;
  font-weight: 700;
  color: #2b2b2b;
}

.edit-title-input {
  font-family: 'Martel', serif;
  font-size: 1.5rem;
  font-weight: 700;
  color: #2b2b2b;
  border: 0.1rem solid #e0e0e0;
}

.edit-title-input:focus {
  border-color: #c4c4c4;
  box-shadow: 0 0 0 0.02rem #2b2b2b;
}

.pill-tag {
  border: 0.09rem solid #d0d0d0;
  border-radius: 999px;
  padding: 0.25rem 1rem;
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.8rem;
  color: #444444;
  background: #ffffff;
}

.pill-select {
  border: 0.09rem solid #d0d0d0;
  border-radius: 999px;
  padding: 0.25rem 1.5rem 0.25rem 0.875rem;
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.8rem;
  color: #444444;
  background: #ffffff;
  cursor: pointer;
}

.pill-select:focus {
  outline: none;
  border-color: #c4c4c4;
}

.date-txt {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.9rem;
  color: #888888;
}

.date-picker {
  width: 10rem;
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.8rem;
}

.section-label {
  font-family: 'Martel', sans-serif;
  font-size: 1rem;
  text-decoration: underline;
  color: #222222;
  margin-bottom: 0.5rem;
}

.body-txt {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.85rem;
  line-height: 1.75;
  color: #444444;
  text-align: justify;
  margin-bottom: 0;
}

.ev-label {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.8rem;
  font-weight: 500;
  color: #666666;
  min-width: 3rem;
}

.evidence-pill {
  border: 0.09rem solid #d8d8d8;
  border-radius: 0.5rem;
  padding: 0.375rem 0.875rem;
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.8rem;
  color: #555555;
  background: #fafafa;
}

.scroll-txt {
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.9rem;
  color: #555555;
}

.field-label {
  font-family: 'Martel', sans-serif;
  font-size: 1rem;
  color: #222222;
}

.field-desc {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.8rem;
  line-height: 1.5;
  color: #444444;
}

.field-input, .field-select {
  border: 0.1rem solid #e0e0e0;
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.8rem;
  border-radius: 0.5rem;
}

.field-input:focus, .field-select:focus {
  border-color: #c4c4c4;
  box-shadow: 0 0 0 0.02rem #2b2b2b;
}

.upload-zone {
  position: relative;
  max-height: 5rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border: 0.15rem dashed #d0d0d0;
  text-align: center;
  background: #fafafa;
  cursor: pointer;
}

.upload-zone p {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.8rem;
  color: #555555;
  margin-bottom: 0.2rem;
}

.upload-zone:hover {
  border-color: #88c2d2;
  background: #f0fafa;
}

.upload-zone-filled {
  border-style: solid;
  border-color: #88c2d2;
  background: #f0fafa;
}

.del-btn {
  width: 1.75rem;
  height: 1.75rem;
  border: none;
  background: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.del-btn>img {
  width: 1.5rem;
  height: 1.5rem;
  object-fit: contain;
}

.del-btn:hover {
  transform: scale(1.1);
}

.plus-btn {
  width: 2rem;
  height: 2rem;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.plus-btn:hover {
  transform: scale(1.1);
}

.delete-box {
  background: #ffffff;
  border-radius: 1.25rem;
  max-width: 22.5rem;
  width: 100%;
  box-shadow: 0 1.25rem 3.75rem rgba(0, 0, 0, 0.2);
}

.btn-filter {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  font-size: 1rem;
  background: #e6e6e6;
  color: #222222;
}

.btn-filter:hover {
  background: #666666;
  color: #ffffff;
}

.btn-add {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  font-size: 1rem;
  color: #ffffff;
  background: #555555;
}

.btn-add:hover {
  background: #333333;
  color: #ffffff;
}

.feedback-received {
  margin-top: 0.25rem;
  padding-left: 1rem;
}

.feedback-author {
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.9rem;
  font-weight: 600;
  color: #444444;
}
.feedback-received-txt {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.85rem;
  font-style: italic;
  color: #666666;
  line-height: 1.6;
  margin-bottom: 0;
}
</style>