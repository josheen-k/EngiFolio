<template>
  <div v-if="show" class="add-popup" @click.self="$emit('close')">
    <div class="add-popup-box">

      <h2 class="text-center fw-bold border-bottom p-3 add-title">Add a new reflection entry</h2>
      <div class="add-popup-scroll px-4 py-4 d-flex flex-column gap-4">

        <!-- competency name and desc-->
        <div class="row g-4">
          <div class="col-5">
            <label class="form-label field-label">Adding reflection for:</label>
            <select v-model="newEntry.indicator_id" class="form-select field-select rounded-3">
              <option v-for="c in allCompts" :key="c.id" :value="c.id">Competency {{ c.displayId }}</option>
            </select>
          </div>
          <div class="col-7">
            <label class="form-label field-label">Description:</label>
            <p class="field-desc">{{ selectedCompt?.desc || 'No description available'}}</p>
          </div>
        </div>

        <!-- competency lvl and exp title-->
        <div class="row g-4">
          <div class="col-5">
            <label class="form-label field-label">Attainment level</label>
            <select v-model="newEntry.entry_level_id" class="form-select field-select rounded-3">
            <option v-for="opt in levelOptions.filter(o=> o.label !== 'Not Started')" :key="opt.value" :value="opt.value">
              {{ opt.label }}
            </option>
          </select>
          </div>
          <div class="col-7">
            <div class="d-flex justify-content-between align-items-center">  
              <label class="form-label field-label">Experience title</label>
              <label v-if="errors.experience_title" class="field-label error-message">*Title cannot be empty</label>
            </div>
            <input v-model="newEntry.experience_title" maxlength="50" class="form-control field-input rounded-3" :class="{ 'field-error': errors.experience_title }" @input="delete errors.experience_title" placeholder="My experience"/>
          </div>
        </div>

        <!-- dates and associalted year -->
        <div class="row g-3">
          <div class="col-4">
            <div class="d-flex justify-content-between align-items-center">  
              <label class="form-label field-label">Start date</label>
              <label v-if="errors.start_date" class="field-label error-message">*Invalid start date</label>
            </div>
            <input v-model="newEntry.start_date" type="date" class="form-control field-input rounded-3" 
            :class="{ 'field-error': errors.start_date }" @input="delete errors.start_date"/>
          </div>
          <div class="col-4">
            <label class="form-label field-label">End date</label>
            <input v-model="newEntry.end_date" type="date" class="form-control field-input rounded-3"/>
          </div>
          <div class="col-4">
            <label class="form-label field-label">Associated year</label>
            <select v-model="newEntry.associated_year" class="form-select field-select rounded-3">
              <option v-for="opt in yearOptions" :key="opt.value" :value="opt.value">
                {{ opt.label }}
              </option>
            </select>
          </div>
        </div>

        <!-- exp and tasks textbox-->
        <div>
          <div class="d-flex justify-content-between align-items-center">  
            <label class="form-label field-label">Experience &amp; tasks: (Max 500 characters)</label>
            <label v-if="errors.experience_tasks" class="field-label error-message">*Experience & tasks cannot be empty</label>
          </div>
          <textarea v-model="newEntry.experience_tasks" maxlength="500" class="form-control field-input rounded-3" rows="4"
          :class="{ 'field-error': errors.experience_tasks }" @input="delete errors.experience_tasks"
          placeholder="Describe the experience and tasks you undertook"></textarea>
        </div>

        <!-- key learnings textbox-->
        <div>
          <label class="form-label field-label">Key learnings: (Max 500 characters)</label>
          <textarea v-model="newEntry.key_learnings" maxlength="500" class="form-control field-input rounded-3" rows="4"
          placeholder="What did you learn that was most valuable?"></textarea>
        </div>

        <!-- future application textbox-->
        <div>
          <label class="form-label field-label">Future application: (Max 500 characters)</label>
          <textarea v-model="newEntry.future_applications" maxlength="500" class="form-control field-input rounded-3" rows="4"
          placeholder="How will you apply these learnings in the future?"></textarea>
        </div>

        <!-- evidence entries-->
        <div>
          <div v-for="(ev, idx) in newEntry.evidenceEntries" :key="idx" class="d-flex gap-3 align-items-start mb-3 pb-3"
          :class="{ 'border-bottom': idx < newEntry.evidenceEntries.length-1 }">

            <!-- evidence type -->
            <div>
              <label class="form-label field-label mb-3">Evidence type</label>
              <select v-model="ev.type" class="form-select field-select rounded-3"
              @change="ev.value = ''; ev.fileName = ''; ev.file = null">
                <option value="">Select evidence type</option>
                <option value="url">Link</option>
                <option value="document">Document</option>
                <option value="image">Image</option>
                <option value="video">Video</option>
              </select>
            </div>

            <!-- evidence input field -->
            <div class="flex-grow-1">  
              <div class="d-flex justify-content-between align-items-center"> 
                <label class="form-label field-label mb-3">Evidence input</label>
                <label v-if="errors[`evidenceURL_${idx}`]" class="field-label error-message">*Invalid evidence URL</label>
                <label v-else-if="errors[`evidenceVideo_${idx}`]" class="field-label error-message">*Invalid YouTube link</label>
                <label v-else-if="errors[`evidenceFileType_${idx}`]" class="field-label error-message">*Invalid file type</label>
              </div> 
            
              <!-- nothing selected-->
              <input v-if="!ev.type" class="form-control field-input rounded-3"
              disabled placeholder="Select a type first"/>

              <!-- if link selected-->
              <input v-else-if="ev.type==='url'" v-model="ev.value" type="url"
                class="form-control field-input rounded-3" 
                :class="{ 'field-error': errors[`evidenceURL_${idx}`] }" @input="delete errors[`evidenceURL_${idx}`]"
                placeholder="https://example.com"/>

              <input v-else-if="ev.type==='video'" v-model="ev.value" type="video"
                class="form-control field-input rounded-3" 
                :class="{ 'field-error': errors[`evidenceVideo_${idx}`] }" @input="delete errors[`evidenceVideo_${idx}`]"
                placeholder="https://www.youtube.com/watch?v="/>

              <!-- if file upload selected -->
              <div v-else>
                <div class="upload-zone rounded-3 p-3" :class="{ 'upload-zone-filled': ev.fileName }">

                  <input type="file" :accept="fileAccept(ev.type)" class="position-absolute w-100 h-100 opacity-0"
                  @change="e=> handleFile(e, ev, idx)"/>

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
            <button v-if="newEntry.evidenceEntries.length>1" class="del-btn mb-1"
            @click="newEntry.evidenceEntries.splice(idx, 1)" title="Remove">
              <img src="@/assets/delete.png">
            </button>
          </div>

          <button v-if="newEntry.evidenceEntries.length < 3" class="btn btn-add-ev rounded-pill px-3 py-1 mt-1"
          @click="addEvidence()">+ Add evidence</button>
        </div>
      </div>

      <!-- form footer actions -->
      <div class="d-flex justify-content-between align-items-center px-4 pb-4 pt-3 border-top">
        <span class="scroll-txt"><u>Scroll to see all fields</u></span>

        <div class="d-flex gap-2">
          <button class="btn btn-filter" @click="saveAsDraft">Save as draft</button>
          <button class="btn btn-filter" @click="handleCancel">Cancel</button>
          <button class="btn btn-add" @click="save">Done</button>
        </div>
      </div>
      <div v-if="popUp.show" class="popUp-msg" :class="popUp.type">
        {{ popUp.message }}
      </div>
    </div>
  </div>

  <div v-if="showCancelConfirm" class="view-popup" @click.self="showCancelConfirm = false">
    <div class="cancel-box text-center p-4">
      <h5 class="fw-bold mb-2 cancel-title">Cancel editing?</h5>
      <p class="field-desc mb-4">All changes to this reflection will be lost.</p>
      <div class="d-flex gap-2 justify-content-center">
        <button class="btn btn-filter" @click="showCancelConfirm = false">Continue editing</button>
        <button class="btn btn-add rounded-pill px-4" @click="emit('close'); showCancelConfirm = false">Exit editing</button>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, computed, watch } from 'vue'
  import { fileAccept, uploadHint, yearOptions } from '@/composables/useCompetencies.js'
  import { useRoute } from 'vue-router'
  import api from "@/services/api"

  // Props received from add reflection
  const props = defineProps({
    show: Boolean,
    initialComptId: [String, Number], 
    levelOptions: Array,
    categories: Array
  })
  
  // Variables for getting profile id from url
  const route = useRoute();

  // Store errors from input and show/hide cancel confirm popup
  const errors = ref({});
  const showCancelConfirm = ref(false)

  // Declares that events that can be sent to parent
  const emit = defineEmits(['close', 'refresh']);

  // Object to store data about the popup message
  const popUp = ref({ show: false, message: '', type: '' })
  // Time the popup can be viewed for. Currently set to 3 seconds allow time for the user to view the message
  const popUpTime = 3000

  // Used to display the popup message and the type being either success or error
  const showPopUp = (message, type) => {
    popUp.value = { show: true, message, type }
    setTimeout(() => popUp.value.show = false, popUpTime)
  }

  // Takes the competency data and turns it into an array of competencies so it can be used for the drop down
  const allCompts = computed(() => {
    // Flatmap gives a single array containing all competencies instead of nested arrays
    return props.categories.flatMap(category => {
      return category.compt
      .filter(indicator => !indicator.discontinuedDate)
      .map(indicator => ({
        id: indicator.id, 
        displayId: indicator.displayId,
        desc: indicator.desc || '' 
      }));
    });
  });

  // Add a new evidence entry to the form, limited to 3 evidence entries
  const addEvidence = () => {
    if (newEntry.value.evidenceEntries.length < 3) {
      newEntry.value.evidenceEntries.push({
        type: '',
        value: '',
        fileName: '',
        file: null
      })
    }
  };

  // Create an empty competency entry, with year and level set so that there is a default drop down value
  const emptyEntry  = () => ({
    indicator_id: null,
    experience_title: '',
    associated_year: 1,
    entry_level_id: 1,
    start_date: '',
    end_date: '',
    experience_tasks: '',
    key_learnings: '',
    future_applications: '',
    evidenceEntries: [{ type: '', value: '', fileName: '', file: null }]
  })

  // Create a new form
  const newEntry = ref(emptyEntry())

  // when popup opens or initialComptId changes, reset and prefill
  watch(() => props.show, (v) => {
    if (v) {
      newEntry.value = emptyEntry();
      newEntry.value.indicator_id = props.initialComptId;
    }}, 
    // Run straight away
    { immediate: true });

  // Find the competency that has been passed as the indicator_id
  const selectedCompt = computed(()=>
    allCompts.value.find(c=> c.id === newEntry.value.indicator_id)
  )

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

      ev.fileName = file.name; 
      ev.value = file.name;
      ev.file = file;
      delete errors.value[`evidenceFileType_${idx}`]
    }
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

