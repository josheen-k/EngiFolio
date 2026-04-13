<template>
  <Navbar/>

  <div>
    <aside>
      <nav>
        <ul>
          <li>CURRENT</li>
          <li>DRAFTS</li>
          <li>FEEDBACK</li>
          <li>DISCONTINUED</li>
        </ul>
      </nav>
    </aside>

    <main>
      <h1>Current Competencies</h1>

      <section v-for="g in groups" :key="g.group_id">
        <h2>{{ g.group_name }}</h2><span><b>6</b></span>

        <div v-for="i in g.indicators" :key="i.indicator_id">
          <h3>{{ i.display_id }}</h3>
        </div>
      </section>
    </main>
  </div>

  <Footer/>
</template>

<script setup lang="ts">
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';

// dummy data
const groups = [
  {
    group_id: 1,
    group_name: "Knowledge and Skill Base",
    status: "current",

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
    status: "current",
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
    status: "current",
    indicators: [
      {
        indicator_id: 31,
        display_id: "3.1",
        description: "Ethical conduct and professional accountability.",
        entries: []
      }
    ]
  }
]

</script>