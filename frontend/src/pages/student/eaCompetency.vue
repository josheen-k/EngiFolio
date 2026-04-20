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