// submit form
  async function submit(statusId) {
    try {
      // Reset errors
      errors.value = {} 

      // Removes empty evidence by filtering out entries without a type and a value
      const evidenceToSave = newEntry.value.evidenceEntries.filter(ev => ev.type && ev.value)

      // If the user is trying to publish the entry, not triggered for drafts
      if (Number(statusId) === 2) {
        // Check for valid title
        if (!newEntry.value.experience_title.trim()) {
          errors.value.experience_title = true
        }

        // Check if a start date has been inputted
        if (!newEntry.value.start_date) {
          errors.value.start_date = true
        }

        // Check for experiences field
        if (!newEntry.value.experience_tasks.trim()) {
          errors.value.experience_tasks = true
        }

        // Loop through and check if evidence input is valid
        for (let i = 0; i < evidenceToSave.length; i++) {
          if (evidenceToSave[i].type === 'url' && !isValidUrl(evidenceToSave[i].value)) {
            errors.value[`evidenceURL_${i}`] = true
          } else if (evidenceToSave[i].type === 'video' && !evidenceToSave[i].value.startsWith('https://www.youtube.com/watch?v=')) {
            errors.value[`evidenceVideo_${i}`] = true
          }
        }
      }

      // Convert object into JSON and check if it is empty to see if there are any errors
      if (JSON.stringify(errors.value) !== '{}') {
        showPopUp("Could not submit entry. Please fix highlighted fields.", "error");
        return;
      }

      // Creates a payload to be submitted, some compulsory values have fallback values if the user chooses to save as a draft
      // date() creates a standardised date string for the current date and time and splits the string so only the date is saved
      const payload = {
        profile_id: route.params.id,
        indicator_id: Number(newEntry.value.indicator_id),
        experience_title: newEntry.value.experience_title || 'Untitled',
        associated_year: Number(newEntry.value.associated_year),
        entry_level_id: newEntry.value.entry_level_id, 
        entry_status_id: statusId, 
        start_date: newEntry.value.start_date || new Date().toISOString().split('T')[0],
        end_date: newEntry.value.end_date,
        experience_tasks: newEntry.value.experience_tasks || "Empty",
        key_learnings: newEntry.value.key_learnings,
        future_applications: newEntry.value.future_applications,
      };

      // Create the new entry on the backend. Get the returned entry_id so that evidence can be linked to the entry
      const res = await api.post('/competency-entries', payload);
      const entryId = res.data.entry_id

      // Save each evidence entry
      for (const ev of evidenceToSave) {
        // Check if evidence type requires a file upload
        if (ev.type === 'document' || ev.type === 'image') {
          // Create a special object for sending files over HTTP, handles binary data
          const formData = new FormData()
          // Add data to the form for passing to the backend
          formData.append('entry_id', entryId);
          formData.append('evidence_type', ev.type)

          // Set the content type so laravel knows to parse it as a file upload
          if (ev.type === 'document') {
            formData.append('file', ev.file)
          } else {
            formData.append('image', ev.file)
          }
          
          // Call the backend to add the evidence
          await api.post('/competency-evidence', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          })
        } else {
          // If the evidence is a link, add it
          await api.post('/competency-evidence', {
            entry_id: entryId,
            evidence_type: ev.type,
            evidence_value: ev.value
          })
        } 
      }

      // Add a post to student actions for an added competency
      await api.post(`/student-actions/new`, {action: `Added entry to competency ${selectedCompt.value?.displayId}`, student_profile_id: route.params.id});
      // Refresh and close the window, pass the statusId and title to current competencies so that confirmation bubble can display them
      emit('refresh', statusId, newEntry.value.experience_title || 'Untitled');
      emit('close');

    } catch (error) {
      showPopUp("Error saving submission.", "error");
    }
  }

  // Pass the entry status id when saving the entry
  // 1 for draft, 2 for submitted
  const save = () => submit(2)
  const saveAsDraft = () => submit(1)

  // Check if profile has been changed, if so load cancel confirmation, else don't prompt the user
  const handleCancel = () => {
    // Copy all data from emptyEntry and add the competency id before checking if there was a change
    const noChange = JSON.stringify(newEntry.value) === JSON.stringify({ ...emptyEntry(), indicator_id: props.initialComptId })
    if (noChange) {
      emit('close')
    } else {
      showCancelConfirm.value = true
    }
  }
</script>

<style scoped>
.add-popup {
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

.scroll-txt {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.9rem;
  color: #888888;
}

.btn-filter, .btn-add-ev {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  background: #e6e6e6;
  color: #222222;
}

.btn-filter {
  font-size: 1rem;
}

.btn-add-ev {
  font-size: 0.8rem;
  font-weight: lighter;
}

.btn-filter:hover, .btn-add-ev:hover {
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

.view-popup {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.3);
  backdrop-filter: blur(0.375rem);
  z-index: 5;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.25rem;
}

.cancel-box {
  background: #ffffff;
  border-radius: 1.25rem;
  max-width: 22.5rem;
  width: 100%;
  box-shadow: 0 1.25rem 3.75rem rgba(0, 0, 0, 0.2);
}

.cancel-title {
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 1.1rem;
  color: #222222;
}
</style>URL