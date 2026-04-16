

<template>
  <div class="page">

    <Navbar/>

    <!-- MAIN CONTENT -->
    <section class="container-lg py-5">

      <!-- Header -->
      <div class="header">
        <h2 class="page-title">Industry Contacts</h2>
        <button class="btn btn-dark btn-main" @click="openForm">
          + Add Contact
        </button>
      </div>

      <!-- Search -->
      <input
        v-model="search"
        class="search"
        placeholder="Search contacts"
      />

      <!-- TABLE -->
      <div class="table-box">
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Company</th>
              <th>Progress Notes</th>
              <th>Date Met</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="c in filteredContacts" :key="c.contact_id">
              <td>{{ c.contact_name }}</td>
              <td>{{ c.company }}</td>
              <td>{{ c.progress_notes }}</td>
              <td>{{ c.date_met }}</td>
              <td>
                <button class="edit-btn" @click="editContact(c)">Edit</button>
                <button class="delete-btn" @click="deleteContact(c.contact_id)">Delete</button>
              </td>
            </tr>

            <tr v-if="filteredContacts.length === 0">
              <td colspan="5" class="empty">No contacts found</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- FORM -->
      <div v-if="showForm" class="form-box">

        <h3>{{ editMode ? "Edit Contact" : "Add Contact" }}</h3>

        <input v-model="form.contact_name" placeholder="Name" />
        <input v-model="form.company" placeholder="Company" />
        <textarea v-model="form.progress_notes" placeholder="Notes"></textarea>
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

    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import api from "@/services/api";
import Navbar from '@/components/Navbar.vue';

const userId = 2;

const contacts = ref([]);
const search = ref("");

const showForm = ref(false);
const editMode = ref(false);

const form = ref({
  contact_id: null,
  contact_name: "",
  company: "",
  progress_notes: "",
  date_met: "",
});

/* Fetch contacts */
const fetchContacts = async () => {
  try {
    const res = await api.get(`/users/${userId}/industry-contacts`);
    contacts.value = res.data;
  } catch (err) {
    console.error("Fetch error:", err);
  }
};

onMounted(fetchContacts);

/* Filter */
const filteredContacts = computed(() => {
  return contacts.value.filter(c =>
    c.contact_name?.toLowerCase().includes(search.value.toLowerCase())
  );
});

/* Open form */
const openForm = () => {
  editMode.value = false;
  form.value = {
    contact_id: null,
    contact_name: "",
    company: "",
    progress_notes: "",
    date_met: "",
  };
  showForm.value = true;
};

/* Close form */
const closeForm = () => {
  showForm.value = false;
};

/* Save */
const saveContact = async () => {
  try {
    const payload = {
      contact_name: form.value.contact_name,
      company: form.value.company,
      progress_notes: form.value.progress_notes,
      date_met: form.value.date_met,
    };

    if (editMode.value) {
      await api.put(
        `/users/${userId}/industry-contacts/${form.value.contact_id}`,
        payload
      );
    } else {
      await api.post(
        `/users/${userId}/industry-contacts`,
        payload
      );
    }

    closeForm();
    fetchContacts();
  } catch (err) {
    console.error("Save error:", err.response?.data || err);
  }
};

/* Edit */
const editContact = (c) => {
  editMode.value = true;
  form.value = { ...c };
  showForm.value = true;
};

/* Delete */
const deleteContact = async (id) => {
  try {
    await api.delete(`/users/${userId}/industry-contacts/${id}`);
    fetchContacts();
  } catch (err) {
    console.error("Delete error:", err);
  }
};
</script>

<style scoped>
.page {
  font-family: 'Martel', sans-serif;
  background: #fff;
  min-height: 100vh;
}

.logo-img {
  height: 2.5rem;
}

.page-title {
  font-size: 2.5rem;
  font-family: 'Martel', sans-serif;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.btn {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 30px;
}

.btn-main {
  padding: 10px 20px;
}

/* SEARCH */
.search {
  margin-bottom: 25px;
  padding: 12px;
  width: 320px;
  border-radius: 12px;
  border: 1px solid #ddd;
}

/* TABLE */
.table-box {
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.05);
  overflow: hidden;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th {
  text-align: left;
  padding: 14px;
  background: #fafafa;
  font-weight: 600;
}

td {
  padding: 14px;
  border-top: 1px solid #eee;
}

tr:hover {
  background: #fafafa;
}

.empty {
  text-align: center;
  padding: 20px;
  color: #777;
}

/* BUTTONS */
.edit-btn {
  background: #e5e7eb;
  border-radius: 10px;
  padding: 6px 12px;
}

.delete-btn {
  background: #ef4444;
  color: white;
  border-radius: 10px;
  padding: 6px 12px;
}

/* FORM */
.form-box {
  margin-top: 30px;
  padding: 25px;
  border-radius: 16px;
  border: 1px solid #eee;
  width: 420px;
  background: white;
  box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}

.form-box input,
.form-box textarea {
  width: 100%;
  margin-bottom: 12px;
  padding: 10px;
  border-radius: 10px;
  border: 1px solid #ddd;
}

.btn-row {
  display: flex;
  gap: 10px;
}
</style>