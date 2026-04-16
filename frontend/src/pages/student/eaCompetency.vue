<template>
  <Navbar/>

  <div class="d-flex gap-4 p-4">
    <aside class="d-flex gap-2 flex-column pt-5">
      <div class="d-flex align-items-center gap-2 px-3 py-2 sidebar"
      :class="{'sidebar-on': currTab===t}" v-for="t in tabs" :key="t"  @click="currTab = t">
        <span class="dot rounded-circle" :class="currTab===t ? 'dot-on' : ''"></span>{{ t }}
      </div>
    </aside>

    <main>
      <h1 class="comp-title">{{currTitle}}</h1>

      <component :is="currComponent"/>
    </main>
  </div>

  <Footer/>
</template>

<script setup>
import { ref, computed } from 'vue'
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
import CurrentCompetency from '@/components/CurrentCompetency.vue';
import DraftReflections from '@/components/DraftReflections.vue';
import FeedbackReflections from '@/components/FeedbackReflections.vue';
import DiscontinuedCompetency from '@/components/DiscontinuedCompetency.vue';

// different tabs in side pannel
const currTab = ref('CURRENT');
const tabs = ['CURRENT', 'DRAFTS', 'FEEDBACK', 'DISCONTINUED'];

// dynamic titles based on current tab
const currTitle = computed(()=> {
  switch (currTab.value) {
    case 'CURRENT':
      return 'Current Competencies'
    case 'DRAFTS':
      return 'Draft Reflections'
    case 'FEEDBACK':
      return 'Feedback Recieved'
    case 'DISCONTINUED':
      return 'Discontinued Competencies'
    default:
      return 'Competencies'
  }
});
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
</script>

<style scoped>
.sidebar{
  font-family: 'Maven Pro', sans-serif;
  font-size: 1.2rem;
  border-radius: 1.5rem;
  cursor: pointer;
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

.comp-title {
  font-family: 'Martel', serif;
  font-size: 2rem;
  color: #2b2b2bc5;
  font-weight: lighter;
  margin-bottom: 2rem;
}
</style>