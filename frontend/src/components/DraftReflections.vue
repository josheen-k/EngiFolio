<template>
  <div class="drafts-wrap">
    <div class="drafts-header">
      <h1 class="compt-title">Drafts Saved</h1>

      <div class="d-flex gap-2 align-items-center">
        <!-- Sort Control -->
        <div class="filter-wrap" ref="sortRef">
          <button class="btn btn-add" @click="sortDdOpen = !sortDdOpen">Sort</button>
          <div v-if="sortDdOpen" class="filter-dd">
            <p class="filter-heading">Sort by</p>
            <div class="d-flex flex-column gap-1 mb-3">
              <label class="filter-option" v-for="opt in sortByOptions" :key="opt.value">
                <input type="radio" :value="opt.value" v-model="sortBy" class="filter-radio" />
                {{ opt.label }}
              </label>
            </div>
            <p class="filter-heading">Order</p>
            <div class="d-flex flex-column gap-1">
              <label class="filter-option">
                <input type="radio" value="asc" v-model="sortOrder" class="filter-radio" />Ascending
              </label>
              <label class="filter-option">
                <input type="radio" value="desc" v-model="sortOrder" class="filter-radio" />Descending
              </label>
            </div>
            <div class="d-flex gap-2 mt-3 justify-content-end">
              <button class="btn btn-filter-sm" @click="clearSort">Clear</button>
            </div>
          </div>
        </div>

        <!-- Filter Control -->
        <div class="filter-wrap" ref="reflecFilterRef">
          <button class="btn btn-filter" @click="reflecFilterDdOpen = !reflecFilterDdOpen">
            {{ hasActiveReflecFilter ? 'See filters' : 'Add filter' }}
          </button>
          <div v-if="reflecFilterDdOpen" class="filter-dd">
            <p class="filter-heading">Year</p>
            <div class="d-flex flex-column gap-1 mb-3">
              <label class="filter-option" v-for="opt in yearOptions" :key="opt.value">
                <input type="checkbox" :value="opt.value" v-model="reflecFilterYear" class="filter-radio" />
                {{ opt.label }}
              </label>
            </div>
            <p class="filter-heading">Attainment level</p>
            <div class="d-flex flex-column gap-1">
              <label class="filter-option" v-for="opt in levelOptions" :key="opt.id">
                <input type="checkbox" :value="opt.competency_level" v-model="reflecFilterLevel" class="filter-radio" />
                {{ opt.competency_level }}
              </label>
            </div>
            <div class="d-flex gap-2 mt-3 justify-content-end">
              <button class="btn btn-filter-sm" @click="clearReflecFilter">Clear</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtered + Sorted Drafts -->
    <div v-if="processedDrafts.length" class="d-flex flex-wrap gap-3">
      <div class="draft-card" v-for="(item, i) in processedDrafts" :key="i" @click="openReflec(item)">
        <p class="draft-title">{{ item.reflec.experience_title || 'Untitled Draft' }}</p>
        <div class="d-flex align-items-center gap-2">
          <span class="compt-pill">Competency {{ item.comptId }}</span>
          <!-- Simplified Delete -->
          <img class="plus-btn" src="@/assets/del.png" @click.stop="doDelete(item)">
        </div>
      </div>
    </div>



    <div v-else class="empty-state">
      <p class="empty-txt">No drafts saved yet.</p>
      <p class="empty-sub">When you save a reflection as a draft, it will appear here.</p>
    </div>
  </div>

      <div v-if="showDeleteConfirm" class="view-popup" @click.self="showDeleteConfirm = false">
  <div class="delete-box text-center p-4">
    <h5 class="fw-bold mb-2 field-label">Delete this draft?</h5>
    <p class="field-desc mb-4">This action cannot be undone.</p>
    <div class="d-flex gap-2 justify-content-center">
      <button class="btn btn-filter" @click="showDeleteConfirm = false">Cancel</button>
      <button class="btn btn-add rounded-pill px-4" @click="confirmDelete">Delete</button>
    </div>
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
    @refresh="$emit('refresh')"
  />
</template>


<script setup>
import { ref, computed } from 'vue'
import ViewReflection from '@/components/ViewReflection.vue'
import { onClickOutside } from '@vueuse/core'
import api from "@/services/api"

// Allows for the eaCompetency page to pass the vales along
const props = defineProps({
  categories: { type: Array, required: true },
  levelOptions: { type: Array, required: true }
});

// Signal parent to reload the data when changed
const emit = defineEmits(['refresh']);

const sortRef = ref(null)
const sortDdOpen = ref(false)
const sortBy = ref('date')
const sortOrder = ref('desc')

const reflecFilterRef = ref(null)
const reflecFilterDdOpen = ref(false)
const reflecFilterYear = ref([])
const reflecFilterLevel = ref([])

