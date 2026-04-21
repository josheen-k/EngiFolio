<template>
  <div class="curr-compt">

    <h1 class="compt-title" v-if="!selectedCompt">Current Competencies</h1>

    <div v-if="selectedCompt" class="detail">

      <div class="d-flex align-items-center">
        <button class="btn btn-filter" @click="closeDetail">Go back</button>
        <h2 class="compt-title">Competency {{ selectedCompt.id }}</h2>
      </div>

      <p class="fs-5">Category: <em>{{ selectedCompt.category }}</em></p>
      <p class="fs-5">Description:</p>
      <p class="detail-txt">{{ selectedCompt.description }}</p>

      <p class="fs-5">Indicators:</p>
      <ul class="ps-3">
        <li class="detail-txt" v-for="(ind, i) in selectedCompt.indicators" :key="i">{{ ind }}</li>
      </ul>

      <div class="d-flex justify-content-between detail-stats">
        <p class="fs-5">Total reflection entries you added: <em>{{ selectedCompt.reflec.length }}</em></p>
        <p class="fs-5">Highest attainment level you reflected: <em>{{ getLvl(selectedCompt) }}</em></p>
      </div>

      <div class="d-flex justify-content-between align-items-center">
        <h3>Your Entries</h3>
        <div class="d-flex gap-3">
          <button type="button" class="btn btn-filter">Add filter</button>
          <button type="button" class="btn btn-add">Add new</button>
        </div>
      </div>

      <div v-if="selectedCompt.reflec.length" class="row g-3">
        <div class="col-12 col-sm-6 col-lg-3" v-for="(reflec, i) in selectedCompt.reflec" :key="i">
          <div class="card compt-card p-3 h-70 reflection-card" @click="openReflec(reflec, i)">
            <p class="compt-label mb-2">{{ reflec.title }}</p>

            <div class="d-flex align-items-center gap-2 mb-2">
              <span class="reflecs rounded-pill px-3 py-1">{{ reflec.year === 0 ? 'PRIOR' : 'YEAR ' + reflec.year }}</span>
              <span class="txt-lvl">{{ reflec.level }}</span>
            </div>

            <p class="txt-lvl">Last updated: {{ reflec.date }}</p>
          </div>
        </div>
      </div>
      <p v-else>No reflection entries yet.</p>
    </div>

    <div v-else class="mb-4" v-for="c in category" :key="c.key">

      <div class="d-flex align-items-center gap-2 mb-3 category" @click="c.open = !c.open">
        <img class="triangle" :class="{ open: c.open }" src="@/assets/triangle.png">
        <span class="c-label">{{ c.label }}</span>
        <span class="txt">{{ c.compt.length }}</span>
      </div>

      <div v-if="c.open" class="d-flex flex-wrap gap-3">
        <div class="compt-wrap" v-for="compt in c.compt" :key="compt.id">

          <div class="card compt-card p-3" @click="openDetail(compt, c.label)">
            <h5 class="compt-label mb-2">Competency {{ compt.id }}</h5>

            <div class="d-flex align-items-center justify-content-start mb-2 gap-2">
              <span class="reflecs rounded-pill px-3 py-1"
                :class="compt.reflec.length ? 'reflecs-blue' : 'reflecs-red'">
                {{ compt.reflec.length }} reflection{{ compt.reflec.length !== 1 ? 's' : '' }}
              </span>

              <img class="plus-btn d-flex align-items-center justify-content-center" src="@/assets/plus-btn.png"
                @click="">
            </div>

            <p class="txt-lvl mb-3">Highest level: {{ getLvl(compt) }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <ViewReflection :show="viewReflec.show" :reflec="viewReflec.reflec" :compt="viewReflec.compt" :index="viewReflec.index"
  @close="closeReflec"/>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import ViewReflection from '@/components/ViewReflection.vue'

const selectedCompt = ref(null);

const viewReflec = ref({
  show: false,
  reflec: null,
  compt: null,
  index: null
});

// dummy data, need to connect this with backend to fetch details
const category = ref([
  {
    key: 'ksb',
    label: 'KNOWLEDGE AND SKILL BASE',
    open: false, //by default
    compt: [
      {
        id: '1.1',
        desc: 'This is an example description of a competency',
        indicators: [
          'Indicator a',
          'Indicator b',
          'Indicator c'
        ],
        reflec: [
          {
            title: 'Experience 1',
            year: 2,
            level: 'Developing',
            date: '2026-03-01'
          },
          {
            title: 'Experience 2',
            year: 3,
            level: 'Confident',
            date: '2026-04-10'
          },
          {
            title: 'Experience 3',
            year: 0,
            level: 'Proficient',
            date: '2026-04-15'
          }
        ]
      },
      {
        id: '1.2',
        desc: 'This is an example description of a competency',
        indicators: [
          'Indicator a',
          'Indicator b',
          'Indicator c'
        ],
        reflec: []
      },
      {
        id: '1.3',
        desc: 'This is an example description of a competency',
        indicators: [
          'Indicator a',
          'Indicator b',
          'Indicator c'
        ],
        reflec: []
      },
      {
        id: '1.4',
        desc: 'This is an example description of a competency',
        indicators: [],
        reflec: []
      },
      {
        id: '1.5',
        desc: 'This is an example description of a competency',
        indicators: [],
        reflec: []
      },
      {
        id: '1.6',
        desc: 'This is an example description of a competency',
        indicators: [],
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
        desc: 'This is an example description of a competency',
        indicators: [],
        reflec: []
      },
      {
        id: '2.2',
        desc: 'This is an example description of a competency',
        indicators: [],
        reflec: []
      },
      {
        id: '2.3',
        desc: 'This is an example description of a competency',
        indicators: [],
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
        id: '3.1',
        desc: 'This is an example description of a competency',
        indicators: [],
        reflec: []
      },
      {
        id: '3.2',
        desc: 'This is an example description of a competency',
        indicators: [],
        reflec: []
      },
      {
        id: '3.3',
        desc: 'This is an example description of a competency',
        indicators: [],
        reflec: []
      }
    ]
  }
]); // end of dummy data

function openDetail(compt, cat) {
  selectedCompt.value = {
    id: compt.id,
    category: cat,
    reflec: compt.reflec ? compt.reflec : [],
    description: compt.desc,
    indicators: compt.indicators
  }
}

function closeDetail() {
  selectedCompt.value = null
}

function getLvl(compt) {
  if (!compt.reflec || compt.reflec.length === 0) {
    return 'Not Started';
  }
  const order = [
    'Not Started',
    'Emerging',
    'Developing',
    'Proficient',
    'Confident'
  ];
  const reflecCopy = [...compt.reflec];

  reflecCopy.sort((a, b) => {
    return order.indexOf(b.level) - order.indexOf(a.level)
  });

  const highestReflec = reflecCopy[0];
  return highestReflec.level;
}

function openReflec(reflec, index) {
  viewReflec.value = {
    show: true,
    reflec,
    compt: selectedCompt.value,
    index
  }
}

function closeReflec() {
  viewReflec.value.show = false
}
</script>

<style scoped>
.curr-compt {
  max-width: 90%;
}

.compt-title {
  font-family: 'Martel', serif;
  font-size: 2rem;
  color: #2b2b2bc5;
  font-weight: lighter;
  margin-bottom: 2rem;
  text-align: center;
}

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

.compt-wrap {
  flex: 0 0 12.5rem;
}

.compt-card {
  width: 13.75rem;
  height: 8.125rem;
  border-radius: 1.5rem;
  border: 1px solid #bababa;
  cursor: pointer;
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
  background-color: #e7e7e7;
  border-radius: 50%;
  font-size: smaller;
  padding: 0.05rem 0.4rem;
}

.txt-lvl {
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

.detail {
  font-family: 'Maven Pro', sans-serif;
  color: #222222;
}

.detail-txt {
  color: #444444;
}

.detail-stats {
  max-width: 90%;
}

.btn-filter {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  font-size: 1rem;
  background: #e6e6e6;
}

.btn-filter:hover {
  background: #666666;
  color: #ffffff;
}

.btn-add {
  font-family: 'Montserrat Alternates', sans-serif;
  border-radius: 1.5rem;
  font-size: 1rem;
  color: #ffffff;
  background: #555555;
}

.btn-add:hover {
  color: #ffffff;
  background: #333333;
}
</style>
