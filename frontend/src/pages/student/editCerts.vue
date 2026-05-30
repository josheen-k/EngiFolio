<template>
  <Navbar />

  <div class="container-lg py-5 px-4" v-if="profile">
    <!--achievement certs-->
    <section class="mb-5 pb-4 border-bottom">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="sec-title mb-0">Achievement Certifications</h2>
        <button class="btn btn-ql" @click="addAchCert">Add Achievement</button>
      </div>
      <div v-if="profile.achievement_certs.length" class="d-flex flex-column gap-3">
        <div class="cert-row" v-for="(cert, index) in profile.achievement_certs" :key="index" :class="{ 
            'cert-dragging': movedAchCertId  === cert.achievement_cert_id,
            'cert-error': (errors[`achieveTitle_${index}`] || errors[`achieveURL_${index}`]) && expandedAchCerts !== index
          }"
          @dragenter.prevent
          @dragover.prevent
          @drop="achCertRearange(cert)"
          >

          <!-- header-->
          <div class="d-flex align-items-center gap-2 header" @click="toggleAchCert(index)">
            <!--  Drag button, uses a similar method to goals page -->
            <button type="button" class="drag-handle-btn" draggable="true" aria-label="Drag to reorder certificates" @dragstart="movedAchCertId = cert.achievement_cert_id" @dragend="movedAchCertId = null" @click.stop>
              <span class="drag-handle-icon" aria-hidden="true">⋮⋮</span>
            </button>
            <img src="@/assets/cert.png" class="cert-icon" alt="certificate" />
            <span class="cert-type-label">{{ cert.title || 'New Certificate' }}</span>
            <button class="remove-btn ms-auto" @click.stop="toggleAchCert(index)">
              <img src="@/assets/triangle.png" class="triangle" :class="{ open: expandedAchCerts === index }" alt="toggle"/>
            </button>
            <button class="remove-btn" @click.stop="removeAchCert(index)">
              <img src="@/assets/delete.png" class="del-icon" alt="remove" />
            </button>
          </div>
          
          <!--drop down-->
          <div v-if="expandedAchCerts === index" class="row g-3">
            <div class="col-12 col-md-6">
              <div class="d-flex justify-content-between align-items-center">  
                <label class="field-label">Title</label>
                <label v-if="errors[`achieveTitle_${index}`]" class="field-label error-message">*Title cannot be empty</label>
              </div>
              <input v-model.trim="cert.title" maxlength="100" class="field-input form-control" :class="{ 'field-error': errors[`achieveTitle_${index}`] }" @input="delete errors[`achieveTitle_${index}`]" placeholder="eg: Dean's Award" />
            </div>
            <div class="col-12">
              <label class="field-label">Description</label>
              <input v-model.trim="cert.body" class="field-input form-control"
                placeholder="Brief description of this certification" />
            </div>
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">  
                <label class="field-label">File path / URL</label>
                <label v-if="errors[`achieveURL_${index}`]" class="field-label error-message">*Must be a valid path</label>
              </div>
              <input v-model.trim="cert.file_path" maxlength="255" :class="{ 'field-error': errors[`achieveURL_${index}`] }" @input="delete errors[`achieveURL_${index}`]" class="field-input form-control"
                placeholder="https://example.com/cert.pdf" />
            </div>
            <div class="col-12 col-md-4">
              <label class="field-label">Issued date</label>
              <input type="date" v-model.trim="cert.issued_date" class="field-input form-control" />
            </div>
          </div>

        </div>
      </div>
      <p v-else class="empty-txt text-center py-4">No achievement certifications yet! Click add to get started.</p>
    </section>

    <!--attainment certs-->
    <section class="mb-5 pb-4 border-bottom">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="sec-title mb-0">Attainment Certifications</h2>
        <button class="btn btn-ql" @click="addAttCert">Add Attainment</button>
      </div>

      <div v-if="profile.attainment_certs.length" class="d-flex flex-column gap-3">
        <div class="cert-row" v-for="(cert, index) in profile.attainment_certs" :key="index" :class="{ 
            'cert-dragging': movedAttCertId  === cert.attainment_cert_id,
            'cert-error': (errors[`attainTitle_${index}`] || errors[`attainURL_${index}`]) && expandedAttCerts !== index
          }"
          @dragenter.prevent
          @dragover.prevent
          @drop="attCertRearange(cert)"
        >

          <!-- header-->
          <div class="d-flex align-items-center gap-2 header" @click="toggleAttCert(index)">
            <button type="button" class="drag-handle-btn" draggable="true" aria-label="Drag to reorder certificates" @dragstart="movedAttCertId = cert.attainment_cert_id" @dragend="movedAttCertId = null" @click.stop>
              <span class="drag-handle-icon" aria-hidden="true">⋮⋮</span>
            </button>
            <img src="@/assets/cert.png" class="cert-icon" alt="certificate" />
            <span class="cert-type-label">{{ cert.title || 'New Certificate' }}</span>
            <button class="remove-btn ms-auto" @click.stop="toggleAttCert(index)">
              <img src="@/assets/triangle.png" class="triangle" :class="{ open: expandedAttCerts === index }" alt="toggle"/>
            </button>
            <button class="remove-btn" @click.stop="removeAttCert(index)">
              <img src="@/assets/delete.png" class="del-icon" alt="remove" />
            </button>
          </div>

          <!--drop down-->
          <div v-if="expandedAttCerts === index"  class="row g-3">
            <div class="col-12 col-md-6">
              <div class="d-flex justify-content-between align-items-center">  
                <label class="field-label">Title</label>
                <label v-if="errors[`attainTitle_${index}`]" class="field-label error-message">*Title cannot be empty</label>
              </div>
              <input v-model.trim="cert.title" maxlength="100" class="field-input form-control" :class="{ 'field-error': errors[`attainTitle_${index}`] }" @input="delete errors[`attainTitle_${index}`]" placeholder="e.g. Certified Engineer" />
            </div>
            <div class="col-12">
              <label class="field-label">Description</label>
              <input v-model.trim="cert.body" class="field-input form-control"
                placeholder="Brief description of this certification" />
            </div>
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">  
                <label class="field-label">File path / URL</label>
                <label v-if="errors[`attainURL_${index}`]" class="field-label error-message">*Must be a valid path</label>
              </div>
              <input v-model.trim="cert.file_path" maxlength="255" :class="{ 'field-error': errors[`attainURL_${index}`] }" @input="delete errors[`attainURL_${index}`]" class="field-input form-control"
                placeholder="https://example.com/cert.pdf" />
            </div>
            <div class="col-12 col-md-4">
              <label class="field-label">Issued date</label>
              <input type="date" v-model.trim="cert.issued_date" class="field-input form-control" />
            </div>
            <div class="col-12 col-md-4">
              <label class="field-label">Expiry date</label>
              <input type="date" v-model.trim="cert.expiry_date" class="field-input form-control" />
            </div>
          </div>

        </div>
      </div>

      <p v-else class="empty-txt text-center py-4">No attainment certifications yet! Click add to get started.</p>
    </section>

    <!--footer-->
    <div class="d-flex justify-content-center gap-3 pt-3">
      <button class="btn btn-filter" @click="handleCancel">Cancel</button>
      <button class="btn btn-ql" @click="saveChanges">Save Changes</button>
    </div>
  </div>

  <div v-else class="container py-5 text-center loading">
    <p class="text-muted small">Loading settings...</p>
  </div>
  <div v-if="popUp.show" class="popUp-msg" :class="popUp.type">
    {{ popUp.message }}
  </div>

    <!--Cancel confirm -->
  <div v-if="showCancelConfirm" class="view-popup" @click.self="showCancelConfirm = false">
    <div class="cancel-box text-center p-4">
      <h5 class="fw-bold mb-2 field-label cancel-title">Cancel editing profile?</h5>
      <p class="field-desc mb-4">All profile changes will be lost.</p>
      <div class="d-flex gap-2 justify-content-center">
        <button class="btn btn-filter" @click="showCancelConfirm = false">Continue editing</button>
        <button class="btn btn-add rounded-pill px-4" @click="cancel">Exit editing</button>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref } from 'vue';
  import { useRouter, useRoute } from 'vue-router';
  import Navbar from '@/components/Navbar.vue'
  import api from "@/services/api";

  // Variables for getting and changing URL information
  const router = useRouter();
  const route = useRoute();

  // Store profile data and loading status
  const profile = ref(null);
  const loading = ref(true);

  // Keep track of the certs that need to be deleted when the user deletes the profile
  const achievementCertsToDelete = ref([]);
  const attainmentCertsToDelete = ref([]);

  // Keep track of what certs have been expanded. Only one cert from each category can be expanded at once
  const expandedAchCerts = ref();
  const expandedAttCerts = ref();

  // Keep track of moved certificates
  const movedAchCertId = ref(null);
  const movedAttCertId = ref(null);

  // Track all errors from user input
  const errors = ref({});

  // Show cancel prompt if user tries to cancel with profile changes and keep track of original profile to check for changes
  const showCancelConfirm = ref(false)
  const originalProfile = ref(null)

  // Object to store data about the popup message
  const popUp = ref({ show: false, message: '', type: '' })
  // Time the popup can be viewed for. Currently set to 3 seconds allow time for the user to view the message
  const popUpTime = 3000

  // Used to display the popup message and the type being either success or error
  const showPopUp = (message, type) => {
    popUp.value = { show: true, message, type }
    setTimeout(() => popUp.value.show = false, popUpTime)
  }

  // Load the student profile data from backend
  const loadProfile = async () => {
    try {
      const response = await  api.get(`/profile/${route.params.id}`);
      profile.value = response.data;
      // Store original profile as a string to check for changes
      originalProfile.value = JSON.stringify(profile.value)
      loading.value = false;
    } catch (error) {
      console.error("Error while fetching profile:", error);
    }
  };

  // Used for drop down for the achievement certificates
  const toggleAttCert = (index) => {
      if (expandedAttCerts.value === index) {
        expandedAttCerts.value = -1;
      } else {
        expandedAttCerts.value = index;
      }  
    }

    // Used for drop down for the attainment certificates
    const toggleAchCert = (index) => {
    if (expandedAchCerts.value === index) {
      expandedAchCerts.value = -1;
    } else {
      expandedAchCerts.value = index;
    }  
  }

  // Reorder the achievement certificate array
  const achCertRearange = (movedCert) => {
    // Find the index in the array where the cert is moving from and where it is moved to
    const oldIndex = profile.value.achievement_certs.findIndex(c => c.achievement_cert_id === movedAchCertId.value)
    const newIndex = profile.value.achievement_certs.findIndex(c => c.achievement_cert_id === movedCert.achievement_cert_id)

    // FindIndex returns -1 if not found, this is just a guard in case
    if (oldIndex !== -1 && newIndex !== -1) {
      // Make a copy of the array in case of error
      const newOrder = [...profile.value.achievement_certs]
      // Deconstructs the array, same as moved[0], Remove one item from old index
      const [moved] =  newOrder.splice(oldIndex, 1)
      // Add moved object to newIndex, remove 0 items
      newOrder.splice(newIndex, 0, moved)

      profile.value.achievement_certs = newOrder
    }
  }

    // Reorder the attainment certificate array
    const attCertRearange = (movedCert) => {
      // Find the index in the array where the cert is moving from and where it is moved to
      const oldIndex = profile.value.attainment_certs.findIndex(c => c.attainment_cert_id === movedAttCertId.value)
      const newIndex = profile.value.attainment_certs.findIndex(c => c.attainment_cert_id === movedCert.attainment_cert_id)

      // FindIndex returns -1 if not found, this is just a guard in case
      if (oldIndex !== -1 && newIndex !== -1) {
        // Make a copy of the array in case of error
        const newOrder = [...profile.value.attainment_certs]
        // Deconstructs the array, same as moved[0], Remove one item from old index
        const [moved] =  newOrder.splice(oldIndex, 1)
        // Add moved object to newIndex, remove 0 items
        newOrder.splice(newIndex, 0, moved)

        profile.value.attainment_certs = newOrder
      }
    }

  // Adds an empty cert to the frontend profile data when add cert is clicked
  const addAchCert = () => {
		profile.value.achievement_certs.unshift({
			title: '',
			body: '',
      file_path: '',
			issued_date: '',
      sort_order: '',
			profile_id: route.params.id
		});
    expandedAchCerts.value = 0;
	};

	const addAttCert = () => {
		profile.value.attainment_certs.unshift({
			title: '',
			body: '',
      file_path: '',
			issued_date: '',
			expiry_date: '',
      sort_order: '',
			profile_id: route.params.id
		});
    expandedAttCerts.value = 0;
	};

	const removeAchCert = (index) => {
    const cert = profile.value.achievement_certs[index];
		if (cert.achievement_cert_id) {
			achievementCertsToDelete.value.push(cert.achievement_cert_id);
		}

		profile.value.achievement_certs.splice(index, 1);

    // Remove errors on delete
    errors.value = {}
	};

	const removeAttCert = (index) => {
		const cert = profile.value.attainment_certs[index];
		if (cert.attainment_cert_id) {
			attainmentCertsToDelete.value.push(cert.attainment_cert_id);
		}
		profile.value.attainment_certs.splice(index, 1);

    // Remove errors on delete
    errors.value = {}
	};

  // Attempt to make a URL object to test if link is correct
  function isValidUrl(url) {
    try {
      new URL(url)
      return true
    } catch {
      return false
    }
  }

  const saveChanges = async () => {
    try {
      // Check to see if any changes have been made. Ignore rest of the logic if no change
      const noChange = JSON.stringify(profile.value) === originalProfile.value
      if (noChange) {
        cancel();
        return;
      }

      errors.value = {}

      // Loop through each cert and check for errors and assign order, entries deconstructs the array into index and entry pairs
      for (const [index, cert] of profile.value.achievement_certs.entries()) {
        if (!cert.title) {
          errors.value[`achieveTitle_${index}`] = true
        }
        if (cert.file_path && !isValidUrl(cert.file_path) && cert.file_path[0] !== '/') {
          errors.value[`achieveURL_${index}`] = true
        }
        // Set the order based off the array
        cert.sort_order = index + 1;
      }

      for (const [index, cert] of profile.value.attainment_certs.entries()) {
        if (!cert.title) {
          errors.value[`attainTitle_${index}`] = true
        }
        if (cert.file_path && !isValidUrl(cert.file_path) && cert.file_path[0] !== '/') {
          errors.value[`attainURL_${index}`] = true
        }
        // Set the order based off the array
        cert.sort_order = index + 1;
      }

      // Check if error object contains any key value pairs by converting it into an array of keys
      if (Object.keys(errors.value).length) {
        showPopUp("Could not save certificates. Please fix highlighted fields.", "error");
        return;
      }

      // Deletions (Handles both types of certs)
      const deleteAchPromises = achievementCertsToDelete.value.map(id => api.delete(`/achievement-cert/${id}`));
      const deleteAttPromises = attainmentCertsToDelete.value.map(id => api.delete(`/attainment-cert/${id}`));

      // Handle Achievement Upserts
      const achUpsertPromises = profile.value.achievement_certs.map(cert => {
        if (!cert.title || cert.title.trim() === '') return null;

        if (cert.achievement_cert_id) {
          return api.put(`/achievement-cert/${cert.achievement_cert_id}`, cert);
        } else {
          return api.post(`/achievement-cert`, { ...cert, profile_id: route.params.id });
        }
      }).filter(p => p !== null);

      // Handle Attainment Upserts
      const attUpsertPromises = profile.value.attainment_certs.map(cert => {
        if (!cert.title || cert.title.trim() === '') return null;

        if (cert.attainment_cert_id) {
          return api.put(`/attainment-cert/${cert.attainment_cert_id}`, cert);
        } else {
          return api.post(`/attainment-cert`, { ...cert, profile_id: route.params.id });
        }
      }).filter(p => p !== null);

      // Execute all API calls concurrently
      await Promise.all([
          ...deleteAchPromises, 
          ...deleteAttPromises, 
          ...achUpsertPromises, 
          ...attUpsertPromises
      ]);
      
      // Clear tracking arrays
      achievementCertsToDelete.value = [];
      attainmentCertsToDelete.value = [];

      // Add a post to student actions for updated certificates
      await api.post(`/student-actions/new`, {action: "Updated certificates", student_profile_id: route.params.id});
      
      // Redirect back to the view page
      router.push({ name: 'profile', params: { id: route.params.id }, query: { tab: 'CERTIFICATIONS' } });
    } catch (error) {
      showPopUp("There was an error saving your certifications.", "error");
    }
  };

  // Check if profile has been changed, if so load cancel confirmation, else don't prompt the user
  const handleCancel = () => {
    // Convert objects so strings and compare for any changes
    const noChange = JSON.stringify(profile.value) !== originalProfile.value
    if (noChange) {
      showCancelConfirm.value = true
    } else {
      cancel()
    }
  }

  // Redirect back to profile page without saving changes
  const cancel = () => {
    router.push({ name: 'profile', params: { id: route.params.id } });
  };

  loadProfile();
