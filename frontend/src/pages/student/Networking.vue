<script setup>
import {ref, onMounted} from 'vue'
import axios from 'axios'
import Navbar from '@/components/Navbar.vue'

//state
const events = ref([])
const editingEventId = ref(null)
const showForm = ref(false)

const comments = ref([])
const newComment = ref('')
const editingCommentId = ref(null)
const showComments = ref(false)

const currentEventId = ref(null)

const questions = ref([])
const newQuestion = ref('')
const editingQuestionId = ref(null)
const showQuestions = ref(false)

//fetch
const fetchEvents = async() => {
    const res = await axios.get('http://127.0.0.1:8000/api/networking-events')
    events.value = res.data
}

//run when load
onMounted(fetchEvents)


const newEvent = ref({
    name: '',
    date: '',
    location: '',
    details: ''
})

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
    newEvent.value = {
      name: event.event_name,
      date: event.event_datetime,
      location: event.location,
      details: event.details
    }
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
    
    fetchEvents()

    questions.value = questions.value.filter(q => q.id !== id)
}

const editQuestion =(q) => {
    newQuestion.value = q.question_text
    editingQuestionId.value = q.id
}

const openComments = async(eventId) => {
  currentEventId.value = eventId
  showComments.value = true

  const res = await axios.get(`http://127.0.0.1:8000/api/networking-events/${eventId}/comments`)

  comments.value = res.data
}

const addComment = async()=> {
  if(!newComment.value.trim()) return

  if(editingCommentId.value) {
    await axios.put(`http://127.0.0.1:8000/api/comments/${editingCommentId.value}`, {comment: newComment.value})
  } else {
    await axios.post(`http://127.0.0.1:8000/api/networking-events/${currentEventId.value}/comments`, {comment: newComment.value})
  }

  newComment.value = ''
  editingCommentId.value = null
  
  await openComments(currentEventId.value)
  await fetchEvents()
}


const editComment = (comment) => {
  newComment.value = comment.comment_text
  editingCommentId.value = comment.id
}

const deleteComment = async(id) => {
  await axios.delete(`http://127.0.0.1:8000/api/comments/${id}`)
  await openComments(currentEventId.value)
  await fetchEvents()
}

</script>

<template>
  <Navbar />

    <div style="
        min-height: 100vh;
        background-color: white;
        color: black;
        padding: 60px;">

        <!--title-->
        <h1 style="font-size: 40px; margin-bottom: 40px;">Event Page</h1>

        <!--table---->
        <table  cellpadding="10" style="margin-top: 20px; width:100%; border: 1px solid black;">
          <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Details</th>
                <th>Questions</th>
                <th>Comments</th>
                <th>Actions</th>
            </tr>
          </thead> 
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
                    <!--confirm later, Do we need a actually time for this?_?-->
                    <td style="padding: 15px;">{{ event.event_datetime.split(' ')[0] }}</td>
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

                    <td>
                      <ul v-if="event.comments && event.comments.length">
                        <li v-for="c in event.comments" :key="'c.id'">
                          {{ c.comment_text }}
                        </li>
                      </ul>
                      <button @click="openComments(event.event_id)">
                        Mange Comments
                      </button>"
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
        <div v-if="showComments" style="
          position: fixed;
          top: 30%;
          left: 40%;
          background: white;
          padding: 20px;
          border: 1px solid black;">

          <h3>Comments</h3>
          <input v-model="newComment" placeholder="Enter comment" />
          <button @click="addComment">
            {{ editingCommentId ? 'Update Comment' : 'Add Comment' }}
          </button>

          <ul>
            <li v-for="c in comments" :key="c.id">
              {{ c.comment_text }}
              <button @click="editComment(c)">Edit</button>
              <button @click="deleteComment(c.id)">Delete</button>
            </li>
          </ul>
          
          <button @click="showComments = false">Close</button>
        </div>
        <button
            @click="showForm = true"
            style="
            position: fixed;
            bottom: 120px;
            left: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            border: none; 
            color: white;
            font-size: 16px;
            cursor: pointer;
            z-index: 1000;">
            
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
<!-- bottom bar -->
<div style="
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background: #0b1a5e;
  color: white;
  padding: 20px 40px;
  display: flex;
  justify-content: space-between;
  align-items: center;
">

  <div>
    <img src="/src/assets/Logo.png" alt="Adelaide University" style="height: 40px"/>

    <div style="font-size: 14px;">
     ©EngiFolio 2026
  </div>
  </div>

  <div style="max-width: 600px; text-align: center; font-size: 14px;">
    EngiFolio acknowledges the Kaurna people as the Traditional Owners of the Country where the city of Adelaide is situated today, and pays its respects to Elders past and present.
  </div>

  <div>
    <a href="#" style="color: white;">Privacy policy</a>
  </div>

</div>

</template>


<!--i have to do it later on -->
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