<template>
  <div style="padding: 20px;">
    <h1>Test Student CRUD</h1>

    <form @submit.prevent="handleSubmit">
      <input v-model="form.name" placeholder="Name" required />
      <input v-model="form.email" placeholder="Email" required />
      <input v-model="form.age" placeholder="Age" />

      <button type="submit">
        {{ editing ? 'Update' : 'Create' }}
      </button>
    </form>

    <hr />

    <ul>
      <li v-for="student in students" :key="student.id">
        {{ student.name }} - {{ student.email }} - {{ student.age }}

        <button @click="editStudent(student)">Edit</button>
        <button @click="removeStudent(student.id)">Delete</button>
      </li>
    </ul>
  </div>
</template>

<script>
import {
  getStudents,
  createStudent,
  updateStudent,
  deleteStudent
} from '../../services/testStudentService';

export default {
  data() {
    return {
      students: [],
      editing: false,
      editingId: null,
      form: {
        name: '',
        email: '',
        age: ''
      }
    };
  },
  methods: {
    async fetchStudents() {
      const res = await getStudents();
      this.students = res.data;
    },

    async handleSubmit() {
      if (this.editing) {
        await updateStudent(this.editingId, this.form);
      } else {
        await createStudent(this.form);
      }

      this.resetForm();
      this.fetchStudents();
    },

    editStudent(student) {
      this.editing = true;
      this.editingId = student.id;
      this.form = { ...student };
    },

    async removeStudent(id) {
      await deleteStudent(id);
      this.fetchStudents();
    },

    resetForm() {
      this.editing = false;
      this.editingId = null;
      this.form = {
        name: '',
        email: '',
        age: ''
      };
    }
  },
  mounted() {
    this.fetchStudents();
  }
};
</script>