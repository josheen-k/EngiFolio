<template>
  <div class="page">
  <Navbar />

  <!-- <main class="container-xl py-4 px-4 px-md-5 goals-main"> -->
      <section class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
          <h1 class="page-title mb-2">Provide student feedback</h1>
          <p class="page-subtitle mb-0">Provide feedback for students' competency development and goals.</p>
        </div>
      </section>

      <!-- category sections -->
      <!-- <div class="mb-4" v-for="c in students" :key="c.key">
        <div class="d-flex align-items-center gap-2 mb-3 category" @click="c.open = !c.open">
          <img class="triangle" :class="{ open: c.open }" src="@/assets/triangle.png"/>
          <span class="c-label">{{ c.label }}</span>
          <span class="txt">{{ filteredCompts(c).length }}</span>
        </div>
      </div> -->

      <!-- TABLE -->
      <div class="table-box">
        <table>
          <thead>
            <tr>
              <th>Student name</th>
              <th>Company</th>
              <th>Progress Notes</th>
              <th>Date Met</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>aaa</td>
              <td>bbb</td>
              <td>ccc</td>
              <td>ddd</td>
            </tr>
            <tr>
              <td>aaa</td>
              <td>bbb</td>
              <td>ccc</td>
              <td>ddd</td>
            </tr>
          </tbody>
        </table>
      </div>

  <!-- </main> -->

  <!-- <Footer /> -->
  </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'

</script>

<style scoped>
.page{
  font-family: monospace;
  background: white;
}
.table-box {
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.05);
  overflow: hidden;
}

.table {
  width: 100%;
  border-collapse: collapse;
}

.th {
  text-align: left;
  padding: 14px;
  background: #fafafa;
  font-weight: 600;
}


.td {
  padding: 14px;
  border-top: 1px solid #eee;
}

.tr:hover {
  background: #fafafa;
}

.empty {
  text-align: center;
  padding: 20px;
  color: #777;
}

/* .sec-title {
  font-family: 'Martel', serif;
  font-size: 2rem;
  color: #2b2b2bc5;
  font-weight: lighter;
  margin-bottom: 2rem;
}

.stat-card {
  border-radius: 1.5rem;
  border-color: #000000;
  height: 12rem;
  padding: 0.5rem;
}

.card-dark {
  background: #f1f1f1;
}

.card-light {
  background: #ffffff;
}

.stat-title {
  font-family: 'Maven Pro', sans-serif;
  font-size: 1.5rem;
  color: #878787;
}

.stat-data {
  font-family: 'Martian Mono', monospace;
  font-size: 1.8rem;
  font-weight: 300;
  color: #606060;
}

.circle {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 2rem;
  display: flex;
  justify-content: center;
  align-items: center;
}

.circle-light {
  background: #ffffff;
}

.circle-dark {
  background: #f1f1f1;
}

.arrow-img {
  width: 2rem;
  height: 2rem;
  object-fit: contain;
}

.btn-filter {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  font-size: 1rem;
  font-weight: lighter;
  background: #e6e6e6;
}

.btn-filter:hover {
  background: #333333;
  color: #ffffff;
}

.focus-table {
  font-family: 'Maven Pro', sans-serif;
  font-size: 0.95rem;
}

.focus-table thead th {
  font-family: 'Martian Mono', monospace;
  color: #222222;
  font-weight: 200;
  font-size: 1.2rem;
  background-color: #f1f1f1;
  border-color: #d0d0d0;
  margin: 5rem;
}

.focus-table tbody td {
  border-color: #e0e0e0;
  color: #222222;
  vertical-align: middle;
}

.table-link {
  color: #1a1a1a;
  font-family: 'Martian Mono', monospace;
  font-size: 0.8rem;
}

.btn-ql {
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 1rem;
  color: #ffffff;
  background: #555555;
  padding: 0.5rem 1rem;
}

.btn-ql3 {
  width: 86%;
}

.btn-ql:hover {
  color: #ffffff;
  background: #333333;
}

.activity-list {
  font-family: 'Maven Pro', sans-serif;
  color: #444444;
} */
</style>