<template>
  <div class="curr-compt">
    <!--compt detailed view-->
    <div v-if="selectedCompt" class="detail">

      <div class="btn-title-wrap">
        <button class="btn btn-filter" @click="closeDetail">Go back</button>
        <h2 class="compt-title mb-0">Competency {{ selectedCompt.id }}</h2>
      </div>

      <p class="fs-5">Category: <em>{{ selectedCompt.category }}</em></p>
      <p class="fs-5 mb-1">Description:</p>
      <p class="detail-txt">{{ selectedCompt.description }}</p>

      <div class="d-flex justify-content-between detail-stats">
        <p class="fs-5">Total reflection entries you added: <em>{{ publishedOnly(selectedCompt).length }}</em></p>
        <p class="fs-5">Highest attainment level you reflected: <em>{{ getLvl(selectedCompt) }}</em></p>
      </div>

      <div class="d-flex justify-content-between align-items-center my-3">
        <h3 class="entry-title">Your Entries</h3>
        <div class="d-flex gap-2 align-items-center">

          <!-- sort control -->
          <div class="filter-wrap" ref="sortRef">
            <button class="btn btn-filter" @click="sortDdOpen = !sortDdOpen">Sort</button>

            <div v-if="sortDdOpen" class="filter-dd">
              <p class="filter-heading">Sort by</p>
              <div class="d-flex flex-column gap-1 mb-3">
                <label class="filter-option" v-for="opt in sortByOptions" :key="opt.value">
                  <input type="radio" :value="opt.value" v-model="sortBy" class="filter-radio"/>{{ opt.label }}
                </label>
              </div>

              <p class="filter-heading">Order</p>
              <div class="d-flex flex-column gap-1">
                <label class="filter-option">
                  <input type="radio" value="asc" v-model="sortOrder" class="filter-radio"/>Ascending
                </label>
                <label class="filter-option">
                  <input type="radio" value="desc" v-model="sortOrder" class="filter-radio"/>Descending
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
                  <input type="checkbox" :value="opt.value" v-model="reflecFilterLevel" class="filter-radio"/>{{ opt.label }}
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
        <div class="col-12 col-sm-6 col-lg-3" v-for="reflec in processedReflec" :key="reflec._rawIndex">
          <div class="card compt-card p-3 h-70 reflec-card"
            @click="openReflec(reflec, reflec._rawIndex)">
            <p class="compt-label mb-2">{{ reflec.title }}</p>
            <div class="d-flex align-items-center gap-2 mb-2">
              <span class="reflecs rounded-pill">{{ reflec.year === 0 ? 'PRIOR' : 'YEAR ' + reflec.year }}</span>
              <span class="txt-lvl">{{ reflec.level }}</span>
            </div>
            <p class="txt-lvl mb-0">Last updated: {{ reflec.date }}</p>
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
                <input type="checkbox" :value="opt.value" v-model="filterLevel" class="filter-radio"/>{{ opt.label }}
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
          <div v-if="filteredCompts(c).length" class="d-flex flex-wrap gap-3">
            <div class="compt-wrap" v-for="compt in filteredCompts(c)" :key="compt.id">
              <div class="card compt-card p-3" @click="openDetail(compt, c.label)">
                <h5 class="compt-label mb-2">Competency {{ compt.id }}</h5>

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

  <ViewReflection :show="viewReflec.show" :reflec="viewReflec.reflec" :compt="viewReflec.compt" :index="viewReflec.index"
  @close="closeReflec" @save="onSaveReflec" @delete="onDeleteReflec"/>

  <AddReflection :show="addModal.show" :initialComptId="addModal.comptId" 
  @close="addModal.show = false" @add="onAddReflec"/>
</template>

<script setup>
import { onMounted, computed, ref } from 'vue'
import ViewReflection from '@/components/ViewReflection.vue'
import AddReflection from '@/components/AddReflection.vue'
import { currentCategories, getLvl, publishedReflec } from '@/useCompetencies.js'
import { onClickOutside } from '@vueuse/core';

const selectedCompt = ref(null);
const categories = currentCategories // use shared data

// filter options for competencies
const filterRef = ref(null)
const ddOpen = ref(false)
const filterReflec = ref('all')
const filterLevel = ref([])

const reflecOption = [
  { value: 'all', label: 'All competencies' },
  { value: 'has-reflections', label: 'Has at least one reflection' },
  { value: 'no-reflections', label: 'No reflections yet' }
]

