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
            <select v-model="f.level" class="form-select field-select rounded-3">
            <option v-for="opt in levelOptions.filter(o=> o.label !== 'Not Started')" :key="opt.value" :value="opt.value">
              {{ opt.label }}
            </option>
          </select>
          </div>
          <div class="col-7">
            <div class="d-flex justify-content-between align-items-center">  
              <label class="form-label field-label">Experience title</label>
              <label v-if="errors.title" class="field-label error-message">*Title cannot be empty</label>
            </div>
            <input v-model="f.title" maxlength="50" class="form-control field-input rounded-3" :class="{ 'field-error': errors.title }" @input="delete errors.title" placeholder="My experience"/>
          </div>
        </div>

        <!-- dates and associalted year -->
        <div class="row g-3">
          <div class="col-4">
            <div class="d-flex justify-content-between align-items-center">  
              <label class="form-label field-label">Start date</label>
              <label v-if="errors.startDate" class="field-label error-message">*Invalid start date</label>
            </div>
            <input v-model="f.startDate" type="date" class="form-control field-input rounded-3" 
            :class="{ 'field-error': errors.startDate }" @input="delete errors.startDate"/>
          </div>
          <div class="col-4">
            <label class="form-label field-label">End date</label>
            <input v-model="f.endDate" type="date" class="form-control field-input rounded-3"/>
          </div>
          <div class="col-4">
            <label class="form-label field-label">Associated year</label>
            <select v-model="f.year" class="form-select field-select rounded-3">
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
            <label v-if="errors.tasks" class="field-label error-message">*Experience & tasks cannot be empty</label>
          </div>
          <textarea v-model="f.tasks" maxlength="500" class="form-control field-input rounded-3" rows="4"
          :class="{ 'field-error': errors.tasks }" @input="delete errors.tasks"
          placeholder="Describe the experience and tasks you undertook"></textarea>
        </div>

        <!-- key learnings textbox-->
        <div>
          <label class="form-label field-label">Key learnings: (Max 500 characters)</label>
          <textarea v-model="f.learnings" maxlength="500" class="form-control field-input rounded-3" rows="4"
          placeholder="What did you learn that was most valuable?"></textarea>
        </div>

        <!-- future application textbox-->
        <div>
          <label class="form-label field-label">Future application: (Max 500 characters)</label>
          <textarea v-model="f.future" maxlength="500" class="form-control field-input rounded-3" rows="4"
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
              <div class="d-flex justify-content-between align-items-center"> 
                <label class="form-label field-label mb-3">Evidence input</label>
                <label v-if="errors[`evidenceURL_${idx}`]" class="field-label error-message">*Invalid evidence URL</label>
              </div> 
            
              <!-- nothing selected-->
              <input v-if="!ev.type" class="form-control field-input rounded-3"
              disabled placeholder="Select a type first"/>

              <!-- if link selected-->
              <input v-else-if="ev.type==='url'" v-model="ev.value" type="url"
                class="form-control field-input rounded-3" 
                :class="{ 'field-error': errors[`evidenceURL_${idx}`] }" @input="delete errors[`evidenceURL_${idx}`]"
                placeholder="https://example.com"/>

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

          <button v-if="f.evidenceEntries.length < 3" class="btn btn-add-ev rounded-pill px-3 py-1 mt-1"
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

  const props = defineProps({
    show: Boolean,
    initialComptId: [String, Number], 
    levelOptions: Array,
    categories: Array
  })
  
  const errors = ref({});
  const showCancelConfirm = ref(false)

  // Set up a pop up notification instead of having an alert
  const popUp = ref({ show: false, message: '', type: '' })

  const showPopUp = (message, type) => {
    popUp.value = { show: true, message, type }
    setTimeout(() => popUp.value.show = false, 3000)
  }

  const emit = defineEmits(['close', 'refresh']);
  const route = useRoute();

  const allCompts = computed(() => {
    // Get the indicator id and description for the selected competency
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

  const addEvidence = () => {
    if (f.value.evidenceEntries.length < 3) {
      f.value.evidenceEntries.push({
        type: '',
        value: '',
        fileName: '',
      })
    }
  };

  const newForm = () => ({
    comptId: null,
    title: '',
    year: 1,
    level: 1,
    startDate: '',
    endDate: '',
    tasks: '',
    learnings: '',
    future: '',
    evidenceEntries: [{ type: '', value: '', fileName: '' }]
  })

  // form state
  const f = ref(newForm())

  // when popup opens or initialComptId changes, reset and prefill
  watch(() => props.show, (v) => {
    if (v) {
      f.value = newForm();
      f.value.comptId = props.initialComptId;
    }}, 
    // Run straight away
    { immediate: true });

  watch(()=> props.initialComptId, (id)=> {
    if (props.show) {
      f.value.comptId = id
    }
  })

  const selectedCompt = computed(()=>
    allCompts.value.find(c=> c.id===f.value.comptId)
  )

  function handleFile(e, ev) {
    const file = e.target.files[0]
    if (file) { 
      ev.fileName = file.name; 
      ev.value = file.name 
    }
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

// submit form
async function submit(statusId) {
  try {
    // Reset errors
    errors.value = {} 

    // Removes empty evidence
    const evidenceToSave = f.value.evidenceEntries.filter(ev => ev.type && ev.value)

    // If the user is trying to publish the entry, not triggered for drafts
    if (Number(statusId) === 2) {
      // Check for valid title
      if (!f.value.title) {
        errors.value.title = true
      }

      // Check for valid title
      if (!f.value.startDate) {
        errors.value.startDate = true
      }

      // Check for experiences field
      if (!f.value.tasks) {
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

    const payload = {
      profile_id: route.params.id,
      indicator_id: Number(f.value.comptId),
      experience_title: f.value.title || 'Untitled',
      associated_year: Number(f.value.year),
      entry_level_id: f.value.level, 
      entry_status_id: statusId, 
      start_date: f.value.startDate || new Date().toISOString().split('T')[0],
      end_date: f.value.endDate,
      experience_tasks: f.value.tasks || "Draft",
      key_learnings: f.value.learnings,
      future_applications: f.value.future,
    };

    const res = await api.post('/competency-entries', payload);
    const entryId = res.data.entry_id

    // Save each evidence entry
    for (const ev of evidenceToSave) {
      await api.post('/competency-evidence', {
        entry_id: entryId,
        evidence_type: ev.type,
        evidence_value: ev.value
      })
    }

    // Add a post to student actions for an added competency
    await api.post(`/student-actions/new`, {action: `Added entry to competency ${selectedCompt.value?.displayId}`, student_profile_id: route.params.id});
    // RefreshClose window
    emit('refresh', statusId, f.value.title || 'Untitled');
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
    // Copy all data from newForm and add the competency id before checking if there was a change
    const noChange = JSON.stringify(f.value) === JSON.stringify({ ...newForm(), comptId: props.initialComptId })
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
</style>