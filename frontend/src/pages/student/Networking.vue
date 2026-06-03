<template>
  <div class="page">
    <Navbar />

    <section class="container-lg py-5">
      <div class="pitch-box">
        <label class="pitch-label">Elevator pitch</label>

        <textarea
          v-model="elevatorPitch"
          class="pitch-textarea"
          placeholder="Write your elevator pitch here..."
        ></textarea>

        <div class="pitch-actions">
          <button
            class="action-button small-button"
            @click="saveElevatorPitch"
            :disabled="savingPitch"
          >
            Save
          </button>
        </div>
      </div>

      <div class="networking-toggle">
        <RouterLink :to="`/student/networking/${route.params.id}`" class="toggle-pill" :class="{ active: $route.name === 'networking-events' }">
          Events Calendar
        </RouterLink>

        <RouterLink :to="`/student/networking/${route.params.id}/contacts`" class="toggle-pill" :class="{ active: $route.name === 'networking-contacts' }">
          Industry Contacts
        </RouterLink>
      </div>

      <RouterView />
    </section>
  </div>
  <div v-if="popUp.show" class="popUp-msg" :class="popUp.type">
    {{ popUp.message }}
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink, RouterView, useRoute } from 'vue-router'
import api from '@/services/api'
import Navbar from '@/components/Navbar.vue'

const route = useRoute()

// Object to store data about the popup message
const popUp = ref({ show: false, message: '', type: '' })
// Time the popup can be viewed for. Currently set to 3 seconds allow time for the user to view the message
const popUpTime = 3000

// Used to display the popup message and the type being either success or error
const showPopUp = (message, type) => {
  popUp.value = { show: true, message, type }
  setTimeout(() => popUp.value.show = false, popUpTime)
}

const elevatorPitch = ref('')
const savingPitch = ref(false)

const fetchElevatorPitch = async () => {
  const res = await api.get(`/profile/${route.params.id}/elevator-pitch`)
  elevatorPitch.value = res.data.pitch_text || ''
}

const saveElevatorPitch = async () => {
  const trimmedPitch = elevatorPitch.value.trim()

  if (!trimmedPitch) {
    showPopUp("Elevator Pitch is empty. Enter in your pitch.", "error");
    return
  }

  savingPitch.value = true

  try {
    await api.put(`/profile/${route.params.id}/elevator-pitch`, {
      pitch_text: trimmedPitch,
    })

    showPopUp("Your elevator pitch has been saved.", "success");
  } catch {
    showPopUp("Error saving elevator pitch", "error");
  }  
  
  finally {
    savingPitch.value = false
  }
}

onMounted(() => {
  fetchElevatorPitch()
})

</script>

<style scoped>
.page {
  font-family: 'Maven Pro', sans-serif;
  background: #f4f6f8;
  min-height: 100vh;
}

.pitch-box {
  background: #fff;
  border: 1px solid #d9e0e7;
  border-radius: 12px;
  padding: 16px;
  margin-bottom: 20px;
  width: 100%;
  box-sizing: border-box;
}

.pitch-label {
  display: block;
  font-weight: 700;
  margin-bottom: 8px;
  color: #24364b;
}

.pitch-textarea {
  width: 100%;
  min-height: 120px;
  border: 1px solid #cfd8e3;
  border-radius: 10px;
  padding: 12px;
  resize: vertical;
  font-size: 1rem;
  line-height: 1.5;
}

.pitch-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 10px;
}

.action-button {
  background: #13202c;
  color: #ffffff;
  padding: 0.85rem 1.4rem;
  border: 1px solid #13202c;
  border-radius: 999px;
  cursor: pointer;
  transition:
    transform 0.18s ease,
    background-color 0.18s ease,
    border-color 0.18s ease;
}

.action-button:hover {
  transform: translateY(-1px);
}

.small-button {
  padding: 0.55rem 0.95rem;
  font-size: 0.85rem;
}

.confirm-overlay {
  position: fixed;
  inset: 0;
  background: rgba(9, 17, 28, 0.48);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  z-index: 1100;
}

.confirm-widget {
  width: min(28rem, 100%);
  background: rgba(255, 255, 255, 0.98);
  border: 1px solid #d6e0ea;
  border-radius: 1.15rem;
  box-shadow: 0 1rem 2.5rem rgba(18, 30, 45, 0.18);
  padding: 1.25rem;
}

.confirm-title {
  margin: 0 0 0.45rem;
  color: #13202c;
  font-size: 1.05rem;
  font-weight: 700;
}

.confirm-message {
  margin: 0;
  color: #4e6577;
  line-height: 1.5;
}

.confirm-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  margin-top: 1rem;
}

.networking-toggle {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.6rem;
  border-radius: 999px;
  background: #eef2f5;
  margin-bottom: 0.01rem;
}

.toggle-pill {
  padding: 0.8rem 1.4rem;
  border-radius: 999px;
  text-decoration: none;
  color: #5d7182;
  background: transparent;
  transition: all 0.18s ease;
}

.toggle-pill.active {
  background: #ffffff;
  color: #13202c;
  box-shadow: 0 4px 14px rgba(18, 32, 44, 0.08);
}

.popUp-msg {
  position: fixed;
  top: 5rem;   
  left: 0;
  right: 0;
  margin-inline: auto;
  width: max-content;
  padding: 0.75rem 2rem;
  border-radius: 2rem; 
  font-family: 'Maven Pro', sans-serif;
  font-size: 1.15rem;
}

.popUp-msg.success {
  background: #5d5d5d;
  color: #fff;
}

.popUp-msg.error {
  background: #db7979;
  color: #fff;
}


.action-button {
  border: 1px solid transparent;
  cursor: pointer;
  background: #13202c;
  color: #ffffff;
  padding: 0.85rem 1.4rem;
  transition:
    transform 0.18s ease,
    background-color 0.18s ease,
    border-color 0.18s ease;
}

.action-button:hover {
  transform: translateY(-1px);
}


.popUp-msg {
  position: fixed;
  top: 5rem;   
  left: 0;
  right: 0;
  margin-inline: auto;
  width: max-content;
  padding: 0.75rem 2rem;
  border-radius: 2rem; 
  font-family: 'Maven Pro', sans-serif;
  font-size: 1.15rem;
}

.popUp-msg.success {
  background: #5d5d5d;
  color: #fff;
}

.popUp-msg.error {
  background: #db7979;
  color: #fff;
}
</style>