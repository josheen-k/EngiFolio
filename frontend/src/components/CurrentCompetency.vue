<template>
  <div class="curr-compt">
    <div class="mb-4" v-for="c in category" :key="c.key">

      <div class="d-flex align-items-center gap-2 mb-3 category" @click="c.open=!c.open">
        <img class="triangle" :class="{open: c.open}" src="@/assets/triangle.png">
        <span class="c-label">{{ c.label }}</span>
        <span class="txt">{{ c.compt.length }}</span>
      </div>

      <div v-if="c.open" class="row g-3">
        <div class="col-12 col-md-6 col-lg-4 col-xl-4" v-for="compt in c.compt" :key="compt.id">

          <div class="card compt-card p-3">
            <h5 class="compt-label mb-2">Competency {{ compt.id }}</h5>

            <div class="d-flex align-items-center justify-content-between mb-2 gap-2">
              <span class="reflecs rounded-pill px-3 py-1" :class="compt.reflec.length? 'reflecs-blue' : 'reflecs-red'">
                {{ compt.reflec.length}} reflection{{ compt.reflec.length!==1? 's':'' }}
              </span>
              
              <img class="plus-btn d-flex align-items-center justify-content-center" 
              src="@/assets/plus-btn.png" @click="">
            </div>

            <p class="txt mb-3">Highest: {{ getLvl(compt) }}</p>
            <span class="open-txt" @click=""><u>OPEN</u></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { onMounted, ref } from 'vue'
  
  // dummy data, need to connect this with backend to fetch details
  const category= ref([
    {
      key: 'ksb',
      label: 'KNOWLEDGE AND SKILL BASE',
      open: false,
      compt: [
        {
          id: '1.1',
          reflec: [
            { level: 'Developing'},
            { level: 'Competent'},
            { level: 'Proficient'}
          ]
        },
        {
          id: '1.2',
          reflec: [
            { level: 'Emerging'},
            { level: 'Emerging'},
            { level: 'Emerging'}
          ]
        },
        {
          id: '1.3',
          reflec: []
        },
        {
          id: '1.4',
          reflec: []
        }
      ]
    },
    {
      key: 'eaa',
      label: 'ENGINEERING APPLICATION ABILITY',
      open: false,
      compt: [
        {
          id: '2.1',
          reflec: []
        },
        {
          id: '1.2',
          reflec: []
        },
        {
          id: '1.3',
          reflec: []
        }
      ]
    },
    {
      key: 'ppa',
      label: 'PROFESSIONAL AND PERSONAL ATTRIBUTES',
      open: false,
      compt: [
        {
          id: '2.1',
          reflec: []
        },
        {
          id: '1.2',
          reflec: []
        },
        {
          id: '1.3',
          reflec: []
        }
      ]
    }
  ]);

  function getLvl(compt) {
  if (!compt.reflec || compt.reflec.length===0) {
    return 'Not Started';
  }
  const order = [
    'Not Started',
    'Emerging',
    'Developing',
    'Competent',
    'Proficient'
  ];
  const reflecCopy = [...compt.reflec];

  reflecCopy.sort((a, b)=> {
    return order.indexOf(b.level) - order.indexOf(a.level)
  });

  const highestReflec = reflecCopy[0];
  return highestReflec.level;
}
</script>

<style scoped>
.category {
  cursor: pointer;
}

.triangle {
  width: 0.8rem;
  height: 0.8rem;
  transition: transform 0.2s ease;
}

.triangle.open {
  transform: rotate(90deg);
}

.c-label {
  font-family: 'Martian Mono', monospace;
  font-size: 1.4rem;
  font-weight: 100;
}

.compt-card {
  border-radius: 1.5rem;
  border: 1px solid #bababa;
}

.compt-card:hover {
  box-shadow: 0 0.25rem 0.75rem #e5e5e5;
}

.compt-label {
  font-family: 'Maven Pro', sans-serif;
  font-size: 1.3rem;
  font-weight: 100;
  color: #878787;
}

.txt {
  font-family: 'Maven Pro', sans-serif;
}

.reflecs-blue {
  background: #bfe9f7;
  color: #1a6a86;
}

.reflecs-red {
  background: #f5c0c0;
  color: #b03030;
}

.plus-btn {
  width: 1.7rem;
  height: 1.7rem;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.plus-btn:hover {
  transform: scale(1.1);
}

.open-txt {
  cursor: pointer;
}
</style>
