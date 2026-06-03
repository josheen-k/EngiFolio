<template>
  <div class="contacts-page">
    <section class="container-lg py-5">
      <div class="header">
        <h2 class="page-title">Industry Contacts</h2>

        <button class="action-button" @click="openForm">
          + Add Contact
        </button>
      </div>

      <div class="controls">
        <input
          v-model="search"
          class="search"
          placeholder="Search contacts"
        />

        <select v-model="sortBy" class="sort">
          <option disabled value="">Sort</option>
          <option value="name_asc">A-Z</option>
          <option value="date_desc">Newest</option>
          <option value="date_asc">Oldest</option>
        </select>
      </div>

      <div class="card-grid">
        <div
          v-for="c in sortedContacts"
          :key="c.contact_id"
          class="contact-card"
          @click="openDetails(c)"
        >
          <div class="menu-wrapper" @click.stop>
            <button
              class="menu-btn"
              type="button"
              aria-label="Open contact actions"
              @click="toggleMenu(c.contact_id)"
            >
              &hellip;
            </button>

            <div v-if="openMenuId === c.contact_id" class="dropdown">
              <ButtonsStyle
                @edit="editContact(c)"
                @delete="deleteContact(c.contact_id)"
              />
            </div>
          </div>

          <div class="card-top">
            <div class="avatar">
              <img :src="getAvatar(c.contact_name)" />
            </div>

            <div class="info">
              <h3 class="contact-name">{{ c.contact_name }}</h3>
              <p class="meta">📅 {{ c.date_met || 'No date added' }}</p>
            </div>
          </div>
        </div>
      </div>

      <div
        v-if="selectedContact"
        class="modal-overlay"
        @click.self="selectedContact = null"
      >
        <div class="modal-box">
          <h3>{{ selectedContact.contact_name }}</h3>

          <p><b>Company:</b> {{ selectedContact.company || 'Not specified' }}</p>
          <p><b>Date Met:</b> {{ selectedContact.date_met || 'Not specified' }}</p>

          <p>
            <b>LinkedIn:</b>

            <a
              v-if="selectedContact.link_url"
              :href="formatUrl(selectedContact.link_url)"
              target="_blank"
              rel="noopener noreferrer"
            >
              View LinkedIn
            </a>

            <span v-else>
              Not added
            </span>
          </p>

          <p class="notes-title"><b>Progress Notes</b></p>

          <p class="notes-body">
            {{
              selectedContact.progress_notes ||
              'No progress notes added yet.'
            }}
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

      <div v-if="showForm" class="modal-overlay" @click.self="closeForm">
        <div class="modal-box">
          <h3>{{ editMode ? 'Edit Contact' : 'Create Contact' }}</h3>

          <input v-model="form.contact_name" placeholder="Name" />
          <input v-model="form.company" placeholder="Company" />

          <textarea
            v-model="form.progress_notes"
            placeholder="Progress notes"
          ></textarea>

          <input
            v-model="form.link_url"
            type="url"
            placeholder="LinkedIn URL"
          />

          <input type="date" v-model="form.date_met" />

          <div class="btn-row">
            <button class="btn btn-dark" @click="saveContact">
              {{ editMode ? 'Update' : 'Create' }}
            </button>

            <button class="btn btn-light" @click="closeForm">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </section>
  </div>
  <div v-if="popUp.show" class="popUp-msg" :class="popUp.type">
    {{ popUp.message }}
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/services/api'
import ButtonsStyle from '@/components/ButtonsStyle.vue'

const route = useRoute()
const profileId = computed(() => route.params.id || 1)

const contacts = ref([])
const search = ref('')
const sortBy = ref('')

const showForm = ref(false)
const editMode = ref(false)
const selectedContact = ref(null)
const openMenuId = ref(null)

const form = ref({
  contact_id: null,
  contact_name: '',
  company: '',
  progress_notes: '',
  link_url: '',
  date_met: '',
})

// Object to store data about the popup message
const popUp = ref({ show: false, message: '', type: '' })
// Time the popup can be viewed for. Currently set to 3 seconds allow time for the user to view the message
const popUpTime = 3000

// Used to display the popup message and the type being either success or error
const showPopUp = (message, type) => {
  popUp.value = { show: true, message, type }
  setTimeout(() => popUp.value.show = false, popUpTime)
}


const fetchContacts = async () => {
  const res = await api.get(`/users/${profileId.value}/industry-contacts`)
  contacts.value = res.data
}

onMounted(() => {
  fetchContacts()
})

const filteredContacts = computed(() => {
  return contacts.value.filter(c =>
    c.contact_name?.toLowerCase().includes(search.value.toLowerCase()) ||
    c.company?.toLowerCase().includes(search.value.toLowerCase()) ||
    c.progress_notes?.toLowerCase().includes(search.value.toLowerCase())
  )
})

