import axios from 'axios';
const API_URL = 'http://127.0.0.1:8000/api/students';

export default {
    getStudents() {
        return axios.get(API_URL);
    },
    createStudent(data) {
        return axios.post(API_URL, data);
    },
    updateStudent(id, data) {
        return axios.put(`${API_URL}/${id}`, data);
    },
    deleteStudent(id) {
        return axios.delete(`${API_URL}/${id}`);
    }
};