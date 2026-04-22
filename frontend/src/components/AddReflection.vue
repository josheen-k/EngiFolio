<template>
  <div v-if="show" class="add-popup" @click.self="$emit('close')">
    <div class="add-popup-box">

      <h2 class="text-center fw-bold border-bottom p-3 add-title">Add a new reflection entry</h2>
      <div class="add-popup-scroll px-4 py-4 d-flex flex-column gap-4">

        <!-- competency name and desc-->
        <div class="row g-4">
          <div class="col-5">
            <label class="form-label field-label">Adding reflection for:</label>
            <select v-model="f.comptId" class="form-select field-select rounded-3">
              <option v-for="c in allCompts" :key="c.id" :value="c.id">Competency {{ c.id }}</option>
            </select>
          </div>
          <div class="col-7">
            <label class="form-label field-label">Description:</label>
            <p class="field-desc">{{ selectedCompt.desc }}</p>
          </div>
        </div>

        <!-- competency lvl and exp title-->
        <div class="row g-4">
          <div class="col-5">
            <label class="form-label field-label">Attainment level</label>
            <select v-model="f.level" class="form-select field-select rounded-3">
              <option>Emerging</option>
              <option>Developing</option>
              <option>Proficient</option>
              <option>Confident</option>
            </select>
          </div>
          <div class="col-7">
            <label class="form-label field-label">Experience title</label>
            <input v-model="f.title" class="form-control field-input rounded-3" placeholder="My experience"/>
          </div>
        </div>

        <!-- dates and associalted year -->
        <div class="row g-3">
          <div class="col-4">
            <label class="form-label field-label">Start date</label>
            <input v-model="f.startDate" type="date" class="form-control field-input rounded-3"/>
          </div>
          <div class="col-4">
            <label class="form-label field-label">End date</label>
            <input v-model="f.endDate" type="date" class="form-control field-input rounded-3"/>
          </div>
          <div class="col-4">
            <label class="form-label field-label">Associated year</label>
            <select v-model="f.year" class="form-select field-select rounded-3">
              <option value="0">Prior to degree</option>
              <option value="1">Year 1</option>
              <option value="2">Year 2</option>
              <option value="3">Year 3</option>
              <option value="4">Year 4</option>
            </select>
          </div>
        </div>

        <!-- exp and tasks textbox-->
        <div>
          <label class="form-label field-label">Experience & tasks</label>
          <textarea v-model="f.tasks" class="form-control field-input rounded-3" rows="4"
          placeholder="Describe the experience and tasks you undertook"></textarea>
        </div>

        <!-- key learnings textbox-->
        <div>
          <label class="form-label field-label">Key learnings</label>
          <textarea v-model="f.learnings" class="form-control field-input rounded-3" rows="4"
          placeholder="What did you learn that was most valuable?"></textarea>
        </div>

        <!-- future application textbox-->
        <div>
          <label class="form-label field-label">Future application</label>
          <textarea v-model="f.future" class="form-control field-input rounded-3" rows="4"
          placeholder="How will you apply these learnings in the future?"></textarea>
        </div>

        <!-- evidence entries-->
        <div>
          <div v-for="(ev, idx) in f.evidenceEntries" :key="idx" class="d-flex gap-3 align-items-end mb-3 pb-3"
          :class="{ 'border-bottom': idx < f.evidenceEntries.length-1 }">

            <!-- evidence type -->
            <div>
              <label class="form-label field-label mb-3">Evidence type</label>
              <select v-model="ev.type" class="form-select field-select rounded-3"
              @change="ev.value = ''; ev.fileName = ''">
                <option value="">Select evidence type</option>
                <option value="url">Link</option>
                <option value="document">Document</option>
                <option value="image">Image</option>
                <option value="video">Video</option>
              </select>
            </div>

            <!-- evidence input field -->
            <div class="flex-grow-1">
              <label class="form-label field-label mb-3">Evidence input</label>

              <!-- nothing selected-->
              <input v-if="!ev.type" class="form-control field-input rounded-3"
              disabled placeholder="Select a type first"/>

              <!-- if link selected-->
              <input v-else-if="ev.type==='url'" v-model="ev.value" type="url"
                class="form-control field-input rounded-3" placeholder="https://example.com"/>

              <!-- if file upload selected -->
              <div v-else>
                <div class="upload-zone rounded-3 p-3" :class="{ 'upload-zone-filled': ev.fileName }">

                  <input type="file" :accept="fileAccept(ev.type)" class="position-absolute w-100 h-100 opacity-0"
                  @change="e=> handleFile(e, ev)"/>

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

            <!-- remove evidence (need 1 field atleast)-->
            <button v-if="f.evidenceEntries.length>1" class="del-btn mb-1"
            @click="f.evidenceEntries.splice(idx, 1)" title="Remove">
              <img src="@/assets/delete.png">
            </button>
          </div>

          <button class="btn btn-filter rounded-pill px-3 py-1 mt-1"
          @click="f.evidenceEntries.push({ type: '', value: '', fileName: '' })">+ Add another</button>
        </div>
      </div>

      <!-- form footer actions -->
      <div class="d-flex justify-content-between align-items-center px-4 pb-4 pt-3 border-top">
        <span class="scroll-txt"><u>Scroll to see all fields</u></span>

        <div class="d-flex gap-2">
          <button class="btn btn-filter" @click="saveAsDraft">Save as draft</button>
          <button class="btn btn-filter" @click="$emit('close')">Cancel</button>
          <button class="btn btn-add" @click="submit">Done</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { getAllCompts, blankForm, todayStr } from '@/useCompetencies.js'

