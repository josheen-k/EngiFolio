<script>
import axios from "axios";

export default {
  data() {
    return {
      tasks: [],
      title: "",
      status: "",
      editingId: null
    };
  },
  methods: {
    async fetchTasks() {
      const res = await axios.get("http://127.0.0.1:8000/api/tasks");
      this.tasks = res.data;
    },
    async addTask() {
      if (!this.title || !this.status) return;

      if(this.editingId) {
        await axios.put(
          'http://127.0.0.1:8000/api/tasks/${this.editingId}',
          {
            title: this.title,
            status: this.status
          }
        );
        this.editingId = null;
      } else {
        await axios.post("http://127.0.0.1:8000/api/tasks", {
          title: this.title,
          status: this.status
        });
      }

      this.title = "";
      this.status = "";
      this.fetchTasks();
  },

  async updateTask() {
    await axios.put(`http://127.0.0.1:8000/api/tasks/${this.editingId}`,
      {
        title: this.title,
        status: this.status
      }
    );
    this.editingId = null;
    this.title ="";
    this.status = "";
    this.fetchTasks();
  },

  editTask(task){
    this.title = task.title;
    this.status = task.status;
    this.editingId = task.id;
  },

  async deleteTask(id) {
  await axios.delete(`http://127.0.0.1:8000/api/tasks/${id}`);
  this.fetchTasks();
  },

  handleEdit(task){
    if(this.editingId === task.id) {
      this.updateTask();
    } else {
      this.title = task.title;
      this.status = task.status;
      this.editingId = task.id;
    }
  },

  formatTime(time) {
    if (!time) return "";

    return time.replace("T", " ").substring(0, 19);
    },
  
  mounted() {
    this.fetchTasks();
  }
}
}
</script>

<template>
  <div class = "container">
    <h1>CRUD TESTING</h1>

    <!-- input-->
    <div class = "form">
      <input v-model = "title" placeholder = "Name" />
      <input v-model = "status" placeholder = "Score" />
      <button @click = "addTask">Update</button>
    </div>

    <div class = "list">
      <div v-for = "task in tasks" :key = "task.id" class= "card">
        
        <div class = "info">
          <p><strong>{{ task.title }} - {{ task.status }}</strong></p>
          <p class = "time">
            Time: {{ formatTime(task.updated_at) }}
          </p>
        </div>

        <div class = "actions">
          <button @click= "handleEdit(task)">{{editingId == task.id ? "confirm" : "correct"}}</button>
          <button @click= "deleteTask(task.id)">delete</button>
        </div>

      </div>
    </div>
  </div>
</template>

<style>
.container {
  max-width: 600px;
  margin: 50px auto;
  font-family: Arial;
}

.form {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

input {
  padding: 8px;
  flex: 1;
}

button {
  padding: 8px 12px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
}

.card{
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  background: #f4f4f4;
  margin-bottom: 10px;
  border-radius: 8px;
}

.info{
  text-align: left;
}

.actions{
  display:flex;
  gap: 10px;
}

.time {
  font-size: 12px;
  color: gray;
}
.action button:first-child{
  background: #4caf50;
  color: white;
}

.actions button:last-child{
  background: red;
  color: white;
}
</style>