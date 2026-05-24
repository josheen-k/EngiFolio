<template>
  <div v-if="show" class="view-popup" @click.self="$emit('close')">
    <div class="view-popup-box">

      <!-- viewing mode-->
      <template v-if="!editing">
        <!-- header with back, title, delete/edit -->
        <div class="d-flex align-items-center justify-content-between border-bottom p-3">
          <img class="plus-btn" src="@/assets/back.png" @click="$emit('close')">

          <h2 class="text-center view-title mb-0">{{ reflec?.experience_title }}</h2>
          <div class="d-flex gap-2">
            <img class="plus-btn" src="@/assets/del.png" @click="showDeleteConfirm = true" title="Delete">
            <img class="plus-btn" src="@/assets/edit.png" @click="enterEdit" title="Edit">
          </div>
        </div>

        <!-- competency, level, year-->
        <div class="d-flex justify-content-center gap-2 pt-3 pb-2">
          <span class="pill-tag">Competency {{ compt?.displayId }}</span>
          <span class="pill-tag">{{ reflec?.associated_year === 0 ? 'PRIOR':'YEAR ' + reflec?.associated_year }}</span>
          <span class="pill-tag">{{ reflec?.entry_level?.competency_level }}</span>
        </div>
        <!-- date range-->
        <p class="text-center date-txt pb-2">{{ reflec?.start_date }} <span v-if="reflec?.end_date">–</span> {{ reflec?.end_date}}</p>

        <div class="view-popup-scroll px-4 py-3 d-flex flex-column gap-4">
          <!-- experience & tasks -->
          <div>
            <p class="section-label">Experience & tasks:</p>
            <p class="body-txt">{{ reflec?.experience_tasks}}</p>
          </div>

          <!-- key learnings -->
          <div>
            <p class="section-label">Key learnings:</p>
            <p class="body-txt">{{ reflec?.key_learnings}}</p>
          </div>

          <!-- future application -->
          <div>
            <p class="section-label">Future Application:</p>
            <p class="body-txt">{{ reflec?.future_applications}}</p>
          </div>

          <!-- evidence-->
         <div>
            <p class="section-label">Evidence:</p>
            <div v-if="reflec?.evidence?.length">
              <div v-for="(ev, i) in reflec.evidence" :key="i"
                class="d-flex align-items-center gap-3 mb-2">
                <span class="ev-label">{{ evLabel(ev.evidence_type) }}:</span>
                <span class="evidence-pill">
                  <a v-if="ev.evidence_type === 'url'" :href="ev.evidence_value" target="_blank">
                    {{ ev.evidence_value }}
                  </a>
                  <span v-else>{{ ev.evidence_value }}</span>
                </span>
              </div>
            </div>
            <p v-else class="body-txt">No evidence added yet</p>
          </div>

          <!-- feedback received -->
          <div>
          <p class="section-label">Feedback Received:</p>
          
          <div v-if="reflec?.feedback?.length" class="d-flex flex-column gap-3">
            <div v-for="(fb, i) in reflec.feedback" :key="i" class="feedback-received">
              <span class="feedback-author">{{ fb.staff.first_name }} {{ fb.staff.last_name }} commented:</span>
              <p class="feedback-received-txt">{{ fb.feedback_content }}</p>
              <span class="date-txt">{{ new Date(fb.created_at).toLocaleDateString() }}</span>
            </div>
          </div>
          <p v-else class="body-txt">No feedback received yet</p>
        </div>
        </div>

        <!-- footer -->
        <div class="d-flex justify-content-between align-items-center p-3 border-top date-txt">
          <span><u>Scroll to see full reflection</u></span>
          <span>
            Last updated on {{ reflec?.updated_at ? new Date(reflec.updated_at).toLocaleDateString() : 'Unknown' }}
          </span>
        </div>
      </template>

      <!--edit mode-->
      <template v-else>

        <!-- editable title in box-->
        <div class="border-bottom p-3">
          <label v-if="errors.title" class="field-label error-message">*Title cannot be empty</label>
          <input v-model.trim="ef.experience_title" maxlength="50" class="form-control rounded-3 text-center edit-title-input"
           :class="{ 'field-error': errors.title }" @input="delete errors.title" />

          <!-- competency name and desc-->
          <div class="row g-4">
            <div class="col-5">
              <label class="form-label field-label">Adding reflection for:</label>
              <div class="form-control field-input rounded-3 bg-light border-0 fw-bold">
                Competency {{ compt?.displayId }}
              </div>
            </div>
            <div class="col-7">
              <label class="form-label field-label">Description:</label>
              <p class="field-desc">{{ compt?.description }}</p>
            </div>
          </div>

          <!-- level and year -->
          <div class="d-flex justify-content-center gap-2 mt-3">
            <select v-model="ef.associated_year" class="pill-select">
              <option value="0">Prior to degree</option>
              <option value="1">Year 1</option>
              <option value="2">Year 2</option>
              <option value="3">Year 3</option>
              <option value="4">Year 4</option>
            </select>

            <select v-model="ef.entry_level_id" class="pill-select">
              <option v-for="opt in levelOptions" :key="opt.value" :value="opt.value">
                {{ opt.label }}
              </option>
            </select>
          </div>

          <!-- date range-->
          <label v-if="errors.startDate" class="field-label error-message">*Invalid start date</label>
          <div class="d-flex justify-content-center align-items-center gap-2 mt-3">
            <input v-model="ef.start_date" type="date" class="form-control field-input rounded-3 text-center date-picker"
              :class="{ 'field-error': errors.startDate }" @input="delete errors.startDate"/>
            <span class="body-txt">–</span>
            <input v-model="ef.end_date" type="date" class="form-control field-input rounded-3 text-center date-picker"/>
          </div>
        </div>

        <!-- scrollable edit body -->
        <div class="view-popup-scroll px-4 py-4 d-flex flex-column gap-4">

          <!-- experience & tasks -->
          <div>
            <div class="d-flex justify-content-between align-items-center">  
              <label class="form-label field-label">Experience &amp; tasks: (Max 500 characters)</label>
              <label v-if="errors.tasks" class="field-label error-message">*Experience & tasks cannot be empty</label>
            </div>
            <textarea v-model.trim="ef.experience_tasks" maxlength="500" class="form-control field-input rounded-3" rows="4"
              :class="{ 'field-error': errors.tasks }" @input="delete errors.tasks"  
              placeholder="Describe the experience and tasks you undertook"></textarea>
          </div>

          <!-- key learnings -->
          <div>
            <label class="form-label field-label">Key learnings: (Max 500 characters)</label>
            <textarea v-model.trim="ef.key_learnings" maxlength="500" class="form-control field-input rounded-3" rows="4"
              placeholder="What did you learn that was most valuable?"></textarea>
          </div>

          <!-- future application -->
          <div>
            <label class="form-label field-label">Future application: (Max 500 characters)</label>
            <textarea v-model.trim="ef.future_applications" maxlength="500" class="form-control field-input rounded-3" rows="4"
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
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center"> 
                  <label class="form-label field-label mb-3">Evidence input</label>
                  <label v-if="errors[`evidenceURL_${idx}`]" class="field-label error-message">*Invalid evidence URL</label>
                </div> 

                <!-- nothing selected -->
                <input v-if="!ev.type" class="form-control field-input rounded-3"
                  disabled placeholder="Select a type first"/>

                <!-- link -->
                <input v-else-if="ev.type === 'url'" v-model="ev.value" type="url"
                  class="form-control field-input rounded-3" 
                  :class="{ 'field-error': errors[`evidenceURL_${idx}`] }" @input="delete errors[`evidenceURL_${idx}`]"
                  placeholder="https://example.com"/>

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

            <button  v-if="ef.evidenceEntries.length < 3"
            class="btn btn-filter rounded-pill px-3 py-1" 
            @click="addEvidence()">+ Add evidence</button>
          </div>
        </div>

        <!-- edit footer -->
        <div class="d-flex justify-content-between align-items-center px-4 pb-4 pt-3 border-top">
          <span class="date-txt"><u>Scroll to see full reflection</u></span>
          <div class="d-flex gap-2">
            <button class="btn btn-filter" @click="saveAsDraft">Save as draft</button>
            <button class="btn btn-filter" @click="handleCancel">Cancel</button>
            <button class="btn btn-add" @click="saveEdit">Done</button>
          </div>
        </div>
        <div v-if="popUp.show" class="popUp-msg" :class="popUp.type">
          {{ popUp.message }}
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

  <div v-if="showCancelConfirm" class="view-popup" @click.self="showCancelConfirm = false">
    <div class="delete-box text-center p-4">
      <h5 class="fw-bold mb-2 cancel-title">Cancel editing?</h5>
      <p class="field-desc mb-4">All changes to this reflection will be lost.</p>
      <div class="d-flex gap-2 justify-content-center">
        <button class="btn btn-filter" @click="showCancelConfirm = false">Continue editing</button>
        <button class="btn btn-add rounded-pill px-4" @click="editing = false; errors = {}; showCancelConfirm = false">Exit editing</button>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, watch } from 'vue'
  import { useRoute } from 'vue-router'
  import { evLabel, fileAccept, uploadHint } from '@/composables/useCompetencies.js'
  import api from "@/services/api"

  const props = defineProps({
    show: Boolean,
    reflec: Object,
    compt: Object,
    index: Number,
    initialComptId: [String, Number], 
    levelOptions: Array,
    categories: Array
  })

  const errors = ref({});
  const emit = defineEmits(['close', 'refresh'])
  const route = useRoute()
  const originalEf = ref(null)
  const showCancelConfirm = ref(false)

  // Set up a pop up notification instead of having an alert
  const popUp = ref({ show: false, message: '', type: '' })

  const showPopUp = (message, type) => {
    popUp.value = { show: true, message, type }
    setTimeout(() => popUp.value.show = false, 3000)
  }

  // local state
  const editing = ref(false)
  const showDeleteConfirm = ref(false)

  // edit form
  const ef = ref({
    profile_id: route.params.id,
    indicator_id: null,
    experience_title: '',
    associated_year: 0,
    entry_level_id: null,
    entry_status_id: 0,
    start_date: '',
    end_date: '',
    experience_tasks: '',
    key_learnings: '',
    future_applications: '',
    evidenceEntries: []
  })

  const addEvidence = () => {
    if (ef.value.evidenceEntries.length < 3) {
      ef.value.evidenceEntries.push({
        type: '',
        value: '',
        fileName: '',
      })
    }
  };

  // reset edit state when popup closes
  watch(() => props.show, (v) => {
    if (!v) {
      editing.value = false
      showDeleteConfirm.value = false
    }
  })

  function handleFile(e, ev) {
    const file = e.target.files[0]
    if (file) {
      ev.fileName = file.name
      ev.value = file.name
    }
  }

  // enter edit
  function enterEdit() {
    const existingEvidence = (props.reflec.evidence || []).map(ev => ({
      evidence_id: ev.evidence_id,
      type: ev.evidence_type,
      value: ev.evidence_value,
      fileName: ev.evidence_type !== 'url' ? ev.evidence_value : ''
    }))

    ef.value = {
      id: props.reflec.entry_id,
      experience_title: props.reflec.experience_title || '',
      indicator_id: props.compt?.id || '',
      associated_year: props.reflec.associated_year ?? 0,
      entry_level_id: props.reflec.entry_level_id || null,
      start_date: props.reflec.start_date || '',
      end_date: props.reflec.end_date || '',
      experience_tasks: props.reflec.experience_tasks || '',
      key_learnings: props.reflec.key_learnings || '',
      future_applications: props.reflec.future_applications || '',
      evidenceEntries: existingEvidence.length 
        ? existingEvidence
        : [{ type: '', value: '', fileName: '' }]
    }
    editing.value = true
    // Store original entry as string to check for changes
    originalEf.value = JSON.stringify(ef.value)
  }

  // Check to see if url sent is valid
  function isValidUrl(url) {
    try {
      new URL(url)
      return true
    } catch {
      return false
    }
  }

  async function saveEntry(statusId) {
    try {
      // Reset errors
      errors.value = {} 

      // Check if the competency has been changed, if so load cancel confirmation, else don't prompt the user
      const noChange = JSON.stringify(ef.value) === originalEf.value
        if (noChange) {
          emit('close');
          return;
        }

      // Removes empty evidence
      const evidenceToSave = ef.value.evidenceEntries.filter(ev => ev.type && ev.value)

      // If the user is trying to publish the entry, not triggered for drafts
      if (Number(statusId) === 2) {
        // Check for valid title
        if (!ef.value.experience_title) {
          errors.value.title = true
        }

        if (!ef.value.start_date) {
          errors.value.startDate = true
        }

        // Check for experiences field
        if (!ef.value.experience_tasks) {
          errors.value.tasks = true
        }

        // Check for valid links
        for (let i = 0; i < evidenceToSave.length; i++) {
          if (evidenceToSave[i].type === 'url' && !isValidUrl(evidenceToSave[i].value)) {
            errors.value[`evidenceURL_${i}`] = true
          }
        }
      }

      if (Object.keys(errors.value).length) {
        showPopUp("Could not submit entry. Please fix highlighted fields.", "error");
        return;
      }

      await api.put(`/competency-entries/${ef.value.id}`, {
        profile_id: route.params.id,
        indicator_id: Number(ef.value.indicator_id),
        experience_title: ef.value.experience_title || 'Untitled',
        associated_year: Number(ef.value.associated_year),
        entry_level_id: ef.value.entry_level_id,
        entry_status_id: statusId,
        start_date: ef.value.start_date,
        end_date: ef.value.end_date,
        experience_tasks: ef.value.experience_tasks || 'Empty',
        key_learnings: ef.value.key_learnings,
        future_applications: ef.value.future_applications,
      })

      const existingIds = (props.reflec.evidence || []).map(ev => ev.evidence_id)
      for (const id of existingIds) {
        await api.delete(`/competency-evidence/${id}`)
      }

      // Save current evidence entries
      for (const ev of evidenceToSave) {
        await api.post('/competency-evidence', {
          entry_id: ef.value.id,
          evidence_type: ev.type,
          evidence_value: ev.value
        })
      }

      if (Number(statusId) === 2) {
        // Add a post to student actions for updated certificates
        await api.post(`/student-actions/new`, {action: `Updated entry to competency ${props.compt?.displayId}`, student_profile_id: route.params.id});
      }

      // Close window
      emit('refresh', statusId, ef.value.experience_title || 'Untitled')
      emit('close');
      
    } catch (error) {
      showPopUp("Error saving submission.", "error");
    }
  }

  // Pass the entry status id when saving the entry
  // 1 for draft, 2 for submitted
  const saveEdit = () => saveEntry(2)
  const saveAsDraft = () => saveEntry(1)

  // Check if the competency has been changed, if so load cancel confirmation, else don't prompt the user
  const handleCancel = () => {
    const noChange = JSON.stringify(ef.value) === originalEf.value
    if (noChange) {
      editing.value = false
      errors.value = {}
    } else {
      showCancelConfirm.value = true
    }
  }

  async function doDelete() {
    try {
      await api.delete(`/competency-entries/${props.reflec.entry_id}`)
      showDeleteConfirm.value = false
      emit('refresh')
      emit('close')
    } catch (error) {
      showPopUp('Could not delete this reflection', "error")
    }
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

.form-control.field-error {
  border-color: #db7979;
  background: #fff5f5;
  box-shadow: #db7979;
}

.error-message {
  color:  #db7979;
}
</style>