const levelOptions = [
  { value: 'Not Started', label: 'Not Started' },
  { value: 'Emerging', label: 'Emerging' },
  { value: 'Developing', label: 'Developing' },
  { value: 'Competent', label: 'Competent' },
  { value: 'Proficient', label: 'Proficient' }
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

function filteredCompts(category) {
  return category.compt.filter(function (compt) {
    const published = publishedOnly(compt)
    const highestLvl = getLvl(compt)

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
const sortRef = ref(null)
const sortDdOpen = ref(false)
const sortBy = ref('date')
const sortOrder = ref('desc')  // 'asc'  | 'desc'

const sortByOptions = [
  { value: 'date', label: 'Date' },
  { value: 'name', label: 'Title (A–Z)' }
]

function clearSort() {
  sortBy.value = 'date'
  sortOrder.value = 'desc'
  sortDdOpen.value = false
}

onClickOutside(sortRef, function () {
  sortDdOpen.value = false
})

//reflec entry filter
const reflecFilterRef = ref(null)
const reflecFilterDdOpen = ref(false)
const reflecFilterYear = ref([])
const reflecFilterLevel = ref([])

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

function clearReflecFilter() {
  reflecFilterYear.value = []
  reflecFilterLevel.value = []
  reflecFilterDdOpen.value = false
}

onClickOutside(reflecFilterRef, function () {
  reflecFilterDdOpen.value = false
})

const processedReflec = computed(() => {
  if (!selectedCompt.value) {
    return []
  }
  let list = publishedOnly(selectedCompt.value)

  // filter by year
  if (reflecFilterYear.value.length > 0) {
    list = list.filter(r => reflecFilterYear.value.includes(r.year))
  }

  // filter by level
  if (reflecFilterLevel.value.length > 0) {
    list = list.filter(r => reflecFilterLevel.value.includes(r.level))
  }

  // sort
  list = list.sort((a, b) => {
    if (sortBy.value === 'name') {
      if (sortOrder.value === 'asc') {
        return (a.title || '').localeCompare(b.title || '')
      } else {
        return (b.title || '').localeCompare(a.title || '')
      }
    }

    //date parsing dd/mm/yyyy format
    function parseDate(str) {
      if (!str) {
        return 0
      }
      const [day, month, year] = str.split('/')
      return new Date(year, month-1, day)
    }
    const da = parseDate(a.date)
    const db = parseDate(b.date)

    if (sortOrder.value === 'asc') {
      return da-db
    } else {
      return db-da
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
    category: catLabel,
    reflec: compt.reflec,
    description: compt.desc,
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

function onSaveReflec({ index, updated }) {
  if (selectedCompt.value) {
    Object.assign(selectedCompt.value.reflec[index], updated)
  }
}

function onDeleteReflec(index) {
  if (selectedCompt.value) {
    selectedCompt.value.reflec.splice(index, 1)
  }
  viewReflec.value.show = false
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

function onAddReflec({ comptId, reflec }) {
  for (const cat of categories.value) {
    const found = cat.compt.find(function (c) {
      return c.id === comptId
    })
    if (found) {
      found.reflec.push(reflec)
      break
    }
  }
}
</script>

<style scoped>
.curr-compt {
  max-width: 90%;
}

.compt-title {
  font-family: 'Martel', serif;
  font-size: 2rem;
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
}

.filter-wrap {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 0.5rem;
}

.title-row>.filter-wrap {
  position: absolute;
  right: 0;
  top: 0;
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
  font-size: 1.4rem;
  font-weight: 100;
}

.compt-wrap {
  flex: 0 0 12.5rem;
}

.compt-card {
  width: 13.75rem;
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
  width: 100%;
  transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.reflec-card:hover {
  box-shadow: 0 0.25rem 0.75rem #e5e5e5;
  transform: translateY(-2px);
}

.compt-label {
  font-family: 'Maven Pro', sans-serif;
  font-size: 1.3rem;
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
}

.reflecs-blue {
  background: #e2f8ff;
  color: #1a6a86;
  padding: 0.1rem 0.8rem;
}

.reflecs-red {
  background: #ffe3e3;
  color: #b03030;
  padding: 0.1rem 0.8rem;
}

.reflecs {
  background: #e6e6e6;
  padding: 0.1rem 0.8rem;
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
  font-size: 1.5rem;
}

.detail {
  font-family: 'Maven Pro', sans-serif;
  color: #222222;
}

.detail-txt {
  color: #444444;
}

.detail-stats {
  max-width: 90%;
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
