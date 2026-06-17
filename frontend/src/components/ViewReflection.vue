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
            <img v-if="!compt?.discontinuedDate" class="plus-btn" src="@/assets/edit.png" @click="enterEdit" title="Edit">
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
                  <a v-if="ev.evidence_type === 'video'" :href="ev.evidence_value" target="_blank">
                    {{ ev.evidence_value }}
                  </a>
                  <span v-else><a :href="ev.evidence_value" target="_blank">
                    Link to {{ ev.evidence_type }}
                  </a></span>
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
          <input v-model.trim="editForm.experience_title" maxlength="50" class="form-control rounded-3 text-center edit-title-input"
           :class="{ 'field-error': errors.title }" @input="delete errors.title" />

          <!-- compt, level and year -->
          <div class="d-flex justify-content-center gap-2 mt-3">
            <select v-model="editForm.indicator_id" class="pill-select">
              <option v-for="c in allCompts" :key="c.id" :value="c.id">Competency {{ c.displayId }}</option>
            </select>

            <select v-model="editForm.associated_year" class="pill-select">
              <option v-for="opt in yearOptions" :key="opt.value" :value="opt.value">
                {{ opt.label }}
              </option>
            </select>

            <select v-model="editForm.entry_level_id" class="pill-select">
              <option v-for="opt in levelOptions" :key="opt.value" :value="opt.value">
                {{ opt.label }}
              </option>
            </select>
          </div>

          <!-- date range-->
          <label v-if="errors.startDate" class="field-label error-message">*Invalid start date</label>
          <div class="d-flex justify-content-center align-items-center gap-2 mt-3">
            <input v-model="editForm.start_date" type="date" class="form-control field-input rounded-3 text-center date-picker"
              :class="{ 'field-error': errors.startDate }" @input="delete errors.startDate"/>
            <span class="body-txt">–</span>
            <input v-model="editForm.end_date" type="date" class="form-control field-input rounded-3 text-center date-picker"/>
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
            <textarea v-model.trim="editForm.experience_tasks" maxlength="500" class="form-control field-input rounded-3" rows="4"
              :class="{ 'field-error': errors.tasks }" @input="delete errors.tasks"  
              placeholder="Describe the experience and tasks you undertook"></textarea>
          </div>

          <!-- key learnings -->
          <div>
            <label class="form-label field-label">Key learnings: (Max 500 characters)</label>
            <textarea v-model.trim="editForm.key_learnings" maxlength="500" class="form-control field-input rounded-3" rows="4"
              placeholder="What did you learn that was most valuable?"></textarea>
          </div>

          <!-- future application -->
          <div>
            <label class="form-label field-label">Future application: (Max 500 characters)</label>
            <textarea v-model.trim="editForm.future_applications" maxlength="500" class="form-control field-input rounded-3" rows="4"
              placeholder="How will you apply these learnings in the future?"></textarea>
          </div>

          <!-- existing evidence entries -->
          <div>
            <p class="form-label field-label">Existing Evidence</p>
            <div v-if="editForm.existingEvidence?.length" class="d-flex flex-column gap-2 mb-3">
              <div v-for="ev in editForm.existingEvidence" :key="ev.evidence_id"
                class="d-flex align-items-center justify-content-between p-2 rounded-3 field-input">
                <span class="field-label">{{ evLabel(ev.evidence_type) }}: 
                  <a :href="ev.evidence_value" target="_blank">{{ ev.evidence_type === 'url' || ev.evidence_type === 'video' ? ev.evidence_value : 'View file' }}</a>
                </span>
                <button class="del-btn" @click="editForm.existingEvidence = editForm.existingEvidence.filter(e => e.evidence_id !== ev.evidence_id); evidenceToDelete.push(ev.evidence_id)" title="Remove">
                  <img src="@/assets/delete.png">
                </button>
              </div>
            </div>
            <p v-else class="field-label">No existing evidence</p>
          </div>


          <!-- editable evidence entries -->
          <div>
            <div v-for="(ev, idx) in editForm.evidenceEntries" :key="idx" class="d-flex gap-3 align-items-end mb-3 pb-3" 
            :class="{ 'border-bottom': idx < editForm.evidenceEntries.length-1 }">

              <!-- evidence type -->
              <div>
                <label class="form-label field-label mb-3">Evidence type</label>
                <select v-model="ev.type" class="form-select field-select rounded-3" @change="ev.value = ''; ev.fileName = ''; ev.file = null">
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
                  <label v-else-if="errors[`evidenceVideo_${idx}`]" class="field-label error-message">*Invalid YouTube link</label>
                  <label v-else-if="errors[`evidenceFileType_${idx}`]" class="field-label error-message">*Invalid file type</label>
                </div> 

                <!-- nothing selected -->
                <input v-if="!ev.type" class="form-control field-input rounded-3"
                  disabled placeholder="Select a type first"/>

                <!-- link -->
                <input v-else-if="ev.type === 'url'" v-model="ev.value" type="url"
                  class="form-control field-input rounded-3" 
                  :class="{ 'field-error': errors[`evidenceURL_${idx}`] }" @input="delete errors[`evidenceURL_${idx}`]"
                  placeholder="https://example.com"/>

                <input v-else-if="ev.type==='video'" v-model="ev.value" type="video"
                  class="form-control field-input rounded-3" 
                  :class="{ 'field-error': errors[`evidenceVideo_${idx}`] }" @input="delete errors[`evidenceVideo_${idx}`]"
                  placeholder="https://www.youtube.com/watch?v="/>
                <!-- file upload -->
                <div v-else>
                  <div class="upload-zone rounded-3 p-3" :class="{ 'upload-zone-filled': ev.fileName }">
                    <input v-if="!ev.fileName" type="file" :accept="fileAccept(ev.type)" class="position-absolute opacity-0" @change="e=> handleFile(e, ev, idx)"/>

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
              <button v-if="editForm.evidenceEntries.length>1" class="del-btn mb-1"
              @click="editForm.evidenceEntries.splice(idx, 1)" title="Remove">
                <img src="@/assets/delete.png">
              </button>
            </div>

            <button  v-if="editForm.evidenceEntries.length < 3"
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
  import { ref, watch, computed } from 'vue'
  import { useRoute } from 'vue-router'
  import { evLabel, fileAccept, uploadHint, yearOptions } from '@/composables/useCompetencies.js'
  import api from "@/services/api"

  // Props received from currentCompetencies or draftReflections when an entry is open
  const props = defineProps({
    show: Boolean,
    reflec: Object,
    compt: Object,
    index: Number,
    initialComptId: [String, Number], 
    levelOptions: Array,
    categories: Array
  })

  // Variables for getting profile id from url
  const route = useRoute();


  // Store errors from input and show/hide cancel confirm popup
  const errors = ref({});

  // Show popup window states
  const editing = ref(false)
  const showCancelConfirm = ref(false)
  const showDeleteConfirm = ref(false)

  // Declares that events that can be sent to parent 
  const emit = defineEmits(['close', 'refresh'])

  // Stores the original entry to be compared to any edits to see if changes were made
  const originalEditForm = ref(null)
  const evidenceToDelete = ref([])

  // Object to store data about the popup message
  const popUp = ref({ show: false, message: '', type: '' })
  // Time the popup can be viewed for. Currently set to 3 seconds allow time for the user to view the message
  const popUpTime = 3000

  // Used to display the popup message and the type being either success or error
  const showPopUp = (message, type) => {
    popUp.value = { show: true, message, type }
    setTimeout(() => popUp.value.show = false, popUpTime)
  }
  
  // Edit form
  const editForm = ref({
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

  // Takes the competency data and turns it into an array of competencies so it can be used for the drop down
  const allCompts = computed(() => {
    // Flatmap gives a single array containing all competencies instead of nested arrays
    return props.categories.flatMap(category => {
      return category.compt
      .filter(indicator => !indicator.discontinuedDate)
      .map(indicator => ({
        id: indicator.id, 
        displayId: indicator.displayId,
      }));
    });
  });

   // Add a new evidence entry to the form, limited to 3 evidence entries
  const addEvidence = () => {
    if (editForm.value.evidenceEntries.length < 3) {
      editForm.value.evidenceEntries.push({
        type: '',
        value: '',
        fileName: '',
        file: null
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

  // Gets the file from the upload field and prepares it for upload
  function handleFile(e, ev, idx) {
    const file = e.target.files[0]
    if (file) {
      if (ev.type === 'document' && file.type !== 'application/pdf') {
        errors.value[`evidenceFileType_${idx}`] = true
        return
      }

      if (ev.type === 'image' && !file.type.startsWith('image/')) {
        errors.value[`evidenceFileType_${idx}`] = true
        return
      }
      ev.fileName = file.name
      ev.value = file.name
      ev.file = file
      delete errors.value[`evidenceFileType_${idx}`]
    }
  }

  // enter edit
  function enterEdit() {
    // Map existing reflection evidence with new file name field. Name it the evidence type for a start
    const existingEvidence = (props.reflec.evidence || []).map(ev => ({
      evidence_id: ev.evidence_id,
      type: ev.evidence_type,
      value: ev.evidence_value,
      // If it is a url set to empty string, else set it to the filename
      fileName: ev.evidence_type !== 'url' ? ev.evidence_value : '',
      file: null
    }))

    evidenceToDelete.value = []

    // Populate the edit form with the values saved 
    editForm.value = {
      id: props.reflec.entry_id,
      experience_title: props.reflec.experience_title || '',
      indicator_id: props.compt?.id || '',
      associated_year: props.reflec.associated_year || 0,
      entry_level_id: props.reflec.entry_level_id || null,
      start_date: props.reflec.start_date || '',
      end_date: props.reflec.end_date || '',
      experience_tasks: props.reflec.experience_tasks || '',
      key_learnings: props.reflec.key_learnings || '',
      future_applications: props.reflec.future_applications || '',
      existingEvidence: props.reflec.evidence || [],  // read-only, just for display and deletion
      evidenceEntries: [{ type: '', value: '', fileName: '', file: null }] 
    }

    // Enter editing
    editing.value = true
    // Store original entry as string to check for changes
    originalEditForm.value = JSON.stringify(editForm.value)
  }

  // Attempt to make a URL object to test if link is correct
  const isValidUrl = (url) => {
    try {
      // URL constructor throws an error if the url format is invalid
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
      const noChange = (JSON.stringify(editForm.value) === originalEditForm.value && editForm.value.entry_status_id === statusId)
        if (noChange) {
          emit('close');
          return;
        }

      // Removes empty evidence
      const evidenceToSave = editForm.value.evidenceEntries.filter(ev => ev.type && ev.value)

      // If the user is trying to publish the entry, not triggered for drafts
      if (Number(statusId) === 2) {
        // Check for valid title
        if (!editForm.value.experience_title) {
          errors.value.title = true
        }

        // Check if a start date has been inputted
        if (!editForm.value.start_date) {
          errors.value.startDate = true
        }

        // Check for experiences field
        if (!editForm.value.experience_tasks) {
          errors.value.tasks = true
        }

        // Loop through and check if url evidence contains a valid link
        for (let i = 0; i < evidenceToSave.length; i++) {
          if (evidenceToSave[i].type === 'url' && !isValidUrl(evidenceToSave[i].value)) {
            errors.value[`evidenceURL_${i}`] = true
          } else if (evidenceToSave[i].type === 'video' && !evidenceToSave[i].value.startsWith('https://www.youtube.com/watch?v=')) {
            errors.value[`evidenceVideo_${i}`] = true
          }
        }
      }

      if (JSON.stringify(errors.value) !== '{}') {
        showPopUp("Could not submit entry. Please fix highlighted fields.", "error");
        return;
      }

      // Creates a payload to be submitted, some compulsory values have fallback values if the user chooses to save as a draft
      const payload = {
        profile_id: route.params.id,
        indicator_id: Number(editForm.value.indicator_id),
        experience_title: editForm.value.experience_title || 'Untitled',
        associated_year: Number(editForm.value.associated_year),
        entry_level_id: editForm.value.entry_level_id,
        entry_status_id: statusId,
        start_date: editForm.value.start_date,
        end_date: editForm.value.end_date,
        experience_tasks: editForm.value.experience_tasks || 'Empty',
        key_learnings: editForm.value.key_learnings,
        future_applications: editForm.value.future_applications,
      }

      // Edit the backend database with the changed data
      await api.put(`/competency-entries/${editForm.value.id}`, payload)

      // Delete evidence that was deleted by the user
      for (const id of evidenceToDelete.value) {
        await api.delete(`/competency-evidence/${id}`)
      }

      // Go through each evidence to save  
      const newEvidence = editForm.value.evidenceEntries.filter(ev => ev.type && ev.value)
      for (const ev of newEvidence) {
        if (ev.type === 'document' || ev.type === 'image') {
          const formData = new FormData()
          formData.append('entry_id', editForm.value.id)
          formData.append('evidence_type', ev.type)
          formData.append(ev.type === 'document' ? 'file' : 'image', ev.file)
          await api.post('/competency-evidence', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          })
        } else {
          await api.post('/competency-evidence', {
            entry_id: editForm.value.id,
            evidence_type: ev.type,
            evidence_value: ev.value
          })
        }
      }

      // Only add a post to student actions when the competency is published
      if (Number(statusId) === 2) {
        await api.post(`/student-actions/new`, {action: `Updated entry to competency ${props.compt?.displayId}`, student_profile_id: route.params.id});
      }

      // Reset evidence to delete
      evidenceToDelete.value = []

      // Call parent functions to close the window and show the popup message
      emit('refresh', statusId, editForm.value.experience_title || 'Untitled')
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
    const noChange = JSON.stringify(editForm.value) === originalEditForm.value
    if (noChange) {
      editing.value = false
      errors.value = {}
    } else {
      showCancelConfirm.value = true
    }
  }

  // Delete the competency entry
  async function doDelete() {
    try {
      // Send call to the backend to check for delete
      await api.delete(`/competency-entries/${props.reflec.entry_id}`)
      showDeleteConfirm.value = false

      // Add new entry to the student actions table
      await api.post(`/student-actions/new`, {action: `Deleted entry to competency ${props.compt?.displayId}`, student_profile_id: route.params.id});
      
      // Send calls to currentCompetency to run onSaveReflec
      emit('refresh', -1, '')
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
  cursor: default;
}

.upload-zone input[type="file"] {
  cursor: pointer;
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