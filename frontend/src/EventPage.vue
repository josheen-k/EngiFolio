<script setup>
import {ref, onMounted} from 'vue'
import axios from 'axios'

//state
const events = ref([])
const editingEventId = ref(null)
const showForm = ref(false)
const newEvent = ref({
    name: '',
    date: '',
    location: '',
    details: ''
})

const currentEventId = ref(null)
const questions = ref([])
const newQuestion = ref('')
const editingQuestionId = ref(null)
const showQuestions =ref(false)

//fetch
const fetchEvents = async() => {
    const res = await axios.get('http://127.0.0.1:8000/api/networking-events')
    events.value = res.data
}

//run when load
onMounted(fetchEvents)

const addEvent = async () => {
        if (editingEventId.value) {
            const res = await axios.put(`http://127.0.0.1:8000/api/networking-events/${editingEventId.value}`,
            newEvent.value)
            
            const index = events.value.findIndex(e => e.event_id === editingEventId.value)
            events.value[index] = res.data
        } else {
        const res = await axios.post(`http://127.0.0.1:8000/api/networking-events`, newEvent.value)
        events.value.push(res.data)
        }
        await fetchEvents()
        newEvent.value ={
            name:'',
            date: '',
            location: '',
            details: '',
        }
        editingEventId.value = null
        showForm.value = false
    }

//delete
const deleteEvent = async(id) => {
        await axios.delete(`http://127.0.0.1:8000/api/networking-events/${id}`)
        fetchEvents()
}

const editEvent = (event) =>{
    editingEventId.value = event.event_id
    newEvent.value = {...event}
    showForm.value = true
}


const openQuestions = async(eventId) => {
    currentEventId.value = eventId
    showQuestions.value = true

    const res = await axios.get(`http://127.0.0.1:8000/api/networking-events/${eventId}/questions`)
    questions.value = res.data
}

const addQuestion = async() => {
    if(!newQuestion.value.trim()){
        alert("Question cannot be empty")
        return
    }
    let res
    if(editingQuestionId.value) {
        res = await axios.put(
            `http://127.0.0.1:8000/api/questions/${editingQuestionId.value}`, {
                 question: newQuestion.value 
            }
        )
        const index = questions.value.findIndex(
            q => q.id === editingQuestionId.value
        )
        questions.value[index] = res.data
    } else {
        res = await axios.post(
            `http://127.0.0.1:8000/api/networking-events/${currentEventId.value}/questions`, {
                question: newQuestion.value
            }
        )
    }

    newQuestion.value = ''
    editingQuestionId.value = null

    await openQuestions(currentEventId.value)
    await fetchEvents()
}

const deleteQuestion = async(id) => {
    await axios.delete(`http://127.0.0.1:8000/api/questions/${id}`)
    
    questions.value = questions.value.filter(q => q.id !== id)
}

const editQuestion =(q) => {
    newQuestion.value = q.question_text
    editingQuestionId.value = q.id
}

</script>

