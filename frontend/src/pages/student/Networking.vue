<template>
  <div class="page">
    <Navbar />

    <section class="container-lg py-5">
      <div class="pitch-box">
        <label class="pitch-label"> Elevator pitch</label>
        <textarea v-model="elevatorPitch" class="pitch-textarea" placeholder="Write your elevator pitch here..."></textarea>

        <div class="pitch-actions">
          <button class="action-button small-button" @click="saveElevatorPitch" :disabled="savingPitch">{{ savingPitch ? 'Saving...' : 'Save' }}</button>
        </div>
      </div>
      <!-- HEADER -->
      <div class="header">
        <div class="title-wrap">
          <!-- <img
            src="https://cdn-icons-png.flaticon.com/512/174/174857.png"
            class="linkedin-icon"
            alt="LinkedIn"
          /> -->
          <h2 class="page-title">Industry Contacts</h2>

          <div class="networking-switch">
            <RouterLink :to="`/student/networking/${route.params.id ||1}`" class="switch-pill"> Events Calender </RouterLink>
            <RouterLink :to="`/student/networking/contacts/${route.params.id || 1}`" class="switch-pill active"> Industry Contacts</RouterLink>
          </div>
        </div>

        <button class="btn btn-dark btn-main" @click="openForm">
          + Add Contact
        </button>
      </div>

      <!-- SEARCH + SORT -->
      <div class="controls">
        <input
          v-model="search"
          class="search"
          placeholder="Search contacts"
        />

        <select v-model="sortBy" class="sort">
          <option disabled value="">Sort</option>
          <option value="name_asc">A-Z</option>
          <option value="name_desc">Z-A</option>
          <option value="date_desc">Newest</option>
          <option value="date_asc">Oldest</option>
        </select>
      </div>

      <!-- CARDS -->
      <div class="card-grid">
        <div
          v-for="c in sortedContacts"
          :key="c.contact_id"
          class="contact-card"
          @click="openDetails(c)"
        >

          <!-- MENU -->
          <div class="menu-wrapper" @click.stop>
            <button class="menu-btn" @click="toggleMenu(c.contact_id)">
              ⋯
            </button>

            <div v-if="openMenuId === c.contact_id" class="dropdown">
              <ButtonsStyle
                @edit="editContact(c)"
                @delete="deleteContact(c.contact_id)"
              />
            </div>
          </div>

          <!-- CARD -->
          <div class="card-top">
            <div class="avatar">
              <img :src="getAvatar(c.contact_name)" />
            </div>

            <div class="info">
              <h3 class="contact-name">{{ c.contact_name }}</h3>

              <p class="meta">📅 {{ c.date_met }}</p>

            </div>
          </div>

        </div>
      </div>

      <!-- DETAILS MODAL -->
      <div v-if="selectedContact" class="modal-overlay" @click.self="selectedContact = null">
        <div class="modal-box">

          <h3>{{ selectedContact.contact_name }}</h3>

          <p><b>Company:</b> {{ selectedContact.company }}</p>
          <p><b>Date Met:</b> {{ selectedContact.date_met }}</p>

          <p v-if="selectedContact.link_url">
            <b>Link:</b>
            <a :href="formatUrl(selectedContact.link_url)" target="_blank" rel="noopener noreferrer">Open Link</a>
          </p>


          <div class="btn-row">
            <ButtonsStyle
              @edit="editContact(selectedContact)"
              @delete="deleteContact(selectedContact.contact_id)"
            />
            <button class="btn btn-light" @click="selectedContact = null">
              Close
            </button>
          </div>

        </div>
      </div>

      <!-- FORM -->
      <div v-if="showForm" class="modal-overlay" @click.self="closeForm">
        <div class="modal-box">

          <h3>{{ editMode ? "Edit Contact" : "Create Contact" }}</h3>

          <input v-model="form.contact_name" placeholder="Name" />
          <input v-model="form.company" placeholder="Company" />
          <input v-model="form.link_url" type="url" placeholder="Link" />
          <input type="date" v-model="form.date_met" />

          <div class="btn-row">
            <button class="btn btn-dark" @click="saveContact">
              {{ editMode ? "Update" : "Create" }}
            </button>

            <button class="btn btn-light" @click="closeForm">
              Cancel
            </button>
          </div>

        </div>
      </div>

      <div v-if="showPitchDialog" class="confirm-overlay" @click.self="closePitchDialog">
        <div class="confirm-widget">
          <p class="confirm-title">{{ pitchDialog.title }}</p>
          <p class="confirm-message">{{ pitchDialog.message }}</p>
          <div class="confirm-actions">
            <button class="action-button small-button" @click="closePitchDialog">{{ pitchDialog.buttonLabel }}</button>
          </div>
        </div>
      </div>

      <div v-if="showConfirmDialog" class="confirm-overlay" @click.self="resolveConfirmDialog(false)">
        <div class="confirm-widget">
          <p class="confirm-title">{{confirmDialog.title }}</p>
          <p class="confirm-message">{{confirmDialog.message }}</p>
          <div class="confirm-actions">
            <button class="ghost-button small-button" @click="resolveConfirmDialog(false)">{{ confirmDialog.cancelLabel }}</button>
            <button class="small-button" :class="confirmDialog.variant === 'danger' ? 'delete-button' : 'action-button'" @click="resolveConfirmDialog(true)">
              {{ confirmDialog.confirmLabel }}
            </button>
          </div>
        </div>
      </div>

    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import api from "@/services/api";