// Used to display the delete pop up
const showDeleteConfirm = ref(false)
const itemToDelete = ref(null)

const sortByOptions = [
  { value: 'date', label: 'Date' },
  { value: 'name', label: 'Title (A–Z)' }
]

const yearOptions = [
  { value: 0, label: 'Prior to degree' },
  { value: 1, label: 'Year 1' },
  { value: 2, label: 'Year 2' },
  { value: 3, label: 'Year 3' },
  { value: 4, label: 'Year 4' }
]

const hasActiveReflecFilter = computed(function () {
  return reflecFilterYear.value.length > 0 || reflecFilterLevel.value.length > 0
})


function clearSort() {
  sortBy.value = 'date'
  sortOrder.value = 'desc'
  sortDdOpen.value = false
}

onClickOutside(sortRef, function () {
  sortDdOpen.value = false
})

function clearReflecFilter() {
  reflecFilterYear.value = []
  reflecFilterLevel.value = []
  reflecFilterDdOpen.value = false
}

onClickOutside(reflecFilterRef, function () {
  reflecFilterDdOpen.value = false
})

const processedDrafts = computed(function () {
  let list = []
  for (const cat of props.categories) {
    for (const compt of cat.compt) {
      for (const r of compt.reflec) {
        if (r.entry_status_id === 1) {
          list.push({
            comptId: compt.id,
            reflec: r,
            compt: compt
          })
        }
      }
    }
  }

  // Filter by year
  if (reflecFilterYear.value.length > 0) {
    list = list.filter(r => reflecFilterYear.value.includes(r.reflec.associated_year))
  }

  // filter by level
  if (reflecFilterLevel.value.length > 0) {
    list = list.filter(item => {
      const lvl = item.reflec.entry_level?.competency_level;
      return reflecFilterLevel.value.includes(lvl);
    });
  }

  // sort
  list = list.sort((a, b) => {
    if (sortBy.value === 'name') {
      if (sortOrder.value === 'asc') {
        return (a.experience_title || '').localeCompare(b.experience_title || '')
      } else {
        return (b.experience_title || '').localeCompare(a.experience_title || '')
      }
    }

    // Convert date into a number
    const da = new Date(a.updated_at);
    const db = new Date(b.updated_at);

    if (sortOrder.value === 'asc') {
      return da-db
    } else {
      return db-da
    }
  })
  return list
})

const viewReflec = ref({
  show: false,
  reflec: null,
  compt: null,
  index: null
})

function openReflec(item) {
  // Finds the original entry
  const originalIndex = item.compt.reflec.findIndex(r => r.entry_id === item.reflec.entry_id);

  viewReflec.value = {
    show: true,
    reflec: item.reflec,
    compt: item.compt,
    index: originalIndex
  }
}

// Show popup for delete
function doDelete(item) {
  itemToDelete.value = item
  showDeleteConfirm.value = true
}

const confirmDelete = async () => { 
  const id = itemToDelete.value.reflec.entry_id
  
  try {
    await api.delete(`/competency-entries/${id}`)
    // Reset values
    showDeleteConfirm.value = false
    itemToDelete.value = null
    emit('refresh') 

  } catch (error) {
    console.error(error)
    alert("Error when deleting the draft: ", error)
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

.drafts-header>.d-flex {
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.compt-title {
  font-family: 'Martel', serif;
  font-size: 2rem;
  color: #2b2b2bc5;
  font-weight: lighter;
  text-align: center;
  margin-bottom: 0;
}

.filter-wrap {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 0.5rem;
}

.title-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 0.5rem;
}

.filter-dd {
  position: absolute;
  top: calc(100% + 0.5rem);
  right: 0;
  background: #ffffff;
  border: 0.09rem solid #e0e0e0;
  border-radius: 1rem;
  padding: 1rem 1.25rem;
  min-width: 16rem;
  box-shadow: 0 0.5rem 1.5rem #e5e5e5;
  z-index: 10;
}

.filter-heading {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.75rem;
  font-weight: bold;
  color: #888888;
  margin-bottom: 0.4rem;
}

.filter-option {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.85rem;
  color: #333333;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
}

.filter-radio {
  cursor: pointer;
}

.btn-title-wrap {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 3rem;
}

.btn-title-wrap>.btn {
  position: absolute;
  left: 0;
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

.btn-filter, .btn-filter-sm {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  font-size: 1rem;
  background: #e6e6e6;
}

.btn-filter:hover, .btn-filter-sm:hover {
  background: #666666;
  color: #ffffff;
}

.btn-add {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  font-size: 1rem;
  color: #ffffff;
  background: #555555;
}

.btn-add:hover {
  color: #ffffff;
  background: #333333;
}

.btn-filter-sm {
  font-size: 0.8rem !important;
}
</style>