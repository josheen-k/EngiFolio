<template>
  <Navbar />
  <div class="edit-wrap" v-if="profile">
    <h2 class="sec-title text-center mb-5">Edit Profile</h2>

    <!--edit pfp-->
    <section class="edit-card mb-4">
      <h3 class="card-title mb-4">Profile Image</h3>
      <div class="d-flex align-items-center gap-4">
        <img :src="profile.profile_image_url || '/src/assets/default.jpg'" @error="(e) => e.target.src = '/default.jpg'"
        class="profile-pic" style="flex-shrink: 0;"/>

        <div class="flex-grow-1">
          <label class="field-label">Profile Image URL</label>
          <input v-model.trim.lazy="profile.profile_image_url" maxlength="255" class="field-input form-control"
            placeholder="Link to your profile picture"/>
        </div>
      </div>
    </section>

    <!--edit basic info-->
    <section class="edit-card mb-4">
      <h3 class="card-title mb-4">Basic Information</h3>
      <div class="row g-3">
        <div class="col-12 col-sm-4">
          <label class="field-label">First name</label>
          <input v-model.trim="profile.user.first_name" maxlength="50" class="field-input form-control" placeholder="First name"/>
        </div>
        <div class="col-12 col-sm-4">
          <div class="d-flex justify-content-between align-items-center">          
            <label class="field-label">Last name</label>
            <!-- Error message -->
            <label v-if="errors.lastName" class="field-label error-message">*Last name cannot be empty</label>
          </div>
          <!-- Change class if error or delete error if the user changes the input text -->
          <input v-model.trim="profile.user.last_name" maxlength="50" class="field-input form-control" :class="{ 'field-error': errors.lastName }" @input="delete errors.lastName" placeholder="Last name"/>
        </div>
        <div class="col-12 col-sm-4">
          <label class="field-label">Preferred name</label>
          <input v-model.trim="profile.preferred_name" maxlength="50" class="field-input form-control" placeholder="Preferred name"/>
        </div>
        <div class="col-12 col-sm-6">
          <label class="field-label">Degree undertaking</label>
          <input v-model.trim="profile.degree_title" maxlength="40" class="field-input form-control"
            placeholder="eg: Bachelor of Engineering"/>
        </div>
        <div class="col-12 col-sm-6">
          <label class="field-label">Specialisation chosen</label>
          <input v-model.trim="profile.specialisation" maxlength="60"  class="field-input form-control" placeholder="eg: Electrical"/>
        </div>
      </div>
    </section>

    <!--edit personal intro-->
    <section class="edit-card mb-4">
      <h3 class="card-title mb-4">Personal Introduction</h3>
      <label class="field-label">About you</label>
      <textarea v-model.trim="profile.personal_intro" maxlength="500" class="field-input form-control" rows="5"
        placeholder="Write a short introduction about yourself…"></textarea>
    </section>

    <!--edit links-->
    <section class="edit-card mb-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="card-title mb-0">Professional Links</h3>
        <button class="btn btn-add" @click="addLink">Add link</button>
      </div>

      <div v-if="profile.links.length" class="d-flex flex-column gap-3">
        <div class="link-edit-row" v-for="(link, index) in profile.links" :key="index">
          <div class="row g-2 align-items-end">
            <div class="col-12 col-sm-4">
              <div class="d-flex justify-content-between align-items-center">  
                <label class="field-label">Label</label>
                <!-- Error message -->
                <label v-if="errors[`linkLabel_${index}`]" class="field-label error-message">*Link label cannot be empty</label>
              </div> 
              <input v-model.trim="link.link_label" class="field-input form-control" :class="{ 'field-error': errors[`linkLabel_${index}`] }" @input="delete errors[`linkLabel_${index}`]" placeholder="e.g. LinkedIn"/>
            </div>
            <div class="col-12 col-sm-6">
              <div class="d-flex justify-content-between align-items-center">  
                <label class="field-label">URL</label>
                <!-- Error message -->
                <label v-if="errors[`linkUrl_${index}`]" class="field-label error-message">*Link URL must be valid</label>
              </div> 
              <input v-model.trim="link.link_url" class="field-input form-control" :class="{ 'field-error': errors[`linkUrl_${index}`] }" @input="delete errors[`linkUrl_${index}`]" placeholder="https://example.com"/>
            </div>
            <div class="col-12 col-sm-2 d-flex align-items-end">
              <button class="remove-btn" @click="removeLink(index)" title="Remove link">
                <img src="@/assets/delete.png" class="del-icon" alt="remove" />
              </button>
            </div>
          </div>
        </div>
      </div>

      <p v-else class="empty-txt text-center py-2">No links yet! Click add link to get started.</p>
    </section>

    <!--footer actions-->
    <footer class="pt-4 d-flex justify-content-end gap-2">
      <button class="btn btn-filter" @click="handleCancel">Cancel</button>
      <button class="btn btn-ql" @click="saveChanges">Save Changes</button>
    </footer>
    <div v-if="popUp.show" class="popUp-msg" :class="popUp.type">
      {{ popUp.message }}
    </div>
  </div>

  <div v-else class="container py-5 text-center loading">
    <p class="text-muted small">Loading settings...</p>
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
  import { ref, onMounted } from 'vue';
  import { useRouter, useRoute } from 'vue-router';
  import Navbar from '@/components/Navbar.vue'
  import api from "@/services/api";

  const router = useRouter();
  const route = useRoute();
  const profile = ref(null);
  const loading = ref(true);
  const linksToDelete = ref([]);
  const errors = ref({});
  const showCancelConfirm = ref(false)
  const originalProfile = ref(null)

  // Set up a pop up notification instead of having an alert
  const popUp = ref({ show: false, message: '', type: '' })

  const showPopUp = (message, type) => {
    popUp.value = { show: true, message, type }
    setTimeout(() => popUp.value.show = false, 3000)
  }



  const loadProfile = async () => {
    // Get profile data, throw error if unsuccessful
    try {
      const response = await  api.get(`/profile/${route.params.id}`);
      profile.value = response.data.profile || response.data;
      // Store original profile as a string to check for changes
      originalProfile.value = JSON.stringify(profile.value)
      loading.value = false;
    } catch (error) {
      console.error("Error while fetching profile:", error);
    }
  };

  // Adds an empty link to the frontend profile data when add link is clicked
  // Restricted to 8 links
  const addLink = () => {
    if (profile.value.links.length - linksToDelete.value.length < 8) {
      profile.value.links.push({
        link_label: '',
        link_url: '',
        profile_id: route.params.id
      });
    } else {
      showPopUp("You have too many links. Edit or delete some of your existing.", "error");
    }
  };