import Navbar from "@/components/Navbar.vue";
import ButtonsStyle from "@/components/ButtonsStyle.vue";


const route = useRoute();
const profileId = computed(() =>(route.params.id || 1));
const contacts = ref([]);
const search = ref("");
const filterBy = ref("all");
const sortBy = ref("");

const showForm = ref(false);
const editMode = ref(false);
const selectedContact = ref(null);
const openMenuId = ref(null);

const elevatorPitch = ref("");
const savingPitch =ref(false);
const showPitchDialog = ref(false);
const showConfirmDialog = ref(false);

const confirmDialog = ref({
  title: "",
  message: "",
  confirmLabel: "Confirm",
  cancelLabel: "Cancel",
  variant: "default",
});
let confirmResolver = null;

const pitchDialog = ref({
  title: "",
  message: "",
  buttonLabel: "OK",
});

const form = ref({
  contact_id: null,
  contact_name: "",
  company: "",
  link_url: "",
  date_met: "",
  linkedin_url: "",
});

/* FETCH */
const fetchContacts = async () => {
  const res = await api.get(`/users/${profileId.value}/industry-contacts`);
  contacts.value = res.data;
};

const fetchElevatorPitch = async () => {
  const res = await api.get(`/profile/${route.params.id}/elevator-pitch`);
  elevatorPitch.value = res.data.pitch_text || "";
}

const saveElevatorPitch = async () => {
  const trimmedPitch = elevatorPitch.value.trim();

  if (!trimmedPitch) {
    openPitchDialog("Elevator Pitch", "It's empty. Please enter something first.");
    return;
  }

  savingPitch.value = true;

  try{
    await api.put(`/profile/${route.params.id}/elevator-pitch`,{
      pitch_text: trimmedPitch,
    });

    openPitchDialog("Saved", "Your elevator pitch has been saved.");
  } finally {
    savingPitch.value = false;
  }
};

onMounted(() => {
  fetchContacts();
  fetchElevatorPitch();
});

/* FILTER */
const filteredContacts = computed(() => {
  return contacts.value.filter(c =>
    c.contact_name?.toLowerCase().includes(search.value.toLowerCase())
  );
});

/* SORT */
const sortedContacts = computed(() => {
  let list = [...filteredContacts.value];

  switch (sortBy.value) {
    case "name_asc":
      return list.sort((a, b) => a.contact_name.localeCompare(b.contact_name));
    case "name_desc":
      return list.sort((a, b) => b.contact_name.localeCompare(a.contact_name));
    case "date_desc":
      return list.sort((a, b) => new Date(b.date_met) - new Date(a.date_met));
    case "date_asc":
      return list.sort((a, b) => new Date(a.date_met) - new Date(b.date_met));
    default:
      return list;
  }
});

/* HELPERS */


const getAvatar = (name) => {
  return `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=111&color=fff&size=64`;
};

const formatUrl = (url) => {
  if(!url) return "";
  return url.startsWith("http://") || url.startsWith("https://") ? url : `https://${url}`;
};

const openPitchDialog = (title, message) => {
  pitchDialog.value = {
    title,
    message,
    buttonLabel: "OK",
  };
  showPitchDialog.value = true;
};

const closePitchDialog = () => {
  showPitchDialog.value = false;
};

const openConfirmDialog = (options) => {
  confirmDialog.value = {
    title: "",
    message: "",
    confirmLabel: "Confirm",
    cancelLabel: "Cancel",
    variant: "default",
    ...options,
  };

  showConfirmDialog.value = true;

  return new Promise((resolve) => {
    confirmResolver = resolve;
  });
};

const resolveConfirmDialog = (result) => {
  showConfirmDialog.value = false;

  if(confirmResolver) {
    confirmResolver(result);
    confirmResolver = null;
  }
};
/* ACTIONS */
const toggleMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id;
};

const openDetails = (c) => {
  selectedContact.value = c;
};

const openForm = () => {
  editMode.value = false;
  form.value = {
    contact_id: null,
    contact_name: "",
    company: "",
    link_url: "",
    date_met: "",
    linkedin_url: "",
  };
  showForm.value = true;
};

const closeForm = () => {
  showForm.value = false;
};