</script>

<style scoped>
.sec-title {
    font-family: 'Martel', serif;
    font-size: 2rem;
    color: #303030c5;
  }

  .cert-row {
    background: #f7f7f7;
    border: 1px solid #cccccc;
    border-radius: 2rem;
    padding: 1.5rem 1.75rem;
    transition: box-shadow 0.2s ease;
  }
  
  .cert-row:hover {
    box-shadow: 0 0.5rem 1.5rem #e5e5e5;
  }
  
  .cert-icon {
    width: 1.3rem;
    height: 1.3rem;
    object-fit: contain;
    opacity: 0.6;
  }
  
  .cert-type-label {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 0.85rem;
    color: #aaaaaa;
  }
  
  .remove-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.2rem;
    opacity: 0.6;
    transition: transform 0.2s ease, opacity 0.2s ease;
  }
  
  .remove-btn:hover {
    transform: scale(1.15);
    opacity: 1;
  }
  
  .del-icon {
    width: 1.3rem;
    height: 1.3rem;
    object-fit: contain;
  }
  
  .field-label {
    display: block;
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 0.8rem;
    color: #999999;
    margin-bottom: 0.3rem;
  }
  
  .field-input.form-control {
    font-family: 'Maven Pro', sans-serif;
    font-size: 1rem;
    color: #333333;
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 0.75rem;
  }
  
  .field-input.form-control:focus {
    border-color: #c4c4c4;
    box-shadow: 0 0 0 0.02rem #2b2b2b;
  }
  
  .empty-txt {
    font-family: 'Maven Pro', sans-serif;
    font-size: 1rem;
    color: #aaaaaa;
  }
  
  .btn-filter {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 1rem;
    border-radius: 1.5rem;
    background: #e6e6e6;
    color: #222222;
    padding: 0.5rem 2rem;
  }
  
  .btn-filter:hover {
    background: #666666;
    color: #ffffff;
  }
  
  .btn-ql {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 1rem;
    color: #ffffff;
    background: #555555;
    border-radius: 1.5rem;
    padding: 0.5rem 2rem;
  }
  
  .btn-ql:hover {
    color: #ffffff;
    background: #333333;
  }

  .loading {
    min-height: calc(100vh);
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

  .triangle {
    width: 0.8rem;
    height: 0.8rem;
    transition: transform 0.2s ease;
  }

  .triangle.open {
    transform: rotate(90deg);
  }

  /* Give the header a negative margin so can click the whole header to extend the cert */
  .header {
    cursor: pointer; 
    margin: -1.5rem -1.75rem; 
    padding: 1.5rem 1.75rem;
  }

  .cert-dragging {
    opacity: 0.55;
  }

  .cert-drop-target {
    background: #eef5ff;
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

  .field-input.form-control.field-error {
    border-color: #db7979;
    background: #fff5f5;
    box-shadow: #db7979;
  }

  .error-message {
    color:  #db7979;
  }

  .cert-error {
    border-color: #db7979;
    background: #fff5f5;
  }

  .cancel-box {
    background: #ffffff;
    border-radius: 1.25rem;
    max-width: 22.5rem;
    width: 100%;
    box-shadow: 0 1.25rem 3.75rem rgba(0, 0, 0, 0.2);
  }

  .field-desc {
    font-family: 'Maven Pro', sans-serif;
    font-size: 0.8rem;
    line-height: 1.5;
    color: #444444;
  }

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

  .cancel-box .btn-filter,
  .cancel-box .btn-add {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
  }

  .cancel-box .btn-add {
    background: #555555;
    color: #ffffff;
  }

  .cancel-box .btn-add:hover {
    background: #333333;
    color: #ffffff;
  }

  .cancel-title {
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 1.1rem;
    color: #222222;
  }
    
  @media (min-width: 820px) {
      .container-lg {
          max-width: 60%;
      }
  }
</style>