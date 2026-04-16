<template>
  <div class="container">

    <!-- Header -->
    <div class="header">
      <h2>Industry Contacts</h2>
      <button class="add-btn" @click="openForm">+ Add Contact</button>
    </div>

    <!-- search bar-->
    <input
      v-model="search"
      class="search"
      placeholder="Search contacts"
    />

    <!-- TABLE -->
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Company</th>
          <th>Notes</th>
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
          <td colspan="5">No contacts found</td>
        </tr>
      </tbody>
    </table>

    <!-- INLINE FORM (NO MODAL, NO OVERLAY) -->
    <div v-if="showForm" class="form-box">

      <h3>{{ editMode ? "Edit Contact" : "Add Contact" }}</h3>

      <input v-model="form.contact_name" placeholder="Name" />
      <input v-model="form.company" placeholder="Company" />
      <textarea v-model="form.progress_notes" placeholder="Notes"></textarea>
      <input type="date" v-model="form.date_met" />

      <div class="btn-row">
        <button class="save-btn" @click="saveContact">
          {{ editMode ? "Update" : "Create" }}
        </button>

        <button class="cancel-btn" @click="closeForm">
          Cancel
        </button>
      </div>

    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import api from "@/services/api";

const userId = 1;

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

/*Fetch contacts */
const fetchContacts = async () => {
  try {
    const res = await api.get(`/users/${userId}/industry-contacts`);
    contacts.value = res.data;
  } catch (err) {
    console.error("Fetch error:", err);
  }
};

onMounted(fetchContacts);

/* Filter contacts */
const filteredContacts = computed(() => {
  return contacts.value.filter(c =>
    c.contact_name?.toLowerCase().includes(search.value.toLowerCase())
  );
});

/* Add form */
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

/* Close add form */
const closeForm = () => {
  showForm.value = false;
};

/*  Save form */
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

/* Edit contact */
const editContact = (c) => {
  editMode.value = true;

  form.value = {
    contact_id: c.contact_id,
    contact_name: c.contact_name,
    company: c.company,
    progress_notes: c.progress_notes,
    date_met: c.date_met,
  };

  showForm.value = true;
};

/* Delete contact */
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
.container {
  max-width: 1100px;
  margin: 40px auto;
  padding: 20px;
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.add-btn {
  background: #111;
  color: white;
  padding: 10px 18px;
  border-radius: 20px;
  border: none;
  cursor: pointer;
}

.search {
  margin-bottom: 20px;
  padding: 10px;
  width: 300px;
  border-radius: 10px;
  border: 1px solid #ddd;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th {
  text-align: left;
  padding: 12px;
  border-bottom: 2px solid #eee;
}

td {
  padding: 12px;
  border-bottom: 1px solid #eee;
}

tr:hover {
  background: #fafafa;
}

button {
  margin-right: 6px;
  padding: 6px 12px;
  border-radius: 8px;
  border: none;
  cursor: pointer;
}

.edit-btn {
  background: #e5e7eb;
}

.delete-btn {
  background: #ef4444;
  color: white;
}

.save-btn {
  background: #111;
  color: white;
  padding: 10px 14px;
  border-radius: 8px;
}

.cancel-btn {
  background: #ccc;
  padding: 10px 14px;
  border-radius: 8px;
}

.form-box {
  margin-top: 20px;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 12px;
  width: 420px;
  background: white;
}

.btn-row {
  margin-top: 10px;
  display: flex;
  gap: 10px;
}
</style>