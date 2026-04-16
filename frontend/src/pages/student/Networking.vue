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

//in case showForm can't use 
const openCreateForm = () => {
  editingEventId.value = null 
  newEvent.value = {
    name: '',
    date: '',
    location: '',
    details: ''
  }

  showForm.value =true
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
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;">

        <!--title-->
        <h1 style="font-size: 40px;">Event Page</h1>

        <button
            @click="showForm = true"
            style="
            height: 47px;          
            padding: 0 20px;            
            border-radius: 30px;         
            border: 1.5px solid #333;     
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;">
            
            Add Event
        </button>
    </div>
        <!--table---->
        <table  cellpadding="10" style="margin-top: 20px; width:100%; border: 1px solid black; border-collapse: collapse">
          <thead>
            <tr>
                <th style="border: 1px solid black;">Name</th>
                <th style="border: 1px solid black;">Date</th>
                <th style="border: 1px solid black;">Location</th>
                <th style="border: 1px solid black;">Details</th>
                <th style="border: 1px solid black;">Questions</th>
                <th style="border: 1px solid black;">Comments</th>
                <th style="border: 1px solid black;">Actions</th>
            </tr>
          </thead> 
            <!--empty message-->
            <tbody v-if="events.length ===0">
                <tr>
                    <td colspan="6" style="
                        padding:15px;
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

                    <td style="padding: 15px; border: 1px solid black;">{{ event.event_name }}</td>
                    <!--confirm later, Do we need a actually time for this?_?-->
                    <td style="padding: 15px; border: 1px solid black;">{{ event.event_datetime.split(' ')[0] }}</td>
                    <td style="padding: 15px; border: 1px solid black;">{{ event.location }}</td>
                    <td style="padding: 15px; border: 1px solid black;">{{ event.details }}</td>

                    <td style="padding: 15px; border: 1px solid black;">
                        <div v-if="event.questions && event.questions.length">
                            <ul style="margin-bottom: 10px;">
                                <li v-for="q in event.questions" :key="q.id">
                                    {{ q.question_text }}
                                </li>
                            </ul>
                            <button @click="openQuestions(event.event_id)"
                            style=" height: 47px;               
                                    padding: 0 20px;             
                                    border-radius: 30px;        
                                    border: 1.5px solid #333;     
                                    display: inline-flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 14px;
                                    cursor: pointer;">Edit Questions
                            </button>
                        </div>

                        <button 
                            v-else
                            @click="openQuestions(event.event_id)"
                            style=" height: 47px;               
                                    padding: 0 20px;             
                                    border-radius: 30px;        
                                    border: 1.5px solid #333;     
                                    display: inline-flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 14px;
                                    cursor: pointer;">Add Questions
                        </button>
                    </td>

                    <td style="padding: 15px; border: 1px solid black;">
                      <ul v-if="event.comments && event.comments.length">
                        <li v-for="c in event.comments" :key="'c.id'">
                          {{ c.comment_text }}
                        </li>
                      </ul>
                      <button @click="openComments(event.event_id)"
                      style=" height: 47px;               
                        padding: 0 20px;             
                        border-radius: 30px;        
                        border: 1.5px solid #333;     
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 14px;
                        cursor: pointer;">Mange Comments
                      </button>
                    </td>
                    
                    <td style="padding: 15px; border: 1px solid black;">
                        <button 
                            @click="editEvent(event)"
                            style=" height: 47px;               
                                    padding: 0 20px;             
                                    border-radius: 30px;        
                                    border: 1.5px solid #333;     
                                    display: inline-flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 14px;
                                    cursor: pointer;">Edit
                        </button>

                        <button @click="deleteEvent(event.event_id)"
                          style=" height: 47px;               
                                  padding: 0 20px;             
                                  border-radius: 30px;        
                                  border: 1.5px solid #333;     
                                  display: inline-flex;
                                  align-items: center;
                                  justify-content: center;
                                  font-size: 14px;
                                  cursor: pointer;">Delete
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
          <button @click="addComment"
            style=" height: 47px;               
                        padding: 0 20px;             
                        border-radius: 30px;        
                        border: 1.5px solid #333;     
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 14px;
                        cursor: pointer;">
            {{ editingCommentId ? 'Update Comment' : 'Add Comment' }}
          </button>

          <ul>
            <li v-for="c in comments" :key="c.id">
              {{ c.comment_text }}
              <button @click="editComment(c)"
                style=" height: 47px;               
                        padding: 0 20px;             
                        border-radius: 30px;        
                        border: 1.5px solid #333;     
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 14px;
                        cursor: pointer;">Edit</button>
              <button @click="deleteComment(c.id)"
                style=" height: 47px;               
                        padding: 0 20px;             
                        border-radius: 30px;        
                        border: 1.5px solid #333;     
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 14px;
                        cursor: pointer;">Delete</button>
            </li>
          </ul>
          
          <button @click="showComments = false"
            style=" height: 47px;               
                    padding: 0 20px;             
                    border-radius: 30px;        
                    border: 1.5px solid #333;     
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 14px;
                    cursor: pointer;">Close</button>
        </div>
        

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
                        style="  height: 47px;               
                        padding: 0 20px;             
                        border-radius: 30px;        
                        border: 1.5px solid #333;     
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 14px;
                        cursor: pointer;">
                        
                        Cancel
                    </button>
                    <button 
                        @click="addEvent" 
                        style=" height: 47px;               
                                padding: 0 20px;             
                                border-radius: 30px;        
                                border: 1.5px solid #333;     
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 14px;
                                cursor: pointer;"">
                        
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
                        <button @click="addQuestion"
                          style=" height: 47px;               
                                  padding: 0 20px;             
                                  border-radius: 30px;        
                                  border: 1.5px solid #333;     
                                  display: inline-flex;
                                  align-items: center;
                                  justify-content: center;
                                  font-size: 14px;
                                  cursor: pointer;">
                            {{ editingQuestionId ? 'Update Question' : 'Add Question' }}
                        </button>

                        <ul>
                            <li v-for="q in questions" :key="q.id">
                                {{ q.question_text }}
                                <button @click="editQuestion(q)"
                                  style="  height: 47px;               
                                  padding: 0 20px;             
                                  border-radius: 30px;        
                                  border: 1.5px solid #333;     
                                  display: inline-flex;
                                  align-items: center;
                                  justify-content: center;
                                  font-size: 14px;
                                  cursor: pointer;">
                                  
                                  Edit
                                </button>
                                <button @click="deleteQuestion(q.id)"
                                  style="  height: 47px;               
                                  padding: 0 20px;             
                                  border-radius: 30px;        
                                  border: 1.5px solid #333;     
                                  display: inline-flex;
                                  align-items: center;
                                  justify-content: center;
                                  font-size: 14px;
                                  cursor: pointer;">Delete</button>
                            </li>
                        </ul>

                        <button @click="showQuestions=false">Close</button>
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

<style>
* {
  font-family: 'Montserrat Alternates', sans-serif;
}
</style>

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