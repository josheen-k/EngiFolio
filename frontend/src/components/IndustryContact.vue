<template>
  <div>
    <h2>Industry Contacts</h2>

    <!-- Form to Add / Update -->
    <form @submit.prevent="isEditing ? updateContact() : createContact()">
      <input v-model="form.contact_name" placeholder="Contact Name" required />
      <input v-model="form.email_id" placeholder="Email" required />
      <input v-model="form.company_name" placeholder="Company" required />
      <input type="date" v-model="form.date_met" placeholder="Date Met" />
      <button type="submit">{{ isEditing ? 'Update' : 'Add' }} Contact</button>
      <button type="button" v-if="isEditing" @click="cancelEdit">Cancel</button>
    </form>

    <!-- Error Message -->
    <div v-if="errorMessage">{{ errorMessage }}</div>

    <!-- Show contacts -->
    <div v-if="contacts.length === 0">No contacts yet</div>

    <ul v-else>
      <li v-for="contact in contacts" :key="contact.id">
        {{ contact.contact_name }} - {{ contact.email_id }} - {{ contact.company_name }}
        <button @click="editContact(contact)">Edit</button>
        <button @click="deleteContact(contact.id)">Delete</button>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const contacts = ref([]);
const isEditing = ref(false);
const errorMessage = ref('');
const form = ref({
  id: null,  // This will now hold the `id` field (from DB auto-increment)
  contact_name: '',
  email_id: '',
  company_name: '',
  date_met: ''
});

// Fetch contacts
const fetchContacts = async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/industry-contacts');
    contacts.value = res.data;
  } catch (err) {
    console.error('Error fetching contacts:', err);
  }
};

// Create a new contact
const createContact = async () => {
  try {
    const res = await axios.post('http://127.0.0.1:8000/api/industry-contacts', form.value);
    contacts.value.push(res.data);
    resetForm();
    errorMessage.value = '';  // Reset error message
  } catch (err) {
    console.error('Error creating contact:', err);
    errorMessage.value = 'Failed to create contact. Please try again.';  // Display error message
  }
};

// Prepare form for editing
const editContact = (contact) => {
  isEditing.value = true;
  form.value = { ...contact }; // Copy all contact values into the form
};

// Update existing contact
const updateContact = async () => {
  try {
    const res = await axios.put(
      `http://127.0.0.1:8000/api/industry-contacts/${form.value.id}`,  // Use `id` instead of `contact_id`
      form.value
    );
    console.log('Updated contact:', res.data);
    const index = contacts.value.findIndex(c => c.id === form.value.id);  // Find the contact by `id`
    contacts.value[index] = res.data;  // Replace the old contact with the updated one
    resetForm();
  } catch (err) {
    console.error('Error updating contact:', err);
    errorMessage.value = 'Failed to update contact. Please try again.';  // Display error message
  }
};

// Delete contact
const deleteContact = async (id) => {
  try {
    console.log('Attempting to delete contact with ID:', id);  // Debug log
    const res = await axios.delete(`http://127.0.0.1:8000/api/industry-contacts/${id}`);
    console.log('Deleted contact:', res.data);
    fetchContacts();  // Re-fetch all contacts from the backend after deletion to get the latest data
    errorMessage.value = '';  // Reset error message
  } catch (err) {
    console.error('Error deleting contact:', err);
    errorMessage.value = 'Failed to delete contact. Please try again.';  // Display error message
  }
};

// Reset the form after add/update
const resetForm = () => {
  form.value = { id: null, contact_name: '', email_id: '', company_name: '', date_met: '' };
  isEditing.value = false;
};

// Call fetchContacts when the component is mounted
onMounted(() => {
  console.log('Component mounted, calling fetchContacts');
  fetchContacts();
});
</script>