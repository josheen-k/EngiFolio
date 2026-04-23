<template>
  <div class="drafts-wrap">

    <div class="drafts-header">
      <h1 class="compt-title">Drafts Saved</h1>
      <button class="btn btn-filter">Add filter</button>
    </div>

    <div v-if="drafts.length" class="d-flex flex-wrap gap-3">
      <div class="draft-card" v-for="(item, i) in drafts" :key="i" @click="openReflec(item)">

        <p class="draft-title">{{ item.reflec.title || 'Draft Title' }}</p>

        <div class="d-flex align-items-center gap-2">
          <span class="compt-pill">Competency {{ item.comptId }}</span>
          <img class="plus-btn" src="@/assets/del.png" @click.stop="deleteDraft(item)">
        </div>

      </div>
    </div>

    <div v-else class="empty-state">
      <p class="empty-txt">No drafts saved yet.</p>
      <p class="empty-sub">When you save a reflection as a draft, it will appear here.</p>
    </div>

  </div>

  <ViewReflection :show="viewReflec.show" :reflec="viewReflec.reflec" :compt="viewReflec.compt" :index="viewReflec.index"
    @close="viewReflec.show = false" @save="onSave" @delete="onDelete"/>
</template>

<script setup>
import { ref, computed } from 'vue'
import ViewReflection from '@/components/ViewReflection.vue'
import { currentCategories } from '@/useCompetencies.js'

// collect all reflections marked as draft across all competencies
const drafts = computed(function () {
  const out = []

  for (const cat of currentCategories.value) {
    for (const compt of cat.compt) {
      for (const r of compt.reflec) {
        if (r.isDraft) {
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
      if (idx !== -1) {
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

function deleteDraft(item) {
  const idx = item.compt.reflec.indexOf(item.reflec)
  if (idx!== -1) {
    item.compt.reflec.splice(idx, 1)
  }
}
</script>

<style scoped>
.drafts-wrap {
  max-width: 90%;
}

.drafts-header {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 2rem;
}

.drafts-header > .btn {
  position: absolute;
  right: 0;
}

.compt-title {
  font-family: 'Martel', serif;
  font-size: 2rem;
  color: #2b2b2bc5;
  font-weight: lighter;
  text-align: center;
  margin-bottom: 0;
}

.draft-card {
  width: 13.75rem;
  border-radius: 1.5rem;
  border: 1.5px solid #bababa;
  padding: 1rem 1.25rem;
  cursor: pointer;
  transition: box-shadow 0.2s ease, transform 0.2s ease;
  background: #ffffff;
}

.draft-card:hover {
  box-shadow: 0 0.25rem 0.75rem #e5e5e5;
  transform: translateY(-2px);
}

.draft-title {
  font-family: 'Maven Pro', sans-serif;
  font-size: 1.1rem;
  color: #444444;
  text-decoration: underline;
  margin-bottom: 0.6rem;
}

.compt-pill {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.9rem;
  color: #555555;
  background: #e8e8e8;
  border-radius: 999px;
  padding: 0.2rem 0.8rem;
}

.plus-btn {
  width: 1.7rem;
  height: 1.7rem;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.plus-btn:hover {
  transform: scale(1.1);
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