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
      <h1>{{currTitle}}</h1>

      <section v-for="g in filteredGrps" :key="g.group_id">
        <h2>{{ g.group_name }}</h2><span><b>6</b></span>

        <div v-for="i in g.indicators" :key="i.indicator_id">
          <h3>{{ i.display_id }}</h3>
        </div>
      </section>
    </main>
  </div>

  <Footer/>
</template>

<script setup>
import { ref, computed } from 'vue'
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';

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

// dummy data
const groups = [
  {
    group_id: 1,
    group_name: "Knowledge and Skill Base",
    status: "CURRENT",

    indicators: [
      {
        indicator_id: 11,
        display_id: "1.1",
        description: "Comprehensive, theory based understanding of the underpinning natural and physical sciences and the engineering fundamentals applicable to the engineering discipline. ",
        entries: [
          {
            entry_id: 1,
            title: "Lab project on structural analysis",
            level: "Developing",
            year: 2,
            updated_at: "2026-04-12",
            evidence: [
              { type: "url", value: "https://myLab.com/report" },
              { type: "file", value: "LabReport.pdf" }
            ]
          }
        ]
      },
      {
        indicator_id: 12,
        display_id: "1.2",
        description: "Conceptual understanding of the mathematics, numerical analysis, statistics, and computer and information sciences which underpin the engineering discipline.",
        entries: []
      }
    ]
  },

  {
    group_id: 2,
    group_name: "Engineering Application Ability",
    status: "CURRENT",
    indicators: [
      {
        indicator_id: 21,
        display_id: "2.1",
        description: "Application of established engineering methods to complex engineering problem solving.",
        entries: []
      }
    ]
  },

  {
    group_id: 3,
    group_name: "Professional and Personal Attributes",
    status: "CURRENT",
    indicators: [
      {
        indicator_id: 31,
        display_id: "3.1",
        description: "Ethical conduct and professional accountability.",
        entries: []
      }
    ]
  }
];

const filteredGrps = computed(() => {
  return groups.filter(g=>g.status === currTab.value)
});
</script>

<style scoped>
.sidebar{
  font-family: 'Maven Pro', sans-serif;
  font-size: 1.2rem;
  border-radius: 1.5rem;
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