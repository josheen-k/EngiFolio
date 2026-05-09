<template>
  <div class="feedback-wrap">
    <div class="feedback-header">
      <h1 class="compt-title">Feedback Received</h1>

      <!-- search bar instead of filter/sort-->
      <div class="search-wrap">
        <img src="@/assets/search.png" class="search-icon" alt="search"/>
        <input v-model="searchQuery" class="search-input" placeholder="Search by staff name..." type="text"/>
        <button v-if="searchQuery" class="search-clear" @click="searchQuery = ''">×</button>
      </div>
    </div>

    <!-- feedback list-->
    <div v-if="filteredItems.length" class="d-flex flex-column gap-1">
      <div class="feedback-item" v-for="(item, i) in filteredItems" :key="i">
        <div class="feedback-row">
          <img class="triangle" :class="{ open: openStates[i] }" src="@/assets/triangle.png" @click="toggleOpen(i)"/>

          <span class="feedback-summary">
            {{ item.authorName  }} commented on
            "<span class="reflec-link" @click="openReflec(item)">{{ item.reflec.experience_title}}</span>"
            (Competency {{ item.comptId }})
          </span>
        </div>

        <!-- expanded feedback text-->
        <div v-if="openStates[i]" class="feedback-body">
        <p class="feedback-txt">{{ item.fb.feedback_content }}</p>
        <p class="feedback-date">{{ new Date(item.fb.created_at).toLocaleDateString('en-AU') }}</p>
      </div>
      </div>
    </div>

    <!-- empty (no feedback)-->
    <div v-else-if="!feedbackItems.length" class="empty-state">
      <p class="empty-txt">No feedback received yet.</p>
      <p class="empty-sub">Feedback from supervisor will appear here once received.</p>
    </div>

    <!-- empty (no search result)-->
    <div v-else class="empty-state">
      <p class="empty-txt">No results for "{{ searchQuery }}"</p>
      <p class="empty-sub">Try a different staff name</p>
    </div>
  </div>

  <ViewReflection 
    v-if="viewReflec.show"
    :show="viewReflec.show" 
    :reflec="viewReflec.reflec" 
    :compt="viewReflec.compt" 
    :index="viewReflec.index"
    :levelOptions="levelOptions"
    @close="viewReflec.show = false" 
    @refresh="onRefresh"
  />
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import ViewReflection from '@/components/ViewReflection.vue'

const props = defineProps({
  categories: { type: Array, required: true },
  levelOptions: { type: Array, required: true }
});


const emit = defineEmits(['refresh'])
const searchQuery = ref('')

// collect all reflections with feedback
const feedbackItems = computed(function () {
  const out = []

  for (const cat of props.categories) {
    for (const compt of cat.compt) {
      for (const r of compt.reflec) {
        for (const fb of r.feedback) { 
          if (r.feedback?.length) {
            out.push({
              comptId: compt.displayId,
              compt: compt,
              reflec: r,
              fb: fb,
              authorName: `${fb.staff.first_name} ${fb.staff.last_name}`
            })
          }
        }
      }
    }
  }
  return out
})

// filter by search query of feedbackAuthor
const filteredItems = computed(function () {
  if (!searchQuery.value.trim()) return feedbackItems.value
  const query = searchQuery.value.toLowerCase().trim()
  return feedbackItems.value.filter(item =>
    item.authorName.toLowerCase().includes(query)
  )
})

const openStates = ref({})
watch(filteredItems, function (items) {
  for (let i = 0; i < items.length; i++) {
    if (openStates.value[i]===undefined) {
      openStates.value[i] = true
    }
  }
}, { immediate: true })

function toggleOpen(i) {
  openStates.value[i] = !openStates.value[i]
}

// view reflection popup
const viewReflec = ref({
  show: false,
  reflec: null,
  compt: null,
  index: null
})

function openReflec(item) {
  viewReflec.value = {
    show: true,
    reflec: item.reflec,
    compt: { id: item.comptId },
    index: item.compt.reflec.indexOf(item.reflec)
  }
}

function onRefresh() {
  emit('refresh')
  viewReflec.value.show = false
}
</script>

<style scoped>
.feedback-wrap {
  max-width: 90%;
}

.feedback-header {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 2rem;
}

.compt-title {
  font-family: 'Martel', serif;
  font-size: 2rem;
  color: #2b2b2bc5;
  font-weight: lighter;
  margin-bottom: 0;
  text-align: center;
}

.search-wrap {
  position: absolute;
  right: 0;
  display: flex;
  align-items: center;
  background: #f5f5f5;
  border: 1px solid #e0e0e0;
  border-radius: 2rem;
  padding: 0.35rem 0.75rem;
  gap: 0.4rem;
}

.search-wrap:focus-within {
  border-color: #888888;
  background: #ffffff;
}

.search-icon {
  width: 1rem;
  height: 1rem;
  object-fit: contain;
}

.search-input {
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.8rem;
  color: #333333;
  background: transparent;
  border: none;
  outline: none;
  width: 10rem;
}

.search-input::placeholder {
  color: #979797;
}

.search-clear {
  background: none;
  border: none;
  font-size: 0.8rem;
  color: #aaaaaa;
  cursor: pointer;
  transition: color 0.2s ease;
}

.search-clear:hover {
  color: #555555;
}

.feedback-item {
  padding: 0.4rem 0;
}

.feedback-row {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.triangle {
  width: 0.8rem;
  height: 0.8rem;
  flex-shrink: 0;
  transition: transform 0.2s ease;
  transform: rotate(0deg);
  cursor: pointer;
}

.triangle.open {
  transform: rotate(90deg);
}

.feedback-summary {
  font-family: 'Maven Pro', sans-serif;
  font-size: 1.2rem;
  color: #333333;
}

.reflec-link {
  text-decoration: underline;
  color: #333333;
  cursor: pointer;
}

.reflec-link:hover {
  color: #555555;
}

.feedback-body {
  padding-left: 1.4rem;
  padding-top: 0.3rem;
}

.feedback-txt {
  font-family: 'Maven Pro', sans-serif;
  font-size: 1rem;
  color: #666666;
  font-style: italic;
  margin-bottom: 0.25rem;
}

.feedback-date {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.8rem;
  color: #aaaaaa;
  margin-bottom: 0;
}

.empty-state {
  text-align: center;
  padding: 4rem 0;
}

.empty-txt {
  font-family: 'Maven Pro', sans-serif;
  font-size: 1.1rem;
  color: #888888;
  margin-bottom: 0.5rem;
}

.empty-sub {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.95rem;
  color: #aaaaaa;
}
</style>