const props = defineProps({
  show: Boolean,
  initialComptId: { 
    type: String, 
    default: '' 
  }
})

const emit = defineEmits(['close', 'add'])
const allCompts = computed(()=>getAllCompts())

// form state
const f = ref(blankForm())

// when popup opens or initialComptId changes, reset and prefil
watch(()=> props.show, (v)=> {
  if (v) {
    f.value = blankForm(props.initialComptId)
  }
})
watch(()=> props.initialComptId, (id)=> {
  if (props.show) {
    f.value.comptId = id
  }
})

const selectedCompt = computed(()=>
  allCompts.value.find(c=> c.id===f.value.comptId)
)

// file evidence helpers
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
    ev.fileName = file.name; 
    ev.value = file.name 
  }
}

// submit form
function submit(asDraft = false) {
  emit('add', {
    comptId: f.value.comptId,
    reflec: {
      title: f.value.title || 'Untitled',
      year: Number(f.value.year),
      level: f.value.level,
      date: todayStr(),
      startDate: f.value.startDate,
      endDate: f.value.endDate,
      tasks: f.value.tasks,
      learnings: f.value.learnings,
      future: f.value.future,
      isDraft: asDraft,
      evidenceEntries: JSON.parse(JSON.stringify(f.value.evidenceEntries))
    }
  })
  emit('close')
}

function saveAsDraft() {
  submit(true)
}
</script>

<style scoped>
.add-popup {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.3);
  backdrop-filter: blur(0.375rem);
  z-index: 2;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.25rem;
}

.add-popup-box {
  background: #ffffff;
  border-radius: 1.25rem;
  width: 100%;
  max-width: 45rem;
  max-height: 88vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 1.25rem 3.75rem rgba(0, 0, 0, 0.2);
}

.add-popup-scroll {
  overflow-y: auto;
}

.add-popup-scroll::-webkit-scrollbar {
  width: 0.375rem;
}

.add-popup-scroll::-webkit-scrollbar-thumb {
  background: #e0e0e0;
  border-radius: 2px;
}

.add-title {
  font-family: 'Martel', serif;
  font-size: 1.6rem;
  color: #2b2b2b;
}

.field-label {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.8rem;
  font-weight: 500;
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

.scroll-txt {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.9rem;
  color: #888888;
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
</style>