<template>
    <div style="
        min-height: 100vh;
        background-color: white;
        color: black;
        padding: 60px;">

        <!--title-->
        <h1 style="font-size: 40px; margin-bottom: 40px;">Event Page</h1>

        <!--table-->
        <table border="1" cellpadding="10" style="margin-top: 20px; width:100%">
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Details</th>
                <th>Questions</th>
                <th>Actions</th>
            </tr>
            
            <!--empty message-->
            <tbody v-if="events.length ===0">
                <tr>
                    <td colspan="6" style="
                        padding:30px;
                        text-align: center;
                        color:#888;">

                        No events yet. Click 'Add Event' to get start.
                    </td>
                </tr>
            </tbody>

            <!--show-->
            <tbody v-else>
                <tr 
                    v-for="event in events"
                    :key="event.event_id"
                    style="border-bottom: 1px solid #ddd;">

                    <td style="padding: 15px;">{{ event.event_name }}</td>
                    <td style="padding: 15px;">{{ event.event_datetime }}</td>
                    <td style="padding: 15px;">{{ event.location }}</td>
                    <td style="padding: 15px;">{{ event.details }}</td>

                    <td style="padding: 15px;">
                        <div v-if="event.questions && event.questions.length">
                            <ul style="margin-bottom: 10px;">
                                <li v-for="q in event.questions" :key="q.id">
                                    {{ q.question_text }}
                                </li>
                            </ul>
                            <button @click="openQuestions(event.event_id)">
                                Edit Questions
                            </button>
                        </div>

                        <button 
                            v-else
                            @click="openQuestions(event.event_id)"
                            style="background: #007bff; color:white; padding: 5px 10px;">
                            
                            Add Questions
                        </button>
                    </td>
                    
                    <td style="padding: 15px;">
                        <button 
                            @click="editEvent(event)"
                            style="
                            margin-right: 10px;
                            padding: 6px 12px;
                            background-color: #ffc107;
                            border: none;
                            cursor: pointer;">
                            
                            Edit
                        </button>

                        <button 
                            @click="deleteEvent(event.event_id)"
                            style="
                                padding: 6px 12px;
                                background-color: #dc3545;
                                border: none;
                                color: white;
                                cursor: pointer;">
                            
                                Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <button
            @click="showForm = true"
            style="
            position: fixed;
            bottom: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;">
            
            Add Event
        </button>

        <div v-if="showForm" style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;">

            <div style="
                background: white;
                padding: 30px;
                width: 500px;
                border-radius: 10px;">
                
                <h2 style="margin-bottom:20px;">{{editingEventId ? 'Edit Event' : 'Create New Event'}}</h2>
                <input v-model="newEvent.name" placeholder="Event Name" style="width:100%; margin-bottom: 10px; padding: 8px;"/>
                <input type="date" v-model="newEvent.date" style="width:100%; margin-bottom: 10px; padding: 8px;"/>
                <input v-model="newEvent.location" placeholder="Location" style="width:100%; margin-bottom: 10px; padding: 8px;"/>
                <textarea v-model="newEvent.details" placeholder="Details" style="width:100%; margin-bottom: 10px; padding: 8px;"></textarea>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button 
                        @click="showForm = false" 
                        style="padding: 8px 12px; 
                        background: #dc3545; 
                        color: white;">
                        
                        Cancel
                    </button>
                    <button 
                        @click="addEvent" 
                        style="padding: 8px 12px; 
                        background: #28a745; 
                        color: white;">
                        
                        {{editingEventId ? 'Update Event' : 'Create Event'}}
                    </button>
                </div>
            </div>    
        </div>
            <div v-if="showQuestions" style="
                        position:fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.5);
                        display: flex;
                        justify-content: center;
                        align-items: center;">
                    
                    <div v-if="showQuestions" style="background: white; padding: 20px; width: 400px;">
                        <h2>Questions</h2>

                        <input v-model="newQuestion">
                        <button @click="addQuestion">
                            {{ editingQuestionId ? 'Update Question' : 'Add Question' }}
                        </button>

                        <ul>
                            <li v-for="q in questions" :key="q.id">
                                {{ q.question_text }}
                                <button @click="editQuestion(q)">Edit</button>
                                <button @click="deleteQuestion(q.id)">Delete</button>
                            </li>
                        </ul>

                        <button @click="showQuestions=false">Close</button>
                    </div>
            </div>
    </div>  
</template>

<!--<script setup>
  import { onMounted, ref } from 'vue'
  import axios from 'axios'
  import Navbar from '@/components/Navbar.vue'

  const message = ref('Waiting for Laravel...')

  // This function talks to the backend
  const fetchData = async () => {
    try {
      const response = await axios.get('http://localhost:8000/api/data')
      message.value = response.data.content
    } catch (error) {
      message.value = "Laravel error."
    }
  }

  onMounted(() => {
    fetchData()
  })
</script>

<template>
  <div>
    <Navbar/>
  </div>
</template>
-->