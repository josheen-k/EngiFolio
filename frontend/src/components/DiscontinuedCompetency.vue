<template>
  <div class="curr-compt">
    <div v-if="selectedCompt" class="detail">

      <div class="btn-title-wrap">
        <button class="btn btn-filter" @click="closeDetail">Go back</button>
        <h2 class="compt-title mb-0">Competency {{ selectedCompt.id }}</h2>
      </div>

      <p class="fs-5">Category: <em>{{ selectedCompt.category }}</em></p>
      <p class="fs-5 mb-1">Description:</p>
      <p class="detail-txt">{{ selectedCompt.description }}</p>

      <p class="fs-5">Indicators:</p>
      <ul class="ps-3">
        <li class="detail-txt" v-for="(ind, i) in selectedCompt.indicators" :key="i">{{ ind }}</li>
      </ul>

      <div class="d-flex justify-content-between detail-stats">
        <p class="fs-5">Total reflection entries you added: <em>{{ selectedCompt.reflec.length }}</em></p>
        <p class="fs-5">Highest attainment level you reflected: <em>{{ getLvl(selectedCompt) }}</em></p>
      </div>

      <div class="d-flex justify-content-between align-items-center my-3">
        <h3 class="entry-title">Your Entries</h3>
        <div class="d-flex gap-3">
          <button type="button" class="btn btn-filter">Add filter</button>
        </div>
      </div>

      <div v-if="selectedCompt.reflec.length" class="row g-3">
        <div class="col-12 col-sm-6 col-lg-3" v-for="(reflec, i) in selectedCompt.reflec" :key="i">
          <div class="card compt-card p-3 h-70 reflec-card" @click="openReflec(reflec, i)">
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

    <div v-else>
      <h1 class="compt-title">Discontinued Competencies</h1>

      <div class="mb-4" v-for="c in filteredCategories" :key="c.key">
        <div class="d-flex align-items-center gap-2 mb-3 category" @click="c.open = !c.open">
          <img class="triangle" :class="{ open: c.open }" src="@/assets/triangle.png"/>
          <span class="c-label">{{ c.label }}</span>
          <span class="txt">{{ c.compt.length }}</span>
        </div>

        <div v-if="c.open" class="d-flex flex-wrap gap-3">
          <div class="compt-wrap" v-for="compt in c.compt" :key="compt.id">
            <div class="card compt-card p-3" @click="openDetail(compt, c.label)">
              <h5 class="compt-label mb-2">Competency {{ compt.id }}</h5>
              <div class="d-flex align-items-center justify-content-start mb-2 gap-2">
                <span class="rounded-pill px-3 py-1" :class="publishedOnly(compt).length ? 'reflecs-blue' : 'reflecs-red'">
                  {{ publishedOnly(compt).length }} reflection{{ publishedOnly(compt).length !== 1 ? 's' : '' }}
                </span>
              </div>
              <p class="txt-lvl mb-0">Highest level: {{ getLvl(compt) }}</p>
            </div>
          </div>
        </div>

        <p v-if="c.open && c.compt.length === 0" class="text-secondary ms-2">
          No discontinued competencies in this category.
        </p>
      </div>
    </div>

  </div>

  <ViewReflection 
    v-if="viewReflec.show"
    :show="viewReflec.show" 
    :reflec="viewReflec.reflec" 
    :compt="viewReflec.compt" 
    :index="viewReflec.index"
    @close="closeReflec" 
    @save="onSaveReflec" 
    @delete="onDeleteReflec"
  />
</template>

<script setup>
import { ref, computed  } from 'vue'
import ViewReflection from '@/components/ViewReflection.vue'
import { discontinuedCategories, getLvl } from '@/useCompetencies.js'

// Sent from main page
const props = defineProps({
  categories: { type: Array, required: true },
  levelOptions: { type: Array, required: true }
});

const emit = defineEmits(['refresh']);
const selectedCompt = ref(null)

function openDetail(compt, catLabel) {
  selectedCompt.value = {
    id: compt.id,
    category: catLabel,
    reflec: compt.reflec,
    description: compt.desc,
    indicators: compt.indicators
  }
}

function closeDetail() {
  selectedCompt.value = null
}

const viewReflec = ref({
  show: false,
  reflec: null,
  compt: null,
  index: null
})

// Filter for entries where discontinued_date exists
const filteredCategories = computed(() => {
  return props.categories.map(cat => ({
    ...cat,
    compt: cat.compt.filter(ind => ind.discontinued_date && ind.discontinued_date !== '')
  })).filter(cat => cat.compt.length > 0);
});

function openReflec(reflec) {
  const originalIndex = selectedCompt.value.reflec.findIndex(r => r.entry_id === reflec.entry_id);
  viewReflec.value = {
    show: true,
    reflec,
    compt: selectedCompt.value,
    index: originalIndex
  }
}

function closeReflec() {
  viewReflec.value.show = false
}

function onSaveReflec({ index, updated }) {
  emit('refresh');
}

function onDeleteReflec(index) {
  if (selectedCompt.value) {
    selectedCompt.value.reflec.splice(index, 1)
  }
  viewReflec.value.show = false
}

function publishedOnly(compt) { return publishedReflec(compt); }

const formatDate = (dateString) => {
  const date = new Date(dateString);
  
  return date.toLocaleDateString('en-AU') + ', ' + 
    date.toLocaleTimeString('en-AU', { 
      hour: 'numeric', 
      minute: '2-digit', 
    }).toLowerCase();
};
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

.btn-title-wrap {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 3rem;
}

.btn-title-wrap > .btn {
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

.btn-filter {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  font-size: 1rem;
  background: #e6e6e6;
}

.btn-filter:hover {
  background: #666666;
  color: #ffffff;
}
</style>