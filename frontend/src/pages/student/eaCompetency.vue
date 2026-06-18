<template>
  <Navbar />

  <div class="page-wrap p-3">
    <aside class="sidebar-wrap">
      <div class="d-flex flex-row flex-md-column gap-2 gap-md-4 pt-0 pt-md-5">
        <div class="d-flex align-items-center gap-2 gap-md-3 px-2 px-md-3 py-2 sidebar"
        :class="{'sidebar-on': currTab===t}" v-for="t in tabs" :key="t"  @click="switchTab(t)">
          <span class="dot rounded-circle d-none d-md-inline-block" :class="currTab===t ? 'dot-on' : ''"></span>{{ t.toUpperCase() }}
        </div>
      </div>
    </aside>

    <main class="mt-5 main-area">
      <!-- Used to pass data to the other components. Only pass initialIndicatorId to current -->
      <component :is="currComponent" :categories="categories" :levelOptions="levelOptions"
      v-bind="currTab === 'current' ? { initialIndicatorId: route.query.indicator } : {}"
      @refresh="loadData"
/>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue' // Added watch here
import { useRoute, useRouter } from 'vue-router'
import Navbar from '@/components/Navbar.vue';
import CurrentCompetency from '@/components/CurrentCompetency.vue';
import DraftReflections from '@/components/DraftReflections.vue';
import FeedbackReflections from '@/components/FeedbackReflections.vue';
import DiscontinuedCompetency from '@/components/DiscontinuedCompetency.vue';
import api from "@/services/api";

// Variables for getting and changing URL information
const route = useRoute()
const router = useRouter()

// Holds information about the student competencies and the level options that the student has
const categories = ref([])
const levelOptions = ref([])

// Read the ?tab= parameter to render the tab buttons
const currTab = computed(() => route.query.tab || 'current');
const tabs = ['current', 'drafts', 'feedback', 'discontinued']

// Map current tab to the component
const currComponent = computed(() => {
  switch (currTab.value) {
    case 'current': return CurrentCompetency
    case 'drafts': return DraftReflections
    case 'feedback': return FeedbackReflections
    case 'discontinued': return DiscontinuedCompetency
    default: return CurrentCompetency
  }
})

// Used to add parameter for tab
function switchTab(tab) {
  router.replace({
    query: {
      ...route.query,
      tab: tab,
      indicator: undefined
    }
  });
}

// Load the data required for the components
const loadData = async () => {
  try {
    // Parallel calls to retrieve required competency data
    const [compRes, levelRes] = await Promise.all([
      // Load competency groups with all competency indicators and competency entries for the logged in student
      api.get(`/competency-groups-student/${route.params.id}`),
      // Get competency levels that can be assigned by a student
      api.get('/competency-levels'),
    ])

    // Pass categories with competency details with student reflections and feedback
    categories.value = compRes.data.map(group => ({
      key: group.display_id,
      label: group.group_name,
      open: true,
      compt: group.indicators.map(ind => ({
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
      }))
    }));

    // Populate the level options table with the level ids and labels
    levelOptions.value =
      levelRes.data.map(l => ({
        value: l.entry_level_id,
        label: l.competency_level
      }));
  } catch (error) {
    console.error('Error when loading competencies and levels', error)
  }
};

// Directs the user to a certain competency indicator if the indicator query is present
// For when the user selects a competency indicator from the dashboard
const handleIndicatorParam = () => {
  // Get the indicator id from the url
  const indicatorId = route.query.indicator;

  if (indicatorId && currTab.value !== 'current') {
    for (const cat of categories.value) {
      const match = cat.compt.find(c => Number(c.id) === Number(indicatorId));
      if (match) {
        router.replace({ query: { ...route.query, tab: 'CURRENT' } });
        break;
      }
    }
  }
};

watch(() => route.query.indicator, () => {
  handleIndicatorParam();
});

// Load data before redirecting the user
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

.sidebar {
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