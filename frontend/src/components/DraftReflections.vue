<template>
  <div class="drafts-wrap">
    <div class="drafts-header">
      <h1 class="compt-title">Drafts Saved</h1>

      <div class="header-ctrls">
        <!-- Sort Control -->
        <div class="filter-wrap" ref="sortRef">
          <button class="btn btn-add" @click="sortDdOpen = !sortDdOpen">Sort</button>
          <div v-if="sortDdOpen" class="filter-dd">
            <p class="filter-heading">Sort by</p>
              <div class="d-flex flex-column gap-1 mb-3">
                <label class="filter-option" v-for="opt in sortByOptions" :key="opt.value">
                  <input type="radio" :value="opt.value" v-model="sortBy" class="filter-radio"  @click="sortOrder = 'asc'"/>{{ opt.label }}
                </label>
              </div>
            <p class="filter-heading">Order</p>
            <div class="d-flex flex-column gap-1">
                <label class="filter-option">
                  <input type="radio" value="asc" v-model="sortOrder" class="filter-radio"/>
                  {{ sortBy === 'date' ? 'Newest to Oldest' : 'A to Z' }}
                </label>
                <label class="filter-option">
                  <input type="radio" value="desc" v-model="sortOrder" class="filter-radio"/>
                  {{ sortBy === 'date' ? 'Oldest to Newest' : 'Z to A' }}
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
                <input type="checkbox" :value="opt.label" v-model="reflecFilterLevel" class="filter-radio" />
                {{ opt.label }}
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
    <div v-if="processedDrafts.length" class="row g-3">
      <div class="col-6 col-sm-4 col-md-3" v-for="(item, i) in processedDrafts" :key="i">
        <div class="draft-card h-100" @click="openReflec(item)">
          <p class="draft-title" :data-tooltip='item.reflec.experience_title'>{{ item.reflec.experience_title }}</p>
          <div class="d-flex align-items-center gap-2">
            <span class="compt-pill">Competency {{ item.comptId }}</span>
            <!-- Simplified Delete -->
            <img class="plus-btn" src="@/assets/del.png" @click.stop="doDelete(item)">
          </div>
          <p class="txt-lvl mb-0 mt-1">Last updated: {{ formatDate(item.reflec.updated_at) }}</p>
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
      <button class="btn btn-filter" @click="showDeleteConfirm = false, draftEntryToDelete = null">Cancel</button>
      <button class="btn btn-add rounded-pill px-4" @click="confirmDelete">Delete</button>
    </div>
  </div>
</div>
<div v-if="popUp.show" class="popUp-msg" :class="popUp.type">
  {{ popUp.message }}
</div>

  <ViewReflection 
    v-if="viewReflec.show"
    :show="viewReflec.show" 
    :reflec="viewReflec.reflec" 
    :compt="viewReflec.compt" 
    :index="viewReflec.index"
    :levelOptions="levelOptions"
    :categories="categories"
    @close="viewReflec.show = false" 
    @refresh="onSaveReflec"
  />
</template>


<script setup>
import { ref, computed } from 'vue'
import ViewReflection from '@/components/ViewReflection.vue'
import { formatDate, yearOptions, sortByOptions } from '@/composables/useCompetencies.js'
import { onClickOutside } from '@vueuse/core'
import api from "@/services/api"

// Catches components passed down though the eaCompetencies template
// Categories contains competency data and student entry data
const props = defineProps({
  categories: { type: Array, required: true },
  levelOptions: { type: Array, required: true }
});

// Signal parent to reload the data when changed
const emit = defineEmits(['refresh']);

// Object to store data about the popup message
const popUp = ref({ show: false, message: '', type: '' })
// Time the popup can be viewed for. Currently set to 3 seconds allow time for the user to view the message
const popUpTime = 3000

// Used to display the popup message and the type being either success or error
const showPopUp = (message, type) => {
  popUp.value = { show: true, message, type }
  setTimeout(() => popUp.value.show = false, popUpTime)
}

// Called by refresh from viewReflection, shows the message depending on the action taken
function onSaveReflec(statusId, entryName) {
  // Pass refresh to eaCompetencies page
  emit('refresh')
  // Convert status id to number so can compare to 2
  // Determines whether the entry was saved as a draft or published
  if (Number(statusId) === -1) {
    showPopUp(`Draft has successfully been deleted.`, "success")
  } 
  else if (Number(statusId) === 1) {
    showPopUp(`${entryName} has been saved to drafts.`, "success")
  } else {
    showPopUp(`${entryName} has been published.`, "success")
  }
}

// Sort refs
const sortRef = ref(null)
const sortDdOpen = ref(false)
const sortBy = ref('date')
const sortOrder = ref('asc')

// Filter refs
const reflecFilterRef = ref(null)
const reflecFilterDdOpen = ref(false)
const reflecFilterYear = ref([])
const reflecFilterLevel = ref([])

// Used to display the delete pop up
const showDeleteConfirm = ref(false)
const draftEntryToDelete = ref(null)

// Empty view reflection that is filled when the user selects a reflection
const viewReflec = ref({
  show: false,
  reflec: null,
  compt: null,
  index: null
})

// Returns if a filter is selected or not
const hasActiveReflecFilter = computed(function () {
  return reflecFilterYear.value.length > 0 || reflecFilterLevel.value.length > 0
})

// Set sorts back to default values
function clearSort() {
  sortBy.value = 'date'
  sortOrder.value = 'asc'
  sortDdOpen.value = false
}

// Close menu when user clicks outside of the menu
onClickOutside(sortRef, function () {
  sortDdOpen.value = false
})

// Set filters back to default values
function clearReflecFilter() {
  reflecFilterYear.value = []
  reflecFilterLevel.value = []
  reflecFilterDdOpen.value = false
}

