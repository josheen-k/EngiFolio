<template>
  <AdminNavbar/>

  <!--using toggle instead of sidebar cause there's only 2 views -->
  <div class="toggle">
    <div class="toggle-line">
      <button class="toggle-btn" :class="{active: currTab === 'CATEGORY' }" @click="currTab = 'CATEGORY'">Category</button>
      <button class="toggle-btn" :class="{ active: currTab === 'COMPETENCY' }" @click="currTab = 'COMPETENCY'">Competency</button>
      <!--sliding pill -->
      <div class="toggle-pill" :class="currTab === 'COMPETENCY' ? 'pill-right' : 'pill-left'"></div>
    </div>
  </div>
  <main class="main-area">
    <component :is="currComponent"/>
  </main>
</template>

<script setup>
  import { ref, computed, onMounted } from 'vue'
  import { useRoute } from 'vue-router' 
  import AdminNavbar from '@/components/AdminNavbar.vue';
  import SetupCategory from '@/components/AdminSetupCategory.vue';
  import SetupCompetency from '@/components/AdminSetupCompetency.vue';

  const route = useRoute() 

  // different tabs in side pannel
  const currTab = ref('CATEGORY');

  // render components based on current tab
  const currComponent = computed(()=> {
    switch (currTab.value) {
      case 'CATEGORY':
        return SetupCategory
      case 'COMPETENCY':
        return SetupCompetency
    }
  });

  onMounted(() => {
    if (route.query.tab === 'COMPETENCY') {
      currTab.value = 'COMPETENCY'
    }
  })
</script>

<style scoped>
.toggle {
  display: flex;
  justify-content: center;
  padding: 1.5rem 0 0.5rem;
}

.toggle-line {
  position: relative;
  display: flex;
  background: #f0f0f0;
  border-radius: 2rem;
  padding: 0.3rem;
  gap: 0;
}

.toggle-pill {
  position: absolute;
  top: 0.3rem;
  bottom: 0.3rem;
  width: calc(50% - 0.3rem);
  background: #ffffff;
  border-radius: 2rem;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.25s ease;
  pointer-events: none;
}

.pill-left {
  transform: translateX(0);
}

.pill-right {
  transform: translateX(calc(100%));
}

.toggle-btn {
  position: relative;
  z-index: 1;
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.95rem;
  color: #888888;
  background: transparent;
  border: none;
  padding: 0.45rem 1.5rem 0.45rem 3rem;
  cursor: pointer;
  transition: color 0.2s ease;
}

.toggle-btn.active {
  color: #222222;
}

.main-area {
  padding: 1rem 1.5rem 3rem;
}
</style>