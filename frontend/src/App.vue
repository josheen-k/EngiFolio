<template>
  <div class="container w-50 mt-5">
    <h1 class="text-center display-1">Crud Example</h1>

    <div class="list-group m-5">
      <div class="list-group-item" v-for="s in students" :key="s.id">

        <div class="d-flex justify-content-between align-items-center" v-if="editing_id !== s.id">
          <span>{{ s.name }} ({{ s.student_id }}) - {{ s.grade }}</span>

          <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm" @click="editing_id = s.id">Update</button>
            <button class="btn btn-danger btn-sm" @click="deleteStudent(s.id)">Delete</button>
          </div>
        </div>

        <div class="row g-2 mt-2" v-else>
          <div class="col-3">
            <input class="form-control" v-model="s.name" placeholder="Name" />
          </div>

          <div class="col-3">
            <input class="form-control" v-model="s.student_id" placeholder="ID" />
          </div>

          <div class="col-3">
            <input class="form-control" v-model="s.grade" placeholder="Grade" />
          </div>

          <div class="col-3 d-flex gap-2">
            <button class="btn btn-success" @click="updateStudent(s)">Save</button>
            <button class="btn btn-secondary" @click="editing_id=null; load()">Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <div class="card p-3 m-5">
      <h5 class="mb-3">Add Student</h5>

      <div class="row">
        <div class="col-3">
          <input class="form-control" v-model="name" placeholder="Name" />
        </div>

        <div class="col-3">
          <input class="form-control" v-model="student_id" placeholder="ID" />
        </div>

        <div class="col-3">
          <input class="form-control" v-model="grade" placeholder="Grade" />
        </div>

        <div class="col-3">
          <button class="btn btn-success" @click="addStudent">Add student</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import studentService from '@/services/studentService';

export default {
  data() {
    return {
      students: [],
      name: '',
      student_id: '',
      grade: '',
      editing_id: null
    };
  },

  methods: {
    load() {
      studentService.getStudents().then(res => {
        this.students = res.data;
      });
    },

    addStudent() {
      studentService.createStudent({
        name: this.name,
        student_id: this.student_id,
        grade: this.grade
      }).then(() => {
        this.load();

        this.name = '';
        this.student_id = '';
        this.grade = '';
      });
    },

    updateStudent(s) {
      studentService.updateStudent(s.id, {
        name: s.name,
        student_id: s.student_id,
        grade: s.grade
      }).then(() => {
        this.editing_id = null;
        this.load();
      });
    },

    deleteStudent(id) {
      studentService.deleteStudent(id).then(() => this.load());
    }
  },
  mounted() {
    this.load();
  }
};
</script>