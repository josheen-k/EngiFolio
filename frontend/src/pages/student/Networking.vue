<template>
  <div class="page">
    <Navbar />

    <section class="container-lg py-5">

      <!-- Header -->
      <div class="header">
        <h2 class="page-title">Industry Contacts</h2>
        <button class="btn btn-dark btn-main" @click="openForm">
          + Add Contact
        </button>
      </div>

      <!-- Controls -->
      <div class="controls">
        <input v-model="search" class="search" placeholder="Search contacts" />

        <select v-model="sortBy" class="filter-select">
          <option disabled value="">Sort by</option>
          <option value="name">Name</option>
          <option value="company">Company</option>
          <option value="date_newest">Newest</option>
          <option value="date_oldest">Oldest</option>
        </select>

        <select v-model="filterBy" class="filter-select">
          <option value="all">All</option>
          <option value="with_linkedin">With LinkedIn</option>
          <option value="without_linkedin">No LinkedIn</option>
        </select>
      </div>

      <!-- Cards -->
      <div class="card-grid">
        <div
          class="contact-card"
          v-for="c in filteredContacts"
          :key="c.contact_id"
        >
          <div class="card-top">
            <div class="contact-main">
              <div class="avatar">
                {{ getInitials(c.contact_name) }}
              </div>

              <div>
                <h3 class="contact-name">{{ c.contact_name }}</h3>
                <p class="company">{{ c.company || "No company" }}</p>
              </div>
            </div>

            <ButtonsStyle
              @edit="editContact(c)"
              @delete="deleteContact(c.contact_id)"
            />
          </div>

          <p class="notes">
            {{ c.progress_notes || "No notes" }}
          </p>

          <a
            v-if="c.linkedin_url"
            :href="c.linkedin_url"
            target="_blank"
            class="linkedin-link"
          >
            LinkedIn
          </a>

          <p class="date">
            {{ c.date_met || "No date" }}
          </p>
        </div>

        <div v-if="filteredContacts.length === 0" class="empty">
          No contacts found
        </div>
      </div>

      <!-- Modal -->
      <div v-if="showForm" class="modal-overlay" @click.self="closeForm">
        <div class="modal-card">
          <h3>{{ editMode ? "Edit Contact" : "Add Contact" }}</h3>

          <input v-model="form.contact_name" placeholder="Name" />
          <input v-model="form.company" placeholder="Company" />
          <textarea v-model="form.progress_notes" placeholder="Notes"></textarea>
          <input v-model="form.linkedin_url" type="url" placeholder="LinkedIn URL" />
          <input type="date" v-model="form.date_met" />

          <div class="btn-row">
            <button class="btn btn-dark" @click="saveContact">
              {{ editMode ? "Update" : "Create" }}
            </button>
            <button class="btn btn-light" @click="closeForm">Cancel</button>
          </div>
        </div>
      </div>

    </section>

    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import api from "@/services/api";

import Navbar from "@/components/Navbar.vue";
import Footer from "@/components/Footer.vue";
import ButtonsStyle from "@/components/ButtonsStyle.vue";

const user = JSON.parse(localStorage.getItem("user"));
const userId = user?.user_id || 1;

const contacts = ref([]);
const search = ref("");
const sortBy = ref("");
const filterBy = ref("all");

const showForm = ref(false);
const editMode = ref(false);

const form = ref({
  contact_id: null,
  contact_name: "",
  company: "",
  progress_notes: "",
  date_met: "",
  linkedin_url: "",
});

const fetchContacts = async () => {
  const res = await api.get(`/users/${userId}/industry-contacts`);
  contacts.value = res.data;
};

onMounted(fetchContacts);

const getInitials = (name) => {
  if (!name) return "?";
  return name
    .split(" ")
    .map(w => w[0].toUpperCase())
    .join("")
    .slice(0, 2);
};

const filteredContacts = computed(() => {
  let result = contacts.value.filter(c => {
    const matchesSearch =
      c.contact_name?.toLowerCase().includes(search.value.toLowerCase()) ||
      c.company?.toLowerCase().includes(search.value.toLowerCase());

    const matchesFilter =
      filterBy.value === "all" ||
      (filterBy.value === "with_linkedin" && c.linkedin_url) ||
      (filterBy.value === "without_linkedin" && !c.linkedin_url);

    return matchesSearch && matchesFilter;
  });

  result = [...result].sort((a, b) => {
    if (!sortBy.value || sortBy.value === "name") {
      return (a.contact_name || "").localeCompare(b.contact_name || "");
    }
    if (sortBy.value === "company") {
      return (a.company || "").localeCompare(b.company || "");
    }
    if (sortBy.value === "date_newest") {
      return new Date(b.date_met || 0) - new Date(a.date_met || 0);
    }
    if (sortBy.value === "date_oldest") {
      return new Date(a.date_met || 0) - new Date(b.date_met || 0);
    }
    return 0;
  });

  return result;
});

const openForm = () => {
  editMode.value = false;
  form.value = {
    contact_id: null,
    contact_name: "",
    company: "",
    progress_notes: "",
    date_met: "",
    linkedin_url: "",
  };
  showForm.value = true;
};

const closeForm = () => (showForm.value = false);

const saveContact = async () => {
  const payload = { ...form.value };

  if (editMode.value) {
    await api.put(`/users/${userId}/industry-contacts/${form.value.contact_id}`, payload);
  } else {
    await api.post(`/users/${userId}/industry-contacts`, payload);
  }

  closeForm();
  fetchContacts();
};

const editContact = (c) => {
  editMode.value = true;
  form.value = { ...c };
  showForm.value = true;
};

const deleteContact = async (id) => {
  if (!confirm("Delete this contact?")) return;
  await api.delete(`/users/${userId}/industry-contacts/${id}`);
  fetchContacts();
};
</script>

<style scoped>
.page {
  font-family: "Martel", sans-serif;
}

/* Header */
.header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}

/* Controls */
.controls {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 20px;
}

.search, .filter-select {
  padding: 8px;
  border-radius: 8px;
  border: 1px solid #ddd;
  font-size: 0.85rem;
}

/* Cards (compact) */
.card-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 16px;
}

.contact-card {
  background: white;
  padding: 16px;
  border-radius: 14px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.05);
  transition: transform 0.2s ease;
}

.contact-card:hover {
  transform: translateY(-3px);
}

.card-top {
  display: flex;
  justify-content: space-between;
  gap: 10px;
}

.contact-main {
  display: flex;
  gap: 10px;
  align-items: center;
}

.avatar {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background: #e6e6e6;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
}

.contact-name {
  font-size: 1.1rem;
}

.company {
  font-size: 0.85rem;
  color: #555;
}

.notes {
  font-size: 0.8rem;
  margin-top: 8px;
}

.date {
  font-size: 0.75rem;
  color: #777;
  margin-top: 5px;
}

.linkedin-link {
  font-size: 0.8rem;
  color: #0a66c2;
  margin-top: 6px;
  display: block;
}

.empty {
  text-align: center;
}

/* Modal */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.3);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-card {
  background: white;
  padding: 20px;
  border-radius: 14px;
  width: 360px;
}

.modal-card input,
.modal-card textarea {
  width: 100%;
  margin-bottom: 10px;
  font-size: 0.85rem;
}

.btn-row {
  display: flex;
  gap: 10px;
}
</style>