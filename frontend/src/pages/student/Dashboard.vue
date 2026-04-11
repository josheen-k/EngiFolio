<template>
  <Navbar/>

  <main class="container-xl py-4 px-4">
    <div class="row g-4 mb-4">
      <div class="col-12 col-md-6">
        <h2>Your Stats</h2>
      </div>
      <div class="col-12 col-md-6">
        <h2>Attainment Level Distribution</h2>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-12 col-lg-8">
        <h2>Need More Focus On</h2>

        <table class="table">
          <tbody>
            <tr v-for="item in focusItems" :key="item.id">
              <td>{{ item.id }}</td>
              <td>{{ item.description }}</td>
              <td>{{ item.entries }}</td>
              <td>{{ item.level }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-12 col-lg-4">
        <h2>Quick Links</h2>
      </div>
      <div class="col-12 col-lg-4">
        <h2>Recent Activity</h2>
        <ul>
          <li v-for="act in recentAct" :key="act">
            {{ act }}
          </li>
        </ul>
      </div>
    </div>
  </main>

  <Footer/>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'

const focusItems = [
  {
    id: '1.2',
    description: 'Comprehensive, theory based understanding of the underpinning natural and physical sciences and the engineering fundamentals applicable to the engineering discipline.',
    entries: 0,
    level: 'Emerging'
  },

  {
    id: '1.2',
    description: 'Comprehensive, theory based understanding of the underpinning natural and physical sciences and the engineering fundamentals applicable to the engineering discipline.',
    entries: 0,
    level: 'Emerging'
  },

  {
    id: '1.2',
    description: 'Comprehensive, theory based understanding of the underpinning natural and physical sciences and the engineering fundamentals applicable to the engineering discipline.',
    entries: 0,
    level: 'Emerging'
  },

  {
    id: '1.2',
    description: 'Comprehensive, theory based understanding of the underpinning natural and physical sciences and the engineering fundamentals applicable to the engineering discipline.',
    entries: 0,
    level: 'Emerging'
  },

  {
    id: '1.2',
    description: 'Comprehensive, theory based understanding of the underpinning natural and physical sciences and the engineering fundamentals applicable to the engineering discipline.',
    entries: 0,
    level: 'Emerging'
  },
]

const recentAct = [
  '2 Apr 2026: Added reflection for competency 1.2',
  '1 Apr 2026: Updated goal: Do 3 mini projects',
  '25 Mar 2026: Deleted goal: Improve teamwork',
  '22 Mar 2026: Updated profile'
]
</script>
