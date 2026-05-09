<template>
  <Navbar/>

  <div class="d-flex p-4 side ms-3">
    <aside class="d-flex gap-4 flex-column pt-5 sidebar-wrap">
      <div class="d-flex align-items-center gap-3 px-3 py-2 sidebar"
      :class="{'sidebar-on': currTab===t}" v-for="t in tabs" :key="t"  @click="currTab = t">
        <span class="dot rounded-circle" :class="currTab===t ? 'dot-on' : ''"></span>{{ t }}
      </div>
    </aside>

    <main class="mt-5 main-area">
      <!-- Used to pass data to the other components -->
      <component :is="currComponent" :categories="categories" :levelOptions="levelOptions" @refresh="handleRefresh"
/>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import Navbar from '@/components/Navbar.vue';
import CurrentCompetency from '@/components/CurrentCompetency.vue';
import DraftReflections from '@/components/DraftReflections.vue';
import FeedbackReflections from '@/components/FeedbackReflections.vue';
import DiscontinuedCompetency from '@/components/DiscontinuedCompetency.vue';
import api from "@/services/api";

const route = useRoute()

// Stores data to be passed to components
const categories  = ref([])
const levelOptions = ref([])

// different tabs in side pannel
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
      compt: group.indicators.map(ind => ({
        id: ind.indicator_id,
        displayId: ind.display_id,
        desc: ind.description,
        reflec: ind.entries || [],
        discontinuedDate: ind.discontinued_date 
      }))
    }));

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
const handleRefresh = () => {
  loadData();
};

// Load the data when the page is loaded
onMounted(loadData);
</script>

<style scoped>
.side {
  min-height: 100vh;
  gap: 4rem;
}

.sidebar-wrap {
  position: sticky;
  top: 30%;
  left: 5%;
  width: 20%;
  height: fit-content;
}

.main-area {
  flex: 1;
  min-width: 0;
}

.sidebar{
  font-family: 'Maven Pro', sans-serif;
  font-size: 1.2rem;
  border-radius: 1.5rem;
  cursor: pointer;
  width: 70%;
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
</style>