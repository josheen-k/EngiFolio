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
        <div class="toggle-line">
          <button class="toggle-btn" :class="{ active: currentTab === 'events' }" @click="switchNetworkingTab('events')">
            Events Calendar
          </button>

          <button class="toggle-btn" :class="{ active: currentTab === 'contacts'}" @click="switchNetworkingTab('contacts')">
            industry Contacts
          </button>
          <div class="toggle-pill" :class="currentTab === 'contacts' ? 'pill-right' : 'pill-left'"></div>
        </div>
      </div>

      <RouterView />
    </section>
  </div>
  <div v-if="popUp.show" class="popUp-msg" :class="popUp.type">
    {{ popUp.message }}
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { RouterView, useRoute, useRouter } from 'vue-router'
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
const router = useRouter()

const currentTab = ref(route.name === 'networking-contacts' ? 'contacts' : 'events')

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
watch(
  () => route.name,
  (name) => {
    currentTab.value = name === 'networking-contacts' ? 'contacts' : 'events'
  }
)

const switchNetworkingTab = (tab) => {
  currentTab.value = tab

  const target = tab === 'events' ? `/student/networking/${route.params.id}` : `/student/networking/${route.params.id}/contacts`

  setTimeout(() => {
    router.push(target)
  }, 100)
}

</script>

<style scoped>
.page {
  font-family: 'Maven Pro', sans-serif;
  background: #ffffff;
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
  font-family: 'Martel', serif;
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
  font-family: 'Montserrat Alternates', sans-serif;
  background: #555555;
  color: #ffffff;
  padding: 0.85rem 1.4rem;
  border: none;
  border-radius: 999px;
  cursor: pointer;
  transition:
    transform 0.18s ease,
    background-color 0.18s ease,
    border-color 0.18s ease;
}

.action-button:hover {
  transform: translateY(-1px);
  background: #333333;
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
  display: flex;
  justify-content: center;
  padding: 1.5rem 0 0.5rem;
}

.toggle-line {
  position: relative;
  display: flex;
  background: #f0f0f0;
  border-radius: 2rem;
  padding: 0.3rem;
  gap: 0;
}

.toggle-pill {
  position: absolute;
  top: 0.3rem;
  bottom: 0.3rem;
  width: calc(50% - 0.3rem);
  background: #ffffff;
  border-radius: 2rem;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.25s ease;
  pointer-events: none;
}

.pill-left {
  transform: translateX(0);
}

.pill-right {
  transform: translateX(calc(100%));
}

.toggle-btn {
  position: relative;
  z-index: 1;
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.95rem;
  color: #888888;
  background: transparent;
  border: none;
  text-decoration: none;
  padding: 0.45rem 1.5rem 0.45rem 1.5rem;
  cursor: pointer;
  transition: color 0.2s ease;
}

.toggle-btn.active {
  color: #222222;
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

.field-input.form-control.field-error {
  border-color: #db7979;
  background: #fff5f5;
  box-shadow: #db7979;
}

.error-message {
  color:  #db7979;
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