const saveContact = async () => {
  const payload = { ...form.value };
  const trimmedLink = (payload.link_url || "").trim();

  if(trimmedLink) {
    try {
      const parsedUrl = new URL(trimmedLink);

      if((parsedUrl.protocol !== "http:" && parsedUrl.protocol !=="https:") || !parsedUrl.hostname.includes(".")){
        throw new Error("Invalid link");
      }

      payload.link_url = trimmedLink;
    } catch {
      openPitchDialog("Invalid Link", "This is not a valid link. Please enter a full link, for example: https://linkedin.com/in/your-name");
      return;
    }
  }

  if(editMode.value) {
    const shouldUpdate = await openConfirmDialog({
      title: "Confirm update",
      message: "Save these changes to this contact?",
      confirmLabel: "Update",
      cancelLabel: "Undo",
    });

    if(!shouldUpdate) {
      return;
    }
  }

  try {
    if(editMode.value) {
      await api.put(`/users/${profileId.value}/industry-contacts/${form.value.contact_id}`,payload);
    } else {
      await api.post(`/users/${profileId.value}/industry-contacts`,payload);
    }

    closeForm();
    await fetchContacts();
  } catch (error) {
    const linkErrors = error.response?.data?.errors?.link_url;

    if (linkErrors?.length) {
      openPitchDialog("Invalid Link", "This is not a valid link. Please enter a full link, for example https://linkedin.com/in/your-name");
      return;
    }

    console.error("Save contact failed:", error);

    if(error.response) {
      openPitchDialog("Save Failed", "Something went wrong while saving this contact.");
    } else {
      openPitchDialog("Save Failed", "Please check your connection and try again.");
    }
  }
};


const editContact = (c) => {
  selectedContact.value = null;
  openMenuId.value = null;

  editMode.value = true;
  form.value = { ...c };
  showForm.value = true;
};

const deleteContact = async (id) => {
  const shouldDelete = await openConfirmDialog({
    title: "Confirm delete",
    message: "Delete this contact?",
    confirmLabel: "Delete",
    cancelLabel: "Keep",
    variant: "danger",
  });

  if(!shouldDelete){
    return;
  }

  await api.delete(`/users/${profileId.value}/industry-contacts/${id}`);
  selectedContact.value = null;
  openMenuId.value = null;
  fetchContacts();
};
</script>

<style scoped>
.page {
  font-family: 'Maven Pro', sans-serif;
  background: #f4f6f8;
  min-height: 100vh;
}

/* HEADER */
.header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 15px;
}

.title-wrap {
  display: flex;
  align-items: center;
  gap: 8px;
}

.linkedin-icon {
  width: 24px;
}

/* CONTROLS */
.controls {
  display: flex;
  gap: 10px;
  margin-bottom: 15px;
}

.search {
  width: 240px;
  padding: 8px;
  border-radius: 10px;
  border: 1px solid #ddd;
  font-size: 0.85rem;
}

.sort {
  padding: 8px;
  border-radius: 10px;
  border: 1px solid #ddd;
  font-size: 0.85rem;
}

/* GRID */
.card-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 14px;
}

/* CARD */
.contact-card {
  background: white;
  border-radius: 12px;
  padding: 14px;
  position: relative;
  cursor: pointer;
  box-shadow: 0 3px 12px rgba(0,0,0,0.05);
}

/* MENU */
.menu-wrapper {
  position: absolute;
  top: 8px;
  right: 8px;
}

.menu-btn {
  background: none;
  border: none;
  font-size: 18px;
  cursor: pointer;
}

/* DROPDOWN */
.dropdown {
  position: absolute;
  right: 0;
  top: 24px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* CARD CONTENT */
.card-top {
  display: flex;
  gap: 10px;
  align-items: center;
}

.avatar img {
  width: 36px;
  height: 36px;
  border-radius: 50%;
}

.contact-name {
  font-size: 0.85rem;
  font-weight: 600;
}

.meta {
  font-size: 0.7rem;
  color: #777;
}

.linkedin-link {
  font-size: 0.7rem;
  color: #0a66c2;
  text-decoration: none;
}

/* MODAL */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.4);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-box {
  background: white;
  padding: 20px;
  border-radius: 12px;
  width: 380px;
}

.modal-box input,
.modal-box textarea {
  width: 100%;
  margin-bottom: 10px;
  padding: 8px;
  border-radius: 10px;
  border: 1px solid #ddd;
}

.btn-row {
  display: flex;
  gap: 10px;
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
  background: #172334;
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

.action-button {
  background: #13202c;
  color: #ffffff;
  padding: 0.85rem 1.4rem;
  border: 1px solid #13202c;
  border-radius: 999px;
  cursor: pointer;
  transition:
    transform 0.18s ease,
    background-color 0.18s ease,
    border-color 0.18s ease;
}

.action-button:hover {
  transform: translateY(-1px);
}

.small-button {
  padding: 0.55rem 0.95rem;
  font-size: 0.85rem;
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

.confirm-message{
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

.ghost-button {
  background: #f5f8fb;
  border: 1px solid #d4dfe9;
  color: #2d4658;
  border-radius: 999px;
  cursor: pointer;
}

.delete-button {
  background: #fff1f1;
  border: 1px solid #f3c6c6;
  color: #a63f3f;
  border-radius: 999px;
  cursor: pointer;
}
</style>

