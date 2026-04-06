<template>
    <div>
        <h1>Crud Example</h1>

        <div v-for="s in students" :key="s.id">
            {{ s.name }} - {{ s.grade }}
        </div>

        <input v-model="name" placeholder="Name"/>
        <input v-model="student_id" placeholder="ID"/>
        <input v-model="grade" placeholder="Grade"/>
        <button @click="addStudent">Add student</button>

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
        }
    },
    mounted() {
        this.load();
    }
};
</script>