<template>
  <div class="feedback-wrap">
    <div class="feedback-header">
      <h1 class="compt-title">Feedback Received</h1>
      <div class="d-flex gap-2">
        <button class="btn btn-sort">Sort</button>
        <button class="btn btn-filter">Add filter</button>
      </div>
    </div>

    <!-- feedback list-->
    <div v-if="feedbackItems.length" class="d-flex flex-column gap-1">
      <div class="feedback-item" v-for="(item, i) in feedbackItems" :key="i">
        <div class="feedback-row">
          <img class="triangle" :class="{ open: openStates[i] }" src="@/assets/triangle.png" @click="toggleOpen(i)"/>

          <span class="feedback-summary">
            {{ item.reflec.feedbackAuthor }} commented on
            "<span class="reflec-link" @click="openReflec(item)">{{ item.reflec.title }}</span>"
            (Competency {{ item.comptId }})
          </span>
        </div>

        <!-- expanded feedback text-->
        <div v-if="openStates[i]" class="feedback-body">
          <p class="feedback-txt">{{ item.reflec.feedback }}</p>
        </div>
      </div>
    </div>

    <!-- empty-->
    <div v-else class="empty-state">
      <p class="empty-txt">No feedback received yet.</p>
      <p class="empty-sub">Feedback from supervisor will appear here once received.</p>
    </div>
  </div>

  <ViewReflection :show="viewReflec.show" :reflec="viewReflec.reflec" :compt="viewReflec.compt" :index="viewReflec.index"
  @close="viewReflec.show = false" @save="onSave" @delete="onDelete"/>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import ViewReflection from '@/components/ViewReflection.vue'
import { currentCategories } from '@/useCompetencies.js'

const feedbackItems = computed(function () {
  const out = []

  for (const cat of currentCategories.value) {
    for (const compt of cat.compt) {
      for (const r of compt.reflec) {
        if (r.feedback) {
          out.push({
            comptId: compt.id,
            reflec: r,
            compt: compt
          })
        }
      }
    }
  }

  return out
})

const openStates = ref({})
watch(feedbackItems, function (items) {
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

function onSave({ index, updated }) {
  const r = viewReflec.value.reflec

  for (const cat of currentCategories.value) {
    for (const compt of cat.compt) {
      const idx = compt.reflec.indexOf(r)
      if (idx!== -1) {
        Object.assign(compt.reflec[idx], updated)
        break
      }
    }
  }
}

function onDelete() {
  const r = viewReflec.value.reflec

  for (const cat of currentCategories.value) {
    for (const compt of cat.compt) {
      const idx = compt.reflec.indexOf(r)
      if (idx!== -1) {
        compt.reflec.splice(idx, 1)
        break
      }
    }
  }
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

.feedback-header>.d-flex {
  position: absolute;
  right: 0;
}

.compt-title {
  font-family: 'Martel', serif;
  font-size: 2rem;
  color: #2b2b2bc5;
  font-weight: lighter;
  margin-bottom: 0;
  text-align: center;
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

.btn-sort {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  font-size: 1rem;
  background: #555555;
  color: #ffffff;
}

.btn-sort:hover {
  background: #333333;
  color: #ffffff;
}

.btn-filter {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  font-size: 1rem;
  background: #e6e6e6;
  color: #222222;
}

.btn-filter:hover {
  background: #666666;
  color: #ffffff;
}
</style>