const sortedContacts = computed(() => {
  const list = [...filteredContacts.value]

  switch (sortBy.value) {
    case 'name_asc':
      return list.sort((a, b) => a.contact_name.localeCompare(b.contact_name))
    case 'date_desc':
      return list.sort((a, b) => new Date(b.date_met) - new Date(a.date_met))
    case 'date_asc':
      return list.sort((a, b) => new Date(a.date_met) - new Date(b.date_met))
    default:
      return list
  }
})

const getAvatar = (name) => {
  return `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=111&color=fff&size=64`
}

const formatUrl = (url) => {
  if (!url) return ''

  return url.startsWith('http://') || url.startsWith('https://')
    ? url
    : `https://${url}`
}

const toggleMenu = (id) => {
  openMenuId.value = openMenuId.value === id ? null : id
}

const openDetails = (contact) => {
  selectedContact.value = contact
}

const openForm = () => {
  editMode.value = false

  form.value = {
    contact_id: null,
    contact_name: '',
    company: '',
    progress_notes: '',
    link_url: '',
    date_met: '',
  }

  showForm.value = true
}

const closeForm = () => {
  showForm.value = false
}

const saveContact = async () => {
  try {
    if (!form.value.contact_name.trim()) {
      showPopUp('Contact name is required.', 'error')
      return
    }

    const payload = {
      contact_name: form.value.contact_name,
      company: form.value.company,
      progress_notes: form.value.progress_notes,
      link_url: form.value.link_url,
      date_met: form.value.date_met,
    }

    if (editMode.value) {
      await api.put(
        `/users/${profileId.value}/industry-contacts/${form.value.contact_id}`,
        payload
      )
      showPopUp(`${payload.contact_name}  successfully updated.`, 'success')
    } else {
      await api.post(`/users/${profileId.value}/industry-contacts`, payload)
      showPopUp(`New contact ${payload.contact_name} successfully added.`, 'success')
    }

    closeForm()
    fetchContacts()
  } catch (error) {
    showPopUp('Save Failed. Something went wrong while saving this contact.', 'error')
  }
}

const editContact = (contact) => {
  selectedContact.value = null
  openMenuId.value = null
  editMode.value = true

  form.value = {
    contact_id: contact.contact_id,
    contact_name: contact.contact_name || '',
    company: contact.company || '',
    progress_notes: contact.progress_notes || '',
    link_url: contact.link_url || '',
    date_met: contact.date_met || '',
  }

  showForm.value = true
}

const deleteContact = async (id) => {
  try {
    await api.delete(`/users/${profileId.value}/industry-contacts/${id}`)
    selectedContact.value = null
    openMenuId.value = null
    fetchContacts()
    showPopUp('Contact successfully deleted.', 'error')
  } catch (err) {
    showPopUp('Error. Cannot delete contact.', 'error')
  }
}
</script>

<style scoped>
.contacts-page {
  font-family: 'Maven Pro', sans-serif;
}

.page-title {
  font-size: 2.5rem;
  font-family: 'Martel', sans-serif;
}

.header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 15px;
}

.controls {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 24px;
}

.search {
  width: 360px;
  max-width: 100%;
  padding: 1rem 1.25rem;
  border-radius: 1.1rem;
  border: 1px solid #d6dde6;
  font-size: 0.95rem;
  color: #223446;
  background: #ffffff;
}

.sort {
  min-width: 128px;
  padding: 0.95rem 1rem;
  border-radius: 1rem;
  border: 1px solid #d6dde6;
  font-size: 0.95rem;
  color: #223446;
  background: #ffffff;
}

.card-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1.4rem;
}

.contact-card {
  background: white;
  border-radius: 12px;
  padding: 14px;
  position: relative;
  cursor: pointer;
  box-shadow: 0 3px 12px rgba(0,0,0,0.05);
}

.contact-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 30px rgba(26, 42, 58, 0.1);
}

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

.dropdown {
  position: absolute;
  right: 0;
  top: 24px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.card-top {
  display: flex;
  gap: 10px;
  align-items: center;
}

.avatar {
  flex: 0 0 auto;
}

.avatar img {
  width: 36px;
  height: 36px;
  border-radius: 50%;
}

.info {
  min-width: 0;
}

.contact-name {
  font-size: 0.85rem;
  font-weight: 600;
}

.meta {
  font-size: 0.7rem;
  color: #777;
}

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

.notes-title {
  margin-top: 14px;
  margin-bottom: 6px;
  color: #24364b;
}

.notes-body {
  background: #f8fafc;
  border: 1px solid #dbe4ee;
  border-radius: 10px;
  padding: 12px;
  line-height: 1.6;
  color: #44576b;
  white-space: pre-wrap;
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

.btn {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 30px;
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

@media (max-width: 768px) {
  .header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }

  .search {
    width: 100%;
  }

  .sort {
    width: 100%;
  }

  .card-grid {
    grid-template-columns: 1fr;
  }
}
</style>
