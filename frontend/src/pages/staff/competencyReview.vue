<template>
  <div class="page">
    <StaffNavbar />

    <main class="container py-5">
      <div class="header">
        <h2>Student Competency Review</h2>
      </div>

      <p v-if="errorMessage" class="error">{{ errorMessage }}</p>

      <!-- Search -->
      <div class="controls">
        <input
          v-model="search"
          class="search"
          placeholder="Search competency entries"
        />
      </div>

      <!-- Students Table -->
      <div class="table-box student-table-box">
        <table class="entries-table">
          <thead>
            <tr>
              <th>Student</th>
              <th>Email</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="student in students" :key="student.user_id" class="student-row">
              <td>
                {{ student.first_name }} {{ student.last_name }}
              </td>

              <td>{{ student.email }}</td>

              <td>
                <button
                  class="btn btn-dark btn-sm"
                  @click="selectStudent(student)"
                >
                  View Entries
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>


      <h3 v-if="selectedStudent" class="selected-heading">
        Competency Entries for
        {{ selectedStudent.first_name }}
        {{ selectedStudent.last_name }}
      </h3>

      <p v-if="loading" class="loading">Loading...</p>

      <div v-if="!loading && selectedStudent && filteredEntries.length === 0" class="empty" >
        No competency entries found for this student.
      </div>

      <!-- Entries Table -->
      <div v-if="filteredEntries.length > 0" class="table-box">
        <table class="entries-table">
          <thead>
            <tr>
              <th>Title</th>
              <th>Indicator</th>
              <th>Year</th>
              <th>Tasks</th>
              <th>Start Date</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="entry in filteredEntries"
              :key="entry.entry_id"
              @click="openDetails(entry)"
            >
              <td>{{ entry.experience_title }}</td>

              <td>
                {{ entry.indicator?.display_id }} -
                {{ entry.indicator?.indicator_name }}
              </td>

              <td>{{ entry.associated_year }}</td>

              <td class="truncate">
                {{ entry.experience_tasks }}
              </td>

              <td>{{ entry.start_date }}</td>

              <td @click.stop>
                <button class="btn btn-dark btn-sm" @click="openFeedback(entry)" >
                  Feedback
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>


      <div
        v-if="selectedDetails"
        class="modal-overlay"
        @click.self="closeDetails"
      >
        <div class="modal-card">
          <h3>{{ selectedDetails.experience_title }}</h3>

          <p>
            <strong>Indicator:</strong>
            {{ selectedDetails.indicator?.display_id }} -
            {{ selectedDetails.indicator?.indicator_name }}
          </p>

          <p>
            <strong>Year:</strong>
            {{ selectedDetails.associated_year }}
          </p>

          <p>
            <strong>Start Date:</strong>
            {{ selectedDetails.start_date }}
          </p>

          <p>
            <strong>End Date:</strong>
            {{ selectedDetails.end_date || "Not specified" }}
          </p>

          <hr />

          <p><strong>Experience Tasks:</strong></p>
          <p>{{ selectedDetails.experience_tasks }}</p>

          <p><strong>Key Learnings:</strong></p>
          <p>
            {{ selectedDetails.key_learnings || "No key learnings added." }}
          </p>

          <p><strong>Future Applications:</strong></p>
          <p>
            {{
              selectedDetails.future_applications ||
              "No future applications added."
            }}
          </p>

          <div class="btn-row">
            <button class="btn btn-dark" @click="openFeedback(selectedDetails)" >
              Give Feedback
            </button>

            <button class="btn btn-light" @click="closeDetails">
              Close
            </button>
          </div>
        </div>
      </div>

      <!-- Feedback Modal -->
      <div
        v-if="selectedEntry"
        class="modal-overlay"
        @click.self="closeFeedback"
      >
        <div class="modal-card">
          <h3>
            Feedback for {{ selectedEntry.experience_title }}
          </h3>

          <textarea v-model="feedbackText" placeholder="Write feedback..."> </textarea>

          <div class="btn-row">
            <button class="btn btn-dark" @click="submitFeedback">
              Submit Feedback
            </button>

            <button class="btn btn-light" @click="closeFeedback">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import StaffNavbar from '@/components/StaffNavbar.vue'
import Footer from "@/components/Footer.vue";
import api from "@/services/api";
import { useRouter } from 'vue-router'

const router = useRouter();

const user = JSON.parse(localStorage.getItem("user"));
const staffUserId = user?.user_id || 4;

