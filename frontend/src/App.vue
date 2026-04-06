<template>
    <div>
        <h1>Crud Example</h1>

        <div class="student" v-for="s in students" :key="s.id">
            <span>{{ s.name }} ({{ s.student_id }}) - {{ s.grade }}</span>

            <input v-model="s.name" placeholder="Name"/>
            <input v-model="s.student_id" placeholder="ID"/>
            <input v-model="s.grade" placeholder="Grade"/>
            <button @click="updateStudent(s)">Update Student</button>

            <button @click="deleteStudent(s.id)">Delete student</button>
        </div>

        <div class="add">
            <input v-model="name" placeholder="Name"/>
            <input v-model="student_id" placeholder="ID"/>
            <input v-model="grade" placeholder="Grade"/>
            <button @click="addStudent">Add student</button>
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
            grade: ''
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
            }).then(() => this.load());
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

<style>
h1 {
  text-align: center;
}

.student {
  display: flex;
  align-items: center;
  gap: 10px;
}

span {
  margin: 20px;
}

.add {
  display: flex;
  gap: 10px;
  margin: 20px;
}
</style>