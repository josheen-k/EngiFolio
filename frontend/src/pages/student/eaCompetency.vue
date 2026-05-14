<template>
  <Navbar/>

  <div class="page-wrap p-3">
    <aside class="sidebar-wrap">
      <div class="d-flex flex-row flex-md-column gap-2 gap-md-4 pt-0 pt-md-5">
        <div class="d-flex align-items-center gap-2 gap-md-3 px-2 px-md-3 py-2 sidebar"
        :class="{'sidebar-on': currTab===t}" v-for="t in tabs" :key="t"  @click="switchTab(t)">
          <span class="dot rounded-circle d-none d-md-inline-block" :class="currTab===t ? 'dot-on' : ''"></span>{{ t }}
        </div>
      </div>
    </aside>

    <main class="mt-5 main-area">
      <!-- Used to pass data to the other components -->
      <component :is="currComponent" :categories="categories" :levelOptions="levelOptions" :initialIndicatorId="route.query.indicator" @refresh="loadData"
/>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Navbar from '@/components/Navbar.vue';
import CurrentCompetency from '@/components/CurrentCompetency.vue';
import DraftReflections from '@/components/DraftReflections.vue';
import FeedbackReflections from '@/components/FeedbackReflections.vue';
import DiscontinuedCompetency from '@/components/DiscontinuedCompetency.vue';
import api from "@/services/api";

const route = useRoute()
const router = useRouter()

// Stores data to be passed to components
const categories  = ref([])
const levelOptions = ref([])

// different tabs in side panel
const currTab = ref('CURRENT');
const tabs = ['CURRENT', 'DRAFTS', 'FEEDBACK', 'DISCONTINUED'];

// render components based on current tab
const currComponent = computed(()=> {
  switch (currTab.value) {
    case 'CURRENT':
      return CurrentCompetency
    case 'DRAFTS':
      return DraftReflections
    case 'FEEDBACK':
      return FeedbackReflections
    case 'DISCONTINUED':
      return DiscontinuedCompetency
  }
});

// Set value of current tab
function switchTab(tab) {
  currTab.value = tab
  if (route.query.indicator) {
    // Clear url query
    router.replace({ query: {} })
  }
}

const loadData = async () => { 
  try {
    // Make calls to backend for competencies and levels
    const [compRes, levelRes] = await Promise.all([
      api.get(`/competency-groups-student/${route.params.id}`),
      api.get(`/competency-levels`)
    ]);

    // Map competency data to the values used by the other components
    categories.value = compRes.data.map(group => ({
    key: group.display_id,
    label: group.group_name,
    open: true,
    compt: group.indicators.map(ind => {
      return {
        id: ind.indicator_id,
        displayId: ind.display_id,
        indicator_name: ind.indicator_name,
        desc: ind.description,
        discontinuedDate: ind.discontinued_date,
        attainmentIndicators: ind.attainment_indicators || [],
        reflec: ind.entries.map(entry => ({
          ...entry,
          feedback: entry.competency_feedback || [],
          evidence: entry.competency_evidence || []
        }))
      }
    })
  }))

    // Map entry level data to the value and label used by the other components
    levelOptions.value = [
      { value: null, label: 'Not Started' },
      ...levelRes.data.map(l => ({ 
        value: l.entry_level_id,  
        label: l.competency_level 
      }))
    ];
  } catch (error) {
    console.error("Error when loading competencies and levels", error);
  }
};

// Reload data
const handleIndicatorParam = () => {
  const indicatorId = route.query.indicator
  if (indicatorId && categories.value.length) {
      // Check to see if there is a matching indicator id
    for (const cat of categories.value) {
      const match = cat.compt.find(c => Number(c.id) === Number(indicatorId))
      if (match) {
        // Set tab to current and stop searching
        currTab.value = 'CURRENT'
        break
      }
    }
  }
}

// update onMounted to check for the param after data loads
onMounted(async () => {
  await loadData()
  handleIndicatorParam()
})
</script>

<style scoped>
.page-wrap {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  gap: 0.5rem;
}

.sidebar-wrap {
  width: 100%;
}

.sidebar{
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.9rem;
  border-radius: 1.5rem;
  cursor: pointer;
}

.main-area {
  flex: 1;
  min-width: 0;
}

.sidebar-on {
  background: #f3f3f3;
  color: #222222;
}

.dot {
  width: 0.7rem;
  height: 0.7rem;
  background: #e0e0e0;
}

.dot-on {
  background: #88c2d2;
}

@media (min-width: 768px) {
  .page-wrap {
    flex-direction: row;
    gap: 8rem;
  }

  .sidebar-wrap {
    position: sticky;
    top: 30%;
    width: 20%;
    left: 7%;
    min-width: 10rem;
    height: fit-content;
  }

  .sidebar {
    font-size: 1.2rem;
    width: 70%;
  }

  .main-area {
    flex: 0 0 60%;
  }
}
</style>