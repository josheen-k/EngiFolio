<template>
  <div class="curr-compt">
    <!--compt detailed view-->
    <div v-if="selectedCompt" class="detail">

      <div class="btn-title-wrap">
        <button class="btn btn-filter" @click="closeDetail">Go back</button>
        <h2 class="compt-title mb-0">Competency {{ selectedCompt.displayId }}</h2>
      </div>

      <p class="fs-5">Category: <em>{{ selectedCompt.category }}</em></p>
      <p class="fs-5 mb-1">Description:</p>
      <p class="detail-txt">{{ selectedCompt.description }}</p>

      <p class="fs-5">Indicators Of Attainment:</p>
        <ul class="ps-3">
        <li class="detail-txt" v-for="(ind, i) in selectedCompt.attainmentIndicators" :key="i">{{ ind.attainment_indicator }}</li>
      </ul>

      <div class="d-flex justify-content-between detail-stats">
        <p class="fs-5">Total reflection entries: <em>{{ publishedOnly(selectedCompt).length }}</em></p>
        <p class="fs-5">Highest attainment level: <em>{{ getLvl(selectedCompt) }}</em></p>
      </div>

      <div class="entries-header my-3">
        <h3 class="entry-title mb-0">Your Entries</h3>
        <div class="ctrl-actions">

          <!-- sort control -->
          <div class="filter-wrap" ref="sortRef">
            <button class="btn btn-filter" @click="sortDdOpen = !sortDdOpen">Sort</button>

            <div v-if="sortDdOpen" class="filter-dd">
              <p class="filter-heading">Sort by</p>
              <div class="d-flex flex-column gap-1 mb-3">
                <label class="filter-option" v-for="opt in sortByOptions" :key="opt.value">
                  <input type="radio" :value="opt.value" v-model="sortBy" class="filter-radio"  @click="sortOrder = 'desc'"/>{{ opt.label }}
                </label>
              </div>

              <p class="filter-heading">Order</p>
              <div class="d-flex flex-column gap-1">
                <label class="filter-option">
                  <input type="radio" value="desc" v-model="sortOrder" class="filter-radio"/>
                  {{ sortBy === 'date' ? 'Newest to Oldest' : 'A to Z' }}
                </label>
                <label class="filter-option">
                  <input type="radio" value="asc" v-model="sortOrder" class="filter-radio"/>
                  {{ sortBy === 'date' ? 'Oldest to Newest' : 'Z to A' }}
                </label>
              </div>

              <div class="d-flex gap-2 mt-3 justify-content-end">
                <button class="btn btn-filter-sm" @click="clearSort">Clear</button>
              </div>
            </div>
          </div>

          <!-- reflection filter control -->
          <div class="filter-wrap" ref="reflecFilterRef">
            <button class="btn btn-filter" @click="reflecFilterDdOpen = !reflecFilterDdOpen">
            {{ hasActiveReflecFilter ? 'See filters' : 'Add filter' }}
            </button>

            <div v-if="reflecFilterDdOpen" class="filter-dd">
              <p class="filter-heading">Year</p>
              <div class="d-flex flex-column gap-1 mb-3">
                <label class="filter-option" v-for="opt in yearOptions" :key="opt.value">
                  <input type="checkbox" :value="opt.value" v-model="reflecFilterYear" class="filter-radio"/>{{ opt.label }}
                </label>
              </div>

              <p class="filter-heading">Attainment level</p>
              <div class="d-flex flex-column gap-1">
                <label class="filter-option" v-for="opt in levelOptions" :key="opt.value">
                  <input type="checkbox" :value="opt.label" v-model="reflecFilterLevel" class="filter-radio"/>{{ opt.label }}
                </label>
              </div>

              <div class="d-flex gap-2 mt-3 justify-content-end">
                <button class="btn btn-filter-sm" @click="clearReflecFilter">Clear</button>
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-add" @click="openAdd(selectedCompt.id)">Add new</button>
        </div>
      </div>

      <!-- filtered + sorted reflection entries -->
      <div v-if="processedReflec.length" class="row g-3">
        <div class="col-6 col-sm-4 col-md-3 col-xl-3" v-for="reflec in processedReflec" :key="reflec.entry_id">
          <div class="card compt-card p-3 h-70 reflec-card"
            @click="openReflec(reflec, reflec.entry_id)">
            <p class="compt-label mb-2">{{ reflec.experience_title }}</p>
            <div class="d-flex align-items-center gap-2 mb-2">
              <span class="reflecs rounded-pill">{{ reflec.associated_year === 0 ? 'PRIOR' : 'YEAR ' + reflec.associated_year }}</span>
              <span class="txt-lvl">{{ reflec.entry_level?.competency_level }}</span>
            </div>
            <p class="txt-lvl mb-0">Last updated: {{ formatDate(reflec.updated_at) }}</p>
          </div>
        </div>
      </div>
      <p v-else class="text-secondary">No reflection entries yet.</p>
    </div>

    <!--all compt view-->
    <div v-else>
      <!-- title row with add filter button -->
      <div class="title-row">
        <h1 class="compt-title">Current Competencies</h1>

        <div class="filter-wrap" ref="filterRef">
          <button class="btn btn-filter" @click="toggleDd()">
          {{ hasActiveFilter ? 'See filters' : 'Add filter' }}</button>

          <!-- filter dropdown -->
          <div v-if="ddOpen" class="filter-dd">
            <p class="filter-heading">Reflection status</p>
            <div class="d-flex flex-column gap-1 mb-3">
              <label class="filter-option" v-for="opt in reflecOption" :key="opt.value">
                <input type="radio" :value="opt.value" v-model="filterReflec" class="filter-radio"/>{{ opt.label }}
              </label>
            </div>

            <p class="filter-heading">Highest attainment level</p>
            <div class="d-flex flex-column gap-1">
              <label class="filter-option" v-for="opt in levelOptions" :key="opt.value">
                <input type="checkbox" :value="opt.label" v-model="filterLevel" class="filter-radio"/>{{ opt.label }}
              </label>
            </div>

            <div class="d-flex gap-2 mt-3 justify-content-end">
              <button class="btn btn-filter-sm" @click="clearFilter()">Clear</button>
            </div>
          </div>
        </div>
      </div>

      <!-- category sections -->
      <div class="mb-4" v-for="c in categories" :key="c.key">
        <div class="d-flex align-items-center gap-2 mb-3 category" @click="c.open = !c.open">
          <img class="triangle" :class="{ open: c.open }" src="@/assets/triangle.png"/>
          <span class="c-label">{{ c.label }}</span>
          <span class="txt">{{ filteredCompts(c).length }}</span>
        </div>

        <div v-if="c.open">
          <div v-if="filteredCompts(c).length" class="row g-3">
            <div class="col-6 col-sm-4 col-md-3 col-xl-3" v-for="compt in filteredCompts(c)" :key="compt.id">
              <div class="card compt-card p-3" @click="openDetail(compt, c.label)">
                <h5 class="compt-label mb-2">Competency {{ compt.displayId }}</h5>
                <!-- <h5 class="compt-label mb-2">{{ compt.indicator_name }}</h5> -->
                <div class="d-flex align-items-center justify-content-start mb-2 gap-2">
                  <span class="rounded-pill px-3 py-1" :class="publishedOnly(compt).length ? 'reflecs-blue' : 'reflecs-red'">
                    {{ publishedOnly(compt).length }} reflection{{ publishedOnly(compt).length !== 1 ? 's' : '' }}
                  </span>
                  <img class="plus-btn" src="@/assets/plus-btn.png" @click.stop="openAdd(compt.id)"/>
                </div>
                <p class="txt-lvl mb-0">Highest level: {{ getLvl(compt) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <ViewReflection 
    v-if="viewReflec.show && viewReflec.reflec"
    :show="viewReflec.show" 
    :reflec="viewReflec.reflec" 
    :compt="viewReflec.compt" 
    :index="viewReflec.index"
    :levelOptions="levelOptions"
    @close="closeReflec" 
    @refresh="onSaveReflec"
  />

  <AddReflection 
    v-if="addModal.show"
    :show="addModal.show" 
    :initialComptId="addModal.comptId" 
    :levelOptions="levelOptions"
    :categories="categories"
    @close="addModal.show = false" 
    @add="onAddReflec"
    @refresh="onSaveReflec"
  />
</template>

<script setup>
import { computed, ref, watch, useRoute, useRouter  } from 'vue'
import ViewReflection from '@/components/ViewReflection.vue'
import AddReflection from '@/components/AddReflection.vue'
import { getLvl, publishedReflec, formatDate, yearOptions, sortByOptions } from '@/composables/useCompetencies.js'
import { onClickOutside } from '@vueuse/core';

const route = useRoute()
const router = useRouter()

// Allows for the eaCompetency page to pass the vales along
const props = defineProps({
  categories: { type: Array, required: true },
  levelOptions: { type: Array, required: true },
  initialIndicatorId: { type: [String, Number], default: null }
});

// Watch for an indicatorId
watch(
  [() => props.initialIndicatorId, () => props.categories],
  ([id, cats]) => {
    if (id && cats.length) {
      for (const cat of cats) {
        const match = cat.compt.find(c => Number(c.id) === Number(id))
        if (match) {
          openDetail(match, cat.label)
          break
        }
      }
    }
  },
  { immediate: true }
)

// Signal parent to reload the data when changed
const emit = defineEmits(['refresh']);
const selectedCompt = ref(null);

// filter options for competencies
const filterRef = ref(null)
const ddOpen = ref(false)
const filterReflec = ref('all')
const filterLevel = ref([])
const reflecFilterRef = ref(null)
const reflecFilterDdOpen = ref(false)
const reflecFilterYear = ref([])
const reflecFilterLevel = ref([])

// Sorting
const sortRef = ref(null)
const sortDdOpen = ref(false)
const sortBy = ref('date')
const sortOrder = ref('desc')  // 'asc'  | 'desc'

watch(() => props.categories, () => {
  if (!selectedCompt.value) return
  for (const cat of props.categories) {
    const updated = cat.compt.find(c => Number(c.id) === Number(selectedCompt.value.id))
    if (updated) {
      selectedCompt.value = {
        ...selectedCompt.value,
        reflec: updated.reflec,
        description: updated.desc,
      }
      break
    }
  }
}, { deep: true })


const reflecOption = [
  { value: 'all', label: 'All competencies' },
  { value: 'has-reflections', label: 'Has at least one reflection' },
  { value: 'no-reflections', label: 'No reflections yet' }
]

const hasActiveFilter = computed(function () {
  return filterReflec.value !== 'all' || filterLevel.value.length > 0
})

function toggleDd() {
  ddOpen.value = !ddOpen.value
}

function clearFilter() {
  filterReflec.value = 'all'
  filterLevel.value = []
  ddOpen.value = false
}

onClickOutside(filterRef, function () {
  ddOpen.value = false;
});

function publishedOnly(compt) {
  return publishedReflec(compt)
}

function filteredCompts(competency) {
  return competency.compt.filter(function (compt) {
    const published = publishedOnly(compt)
    const highestLvl = getLvl(compt)

    // Exclude discontinued competencies
    if (compt.discontinuedDate) return false

    // filter by reflection status
    if (filterReflec.value==='has-reflections' && published.length === 0) {
      return false
    }
    if (filterReflec.value==='no-reflections' && published.length > 0) {
      return false
    }
    // filter by level
    if (filterLevel.value.length > 0 && !filterLevel.value.includes(highestLvl)) {
      return false
    }
    return true
  })
}

// filter & sort for reflec entries
// reflec entry sort

function clearSort() {
  sortBy.value = 'date'
  sortOrder.value = 'desc'
  sortDdOpen.value = false
}

onClickOutside(sortRef, function () {
  sortDdOpen.value = false
})

const hasActiveReflecFilter = computed(function () {
  return reflecFilterYear.value.length > 0 || reflecFilterLevel.value.length > 0
})

function clearReflecFilter() {
  reflecFilterYear.value = []
  reflecFilterLevel.value = []
  reflecFilterDdOpen.value = false
}

onClickOutside(reflecFilterRef, function () {
  reflecFilterDdOpen.value = false
})

const processedReflec = computed(() => {
  if (!selectedCompt.value) { return [] }
  let list = publishedOnly(selectedCompt.value)

  // filter by year
  if (reflecFilterYear.value.length > 0) {
    list = list.filter(r => reflecFilterYear.value.includes(r.associated_year))
  }

  // filter by levels
  if (reflecFilterLevel.value.length > 0) {
    list = list.filter(r => {
      // Use optional chaining to handle both object or string formats
      const currentLvl = r.entry_level?.competency_level;
      return reflecFilterLevel.value.includes(currentLvl);
    });
  }

  // sort
  list = list.sort((a, b) => {
    if (sortBy.value === 'name') {
      if (sortOrder.value === 'asc') {
        return (b.experience_title || '').localeCompare(a.experience_title || '')       
      } else {
        return (a.experience_title || '').localeCompare(b.experience_title || '')
      }
    }

    // Convert date into a number
    const da = new Date(a.updated_at);
    const db = new Date(b.updated_at);

    if (sortOrder.value === 'asc') {
      return da - db
    } else {
      return db - da
    }
  })
  return list
})

function openDetail(compt, catLabel) {
  // reset reflection filters and sort when opening new compt
  clearReflecFilter()
  clearSort()

  selectedCompt.value = {
    id: compt.id,
    displayId: compt.displayId,
    indicator_name: compt.indicator_name,
    category: catLabel,
    reflec: compt.reflec,
    description: compt.desc,
    attainmentIndicators: compt.attainmentIndicators || [] 
  }
}

function closeDetail() {
  selectedCompt.value = null
}

//detailed reflection view
const viewReflec = ref({ 
  show: false, 
  reflec: null, 
  compt: null, 
  index: null 
})

function openReflec(reflec, index) {
  viewReflec.value = {
    show: true,
    reflec,
    compt: selectedCompt.value,
    index
  }
}

function closeReflec() {
  viewReflec.value.show = false
}

function onSaveReflec() {
  viewReflec.value.show = false 
  emit('refresh')
}

// add reflection popup 
const addModal = ref({ 
  show: false, 
  comptId: '' 
})

function openAdd(comptId = '') {
  addModal.value = { 
    show: true, 
    comptId 
  }
}

// Refresh the data when an entry is added
function onAddReflec() {
  emit('refresh')
  addModal.value.show = false
}
</script>

<style scoped>
.curr-compt {
  max-width: 100%;
}

.compt-title {
  font-family: 'Martel', serif;
  font-size: clamp(1.3rem, 4vw, 2rem);
  color: #2b2b2bc5;
  font-weight: lighter;
  margin-bottom: 2rem;
  text-align: center;
}

.title-row {
  position: relative;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  margin-bottom: 0.5rem;
  gap: 0.5rem;
}

.title-row>.filter-wrap {
  position: static;
}

.btn-title-wrap {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 2rem;
  padding: 0 6rem;
}

.btn-title-wrap>.btn {
  position: absolute;
  left: 0;
}

.detail-stats {
  display: flex;
  flex-direction: column;
  gap: 0;
  margin-bottom: 0.5rem;
}

.entries-header {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

.ctrl-actions {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-wrap: wrap;
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
  min-width: min(16rem, 90vw);
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

.category {
  cursor: pointer;
}

.triangle {
  width: 0.8rem;
  height: 0.8rem;
  transition: transform 0.2s ease;
}

.triangle.open {
  transform: rotate(90deg);
}

.c-label {
  font-family: 'Martian Mono', monospace;
  font-size: clamp(1rem, 3vw, 1.4rem);
  font-weight: 100;
}

.compt-card {
  width: 100%;
  min-height: 8.125rem;
  border-radius: 1.5rem;
  border: 1px solid #bababa;
  cursor: pointer;
  transition: box-shadow 0.2s ease;
}

.compt-card:hover {
  box-shadow: 0 0.25rem 0.75rem #e5e5e5;
}

.reflec-card {
  width: 100% !important;
  transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.reflec-card:hover {
  box-shadow: 0 0.25rem 0.75rem #e5e5e5;
  transform: translateY(-2px);
}

.compt-label {
  font-family: 'Maven Pro', sans-serif;
  font-size: clamp(0.85rem, 2.5vw, 1.1rem);
  font-weight: 100;
  color: #878787;
}

.txt {
  font-family: 'Maven Pro', sans-serif;
  background-color: #e7e7e7;
  border-radius: 50%;
  font-size: smaller;
  padding: 0.05rem 0.4rem;
}

.txt-lvl {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.9rem;
}

.reflecs-blue {
  background: #e2f8ff;
  color: #1a6a86;
  padding: 0.1rem 0.8rem;
  font-size: 0.85rem;
}

.reflecs-red {
  background: #ffe3e3;
  color: #b03030;
  padding: 0.1rem 0.8rem;
  font-size: 0.85rem;
}

.reflecs {
  background: #e6e6e6;
  padding: 0.1rem 0.8rem;
  font-size: 0.85rem;
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

.entry-title {
  font-family: 'Martian Mono', monospace;
  font-weight: 200;
  font-size: clamp(1.1rem, 3vw, 1.5rem);
}

.detail {
  font-family: 'Maven Pro', sans-serif;
  color: #222222;
}

.detail-txt {
  color: #444444;
  font-size: 0.95rem;
}

.btn-filter, .btn-filter-sm {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  font-size: 0.9rem;
  background: #e6e6e6;
}

.btn-filter:hover,.btn-filter-sm:hover {
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

@media (min-width: 768px) {
  .title-row > .filter-wrap {
    position: absolute;
    right: 0;
    top: 0;
  }

  .detail-stats {
    flex-direction: row;
    justify-content: space-between;
    max-width: 90%;
  }

  .btn-filter {
    font-size: 1rem;
  }

  .btn-add {
    font-size: 1rem;
  }
}

@media (min-width: 576px) {
  .entries-header {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
  }
}
</style>