const removeLink = (index) => {
  const link = profile.value.links[index];
    if (link.link_id) {
      linksToDelete.value.push(link.link_id);
    }  
    profile.value.links.splice(index, 1);
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
    const hasChanged = JSON.stringify(profile.value) === originalProfile.value
    if (hasChanged) {
      cancel();
      return;
    }

    // Reset errors
    errors.value = {}

    // Check if last name is empty and add to errors if so
    if (!profile.value.user.last_name.trim()) {
      errors.value.lastName = true
    }

    // Remove all links without a label or a url
    profile.value.links = profile.value.links.filter(link => link.link_label || link.link_url);

    // Loop through each link, add entry to errors for the links located at position i
    for (let i = 0; i < profile.value.links.length; i++) {
      const link = profile.value.links[i]
      if (!link.link_label) {
        errors.value[`linkLabel_${i}`] = true
      }
      if (!isValidUrl(link.link_url)) {
        errors.value[`linkUrl_${i}`] = true
      } 
    }


    // Check if error object contains any key value pairs by converting it into an array of keys
    if (Object.keys(errors.value).length) {
      showPopUp("Could not save profile. Please fix highlighted fields.", "error");
      return;
    }

    // Saves the main profile
    await api.put(`/profile/${route.params.id}`, profile.value);
    
    // Deletes the required links
    const deletePromises = linksToDelete.value.map(id => api.delete(`/link/${id}`));

    // Handle Updates and Creations
    const upsertPromises = profile.value.links.map(link => {
      // Ignore empty rows
      if (!link.link_url || link.link_url.trim() === '') return null;

      // Create or update the link
      if (link.link_id) {
        return api.put(`/link/${link.link_id}`, link);
      } else {
        return api.post(`/link`, link);
      }
    }).filter(p => p !== null);

    // Execute all API calls
    await Promise.all([...deletePromises, ...upsertPromises]);
    
    // Clear the delete tracking for next time
    linksToDelete.value = [];

    // Add a post to student actions for an updated profile
    await api.post(`/student-actions/new`, {action: "Updated profile", student_profile_id: route.params.id});

    router.push({ name: 'profile', params: { id: route.params.id } });
  } catch (error) {
    console.error("Save failed:", error);
    showPopUp("There was an error saving your changes.", "error");
  }
};

// Check if profile has been changed, if so load cancel confirmation, else don't prompt the user
const handleCancel = () => {
  // Convert objects so strings and compare for any changes
  const hasChanged = JSON.stringify(profile.value) === originalProfile.value
  if (hasChanged) {
    cancel()
  } else {
    showCancelConfirm.value = true
  }
}

// Redirect back to profile page without saving changes
const cancel = () => {
  router.push({ name: 'profile', params: { id: route.params.id } });
};

onMounted(() => {
  loadProfile();
})
</script>

<style scoped>
.edit-wrap {
  max-width: 60%;
  margin: 0 auto;
  padding: 3rem 1.5rem;
}

.sec-title {
  font-family: 'Martel', serif;
  font-size: 2rem;
  color: #303030c5;
}

.edit-card {
  background: #f7f7f7;
  border: 1px solid #cccccc;
  border-radius: 2rem;
  padding: 1.5rem 1.75rem;
  transition: box-shadow 0.2s ease;
}

.edit-card:hover {
  box-shadow: 0 0.5rem 1.5rem #e5e5e5;
}

.card-title {
  font-family: 'Martel', serif;
  font-size: 1.3rem;
  font-weight: 100;
  color: #808080;
}

.profile-pic {
  width: 7rem;
  height: 7rem;
  border-radius: 50%;
  object-fit: cover;
  border: 2.5px solid #dddddd;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.link-edit-row {
  background: #ffffff;
  border: 1px solid #e0e0e0;
  border-radius: 1.25rem;
  padding: 1rem 1.25rem;
}

.remove-btn {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.2rem;
  opacity: 0.5;
  transition: transform 0.2s ease, opacity 0.2s ease;
}

.remove-btn:hover {
  transform: scale(1.15);
  opacity: 1;
}

.del-icon {
  width: 1.2rem;
  height: 1.2rem;
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

.btn-filter, .btn-add {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  background: #e6e6e6;
  color: #222222;
  padding: 0.5rem 2rem;
}

.btn-filter:hover, .btn-add:hover {
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

.btn-add {
  font-size: 0.8rem;
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

.field-input.form-control.field-error {
  border-color: #db7979;
  background: #fff5f5;
  box-shadow: #db7979;
}

.error-message {
  color:  #db7979;
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

@media (max-width: 768px) {
  .edit-wrap {
    max-width: 100%;
    padding: 2rem 1rem;
  }
}
</style>