const students = ref([]);
const entries = ref([]);

const selectedUserId = ref("");
const selectedStudent = ref(null);

const search = ref("");
const loading = ref(false);
const errorMessage = ref("");

const selectedEntry = ref(null);
const selectedDetails = ref(null);

const feedbackText = ref("");

const fetchStudents = async () => {
  try {
    loading.value = true;
    errorMessage.value = "";

    const res = await api.get(
      `/staff/my-students?staff_id=${staffUserId}`
    );

    students.value = res.data;
  } catch (err) {
    console.error(
      "Fetch students error:",
      err.response?.data || err
    );

    errorMessage.value =
      "Could not load assigned students.";
  } finally {
    loading.value = false;
  }
};

const openStudentProfile = (student) => {
  router.push(`/profile/${student.user_id}`)
}

const selectStudent = async (student) => {
  selectedStudent.value = student;
  selectedUserId.value = student.user_id;

  await fetchEntries();
};

const fetchEntries = async () => {
  if (!selectedUserId.value) return;

  try {
    loading.value = true;
    errorMessage.value = "";

    const res = await api.get(
      `/users/${selectedUserId.value}/competency-entries`
    );

    entries.value = res.data;
  } catch (err) {
    console.error(
      "Fetch entries error:",
      err.response?.data || err
    );

    errorMessage.value =
      "Could not load competency entries.";
  } finally {
    loading.value = false;
  }
};

const filteredEntries = computed(() => {
  return entries.value.filter((entry) =>
    entry.experience_title
      ?.toLowerCase()
      .includes(search.value.toLowerCase()) ||
    entry.indicator?.indicator_name
      ?.toLowerCase()
      .includes(search.value.toLowerCase()) ||
    entry.indicator?.display_id
      ?.toLowerCase()
      .includes(search.value.toLowerCase())
  );
});

const openDetails = (entry) => {
  selectedDetails.value = entry;
};

const closeDetails = () => {
  selectedDetails.value = null;
};

const openFeedback = (entry) => {
  selectedEntry.value = entry;
  selectedDetails.value = null;
  feedbackText.value = "";
};

const closeFeedback = () => {
  selectedEntry.value = null;
  feedbackText.value = "";
};

const submitFeedback = async () => {
  if (!feedbackText.value.trim()) {
    alert("Please enter feedback before submitting.");
    return;
  }

  try {
    await api.post(
      `/competency-entries/${selectedEntry.value.entry_id}/feedback`,
      {
        staff_id: staffUserId,
        feedback_content: feedbackText.value,
      }
    );

    closeFeedback();

    alert("Feedback submitted");
  } catch (err) {
    console.error(
      "Submit feedback error:",
      err.response?.data || err
    );

    alert("Could not submit feedback");
  }
};

onMounted(fetchStudents);
</script>

<style scoped>
.page {
  min-height: 100vh;
  background: white;
}

.header {
  margin-bottom: 20px;
}

.controls {
  margin-bottom: 20px;
}

.search {
  padding: 10px;
  border-radius: 10px;
  border: 1px solid #ddd;
  width: 320px;
}

.student-table-box {
  margin-bottom: 30px;
}

.selected-heading {
  margin-bottom: 16px;
}

.table-box {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,0.06);
}

.entries-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.92rem;
}

.entries-table th {
  text-align: left;
  padding: 14px;
  background: #f8f8f8;
  border-bottom: 2px solid #eee;
}

.entries-table td {
  padding: 14px;
  border-bottom: 1px solid #eee;
  vertical-align: top;
}

.entries-table tbody tr:hover {
  background: #fafafa;
}

.entries-table tbody tr {
  cursor: pointer;
}

.truncate {
  max-width: 320px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.btn-sm {
  padding: 6px 12px;
  font-size: 0.8rem;
}

.loading,
.empty,
.error {
  margin-top: 20px;
}

.error {
  color: #b91c1c;
  font-weight: 600;
}

.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.35);
  backdrop-filter: blur(6px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-card {
  background: white;
  width: 520px;
  max-width: 92%;
  max-height: 85vh;
  overflow-y: auto;
  padding: 25px;
  border-radius: 18px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.25);
}

.modal-card textarea {
  width: 100%;
  min-height: 160px;
  margin-bottom: 12px;
  padding: 10px;
  border-radius: 10px;
  border: 1px solid #ddd;
}

.btn-row {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
}
</style>