// Close menu when user clicks outside of the menu
onClickOutside(reflecFilterRef, function () {
  reflecFilterDdOpen.value = false
})

const processedDrafts = computed(function () {
  let list = []
  // For all categories, competencies and reflections, add the competency to the list
  for (const cat of props.categories) {
    for (const compt of cat.compt) {
      for (const refl of compt.reflec) {
        if (refl.entry_status_id === 1) {
          list.push({
            comptId: compt.displayId,
            reflec: refl,
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
      const currentLvl = item.reflec.entry_level?.competency_level;
      return reflecFilterLevel.value.includes(currentLvl);
    });
  }

  // Sort by looping through the list by comparing two items at a time
  // A negative number means a comes first, positive means b comes first and 0 means stay the same
  list = list.sort((a, b) => {
    // Sorting by name
    if (sortBy.value === 'name') {
      // Sort by alphabetical order
      if (sortOrder.value === 'asc') {
        // Compares a to b and returns negative if a comes before b alphabetically
        return (a.reflec.experience_title || '').localeCompare(b.reflec.experience_title || '')    
      } else {
        // Compares a to b and returns negative if b comes before a alphabetically
        return (b.reflec.experience_title || '').localeCompare(a.reflec.experience_title || '')  
      }
    }

    // Convert date into a number and sort by date
    const dateA = new Date(a.reflec.updated_at);
    const dateB = new Date(b.reflec.updated_at);

    // Sort newest to oldest
    if (sortOrder.value === 'asc') {
      // Positive number means b is before a
      return dateB - dateA
    } else {
      // Positive number means a is before b
      return dateA - dateB
    }
  })
  return list
})

// Runs when the user selects a reflection to open
function openReflec(item) {
  // Finds the original entry in the array as filtering may change where it is
  const originalIndex = item.compt.reflec.findIndex(r => r.entry_id === item.reflec.entry_id);

  viewReflec.value = {
    show: true,
    reflec: item.reflec,
    compt: item.compt,
    index: originalIndex
  }
}

// Show popup for delete, set the entry to be deleted
function doDelete(draftEntry ) {
  draftEntryToDelete.value = draftEntry 
  showDeleteConfirm.value = true
}

// Delete the selected draft after getting confirmation
const confirmDelete = async () => { 
  const id = draftEntryToDelete.value.reflec.entry_id
  
  try {
    // Call backend to delete the competency entry
    await api.delete(`/competency-entries/${id}`)
    // Reset values
    showDeleteConfirm.value = false
    draftEntryToDelete.value = null
    showPopUp(`Draft has successfully been deleted.`, "success")
    emit('refresh') 
  } catch (error) {
    showPopUp("Error when deleting the draft.", "error")
  }
}
</script>

<style scoped>
.drafts-wrap {
  max-width: 100%;
}

.drafts-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 2rem;
}

.header-ctrls {
  display: flex;
  gap: 0.5rem;
  align-items: center;
  flex-wrap: wrap;
  justify-content: center;
}

.compt-title {
  font-family: 'Martel', serif;
  font-size: clamp(1.3rem, 4vw, 2rem);
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

.filter-dd {
  position: absolute;
  top: calc(100% + 0.5rem);
  left: 0;
  background: #ffffff;
  border: 0.09rem solid #e0e0e0;
  border-radius: 1rem;
  padding: 1rem 1.25rem;
  min-width: 10rem;
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

.draft-card {
  border-radius: 1.5rem;
  border: 1.5px solid #bababa;
  padding: 0.75rem 1rem;
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
  font-size: clamp(0.85rem, 2.5vw, 1.1rem);
  color: #444444;
  text-decoration: underline;
  margin-bottom: 0.6rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  cursor: default;
}

.draft-title::after {
  content: attr(data-tooltip);
  position: absolute;
  bottom: calc(100% + 0.4rem);
  left: 50%;
  transform: translateX(-50%);
  background: #727272;
  color: #ffffff;
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.75rem;
  white-space: normal;
  width: max-content;
  max-width: 14rem;
  padding: 0.4rem 0.65rem;
  border-radius: 0.5rem;
  box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.2);
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.15s ease;
  z-index: 5;
}

.draft-title:hover::after {
  opacity: 1;
}

.compt-pill {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.8rem;
  color: #555555;
  background: #e8e8e8;
  border-radius: 2rem;
  padding: 0.2rem 0.6rem;
}

.plus-btn {
  width: 1.5rem;
  height: 1.5rem;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.plus-btn:hover {
  transform: scale(1.1);
}

.txt-lvl {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.85rem;
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
  font-size: 0.9rem;
  background: #e6e6e6;
}

.btn-filter:hover, .btn-filter-sm:hover {
  background: #666666;
  color: #ffffff;
}

.btn-add {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  font-size: 0.9rem;
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

.view-popup {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.3);
  backdrop-filter: blur(0.375rem);
  z-index: 4;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.25rem;
}

.delete-box {
  background: #ffffff;
  border-radius: 1.25rem;
  max-width: 22.5rem;
  width: 100%;
  box-shadow: 0 1.25rem 3.75rem rgba(0, 0, 0, 0.2);
}

.field-label {
  font-family: 'Martel', sans-serif;
  font-size: 1rem;
  color: #222222;
}

.field-desc {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.8rem;
  line-height: 1.5;
  color: #444444;
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

@media (min-width: 768px) {
  .drafts-header {
    position: relative;
    flex-direction: row;
    justify-content: center;
  }

  .header-ctrls {
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    justify-content: flex-end;
  }
  .draft-card {
    padding: 1rem 1.25rem;
  }

  .btn-filter, .btn-add {
    font-size: 1rem;
  }
}
</style>