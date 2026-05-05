import { ref } from 'vue'

// dummy data, need to get this from backend
export const currentCategories = ref([
  {
    key: 'ksb',
    label: 'KNOWLEDGE AND SKILL BASE',
    open: true,
    compt: [
      {
        id: '1.1',
        desc: 'This is an example description of a competency.',
        reflec: [
          {
            title: 'Lab Report Analysis',
            year: 2,
            level: 'Developing',
            date: '01/04/2023',
            startDate: '10/01/2023',
            endDate: '11/02/2024',
            tasks: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            learnings: 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC.',
            future: 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using "Content here, content here", making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text.',
            isDraft: false,
            feedback: 'Good structure, but expand your analysis section.',
            feedbackAuthor: 'Dr Smith',
            evidenceEntries: [
              {
                type: 'url',
                value: 'https://myevidence.com/report',
                fileName: ''
              },
              {
                type: 'document',
                value: '',
                fileName: 'Myevidence.pdf'
              }
            ]
          },
          {
            title: 'Industry Placement',
            year: 3,
            level: 'Developing',
            date: '15/06/2024',
            startDate: '',
            endDate: '',
            tasks: '',
            learnings: '',
            future: '',
            isDraft: false,
            evidenceEntries: [
              {
                type: '',
                value: '',
                fileName: ''
              }
            ]
          }
        ]
      },
      {
        id: '1.2',
        desc: 'Comprehensive, theory based understanding of the underpinning natural and physical sciences and the engineering fundamentals applicable to the engineering discipline.',
        reflec: [
          {
            title: 'First Year Project',
            year: 1,
            level: 'Emerging',
            date: '01/04/2022',
            startDate: '',
            endDate: '',
            tasks: '',
            learnings: '',
            future: '',
            isDraft: false,
            feedback: 'Good structure, but expand your analysis section.',
            feedbackAuthor: 'Dr Smith',
            evidenceEntries: [
              {
                type: '',
                value: '',
                fileName: ''
              }
            ]
          },
          {
            title: 'Group Research Task',
            year: 0,
            level: 'Developing',
            date: '01/04/2023',
            startDate: '',
            endDate: '',
            tasks: '',
            learnings: '',
            future: '',
            isDraft: false,
            evidenceEntries: [
              {
                type: '',
                value: '',
                fileName: ''
              }
            ]
          },
          {
            title: 'Engineering Design Expo',
            year: 3,
            level: 'Confident',
            date: '01/04/2024',
            startDate: '',
            endDate: '',
            tasks: '',
            learnings: '',
            future: '',
            isDraft: false,
            evidenceEntries: [
              {
                type: '',
                value: '',
                fileName: ''
              }
            ]
          },
          {
            title: 'Capstone Project',
            year: 4,
            level: 'Proficient',
            date: '01/04/2025',
            startDate: '',
            endDate: '',
            tasks: '',
            learnings: '',
            future: '',
            isDraft: false,
            evidenceEntries: [
              {
                type: '',
                value: '',
                fileName: ''
              }
            ]
          }
        ]
      },
      {
        id: '1.3',
        desc: 'This is an example description of a competency.',
        indicators: [
          'Indicator a',
          'Indicator b'
        ],
        reflec: []
      },
      {
        id: '1.4',
        desc: 'This is an example description of a competency.',
        indicators: [],
        reflec: []
      },
      {
        id: '1.5',
        desc: 'This is an example description of a competency.',
        indicators: [],
        reflec: []
      },
      {
        id: '1.6',
        desc: 'This is an example description of a competency.',
        indicators: [],
        reflec: []
      }
    ]
  },
  {
    key: 'eaa',
    label: 'ENGINEERING APPLICATION ABILITY',
    open: true,
    compt: [
      {
        id: '2.1',
        desc: 'This is an example description of a competency.',
        indicators: [],
        reflec: []
      },
      {
        id: '2.2',
        desc: 'This is an example description of a competency.',
        indicators: [],
        reflec: []
      },
      {
        id: '2.3',
        desc: 'This is an example description of a competency.',
        indicators: [],
        reflec: []
      },
      {
        id: '2.4',
        desc: 'This is an example description of a competency.',
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
        id: '3.3',
        desc: 'This is an example description of a competency.',
        indicators: [],
        reflec: []
      },
      {
        id: '3.4',
        desc: 'This is an example description of a competency.',
        indicators: [],
        reflec: []
      },
      {
        id: '3.5',
        desc: 'This is an example description of a competency.',
        indicators: [],
        reflec: []
      },
      {
        id: '3.6',
        desc: 'This is an example description of a competency.',
        indicators: [],
        reflec: []
      }
    ]
  }
])

// discontinued 
export const discontinuedCategories = ref([
  {
    key: 'ksb',
    label: 'KNOWLEDGE AND SKILL BASE',
    open: false,
    compt: []
  },
  {
    key: 'eaa',
    label: 'ENGINEERING APPLICATION ABILITY',
    open: false,
    compt: []
  },
  {
    key: 'ppa',
    label: 'PROFESSIONAL AND PERSONAL ATTRIBUTES',
    open: true,
    compt: [
      {
        id: '3.1',
        desc: 'This is an example description of a competency.',
        indicators: [],
        reflec: []
      },
      {
        id: '3.2',
        desc: 'This is an example description of a competency.',
        indicators: [],
        reflec: []
      }
    ]
  }
])



// return only saved/posted reflections 
export function publishedReflec(compt) {
  return compt.reflec.filter(function (r) {
    return r.isDraft == false
  })
}

// get highest lvl from reflections
export function getLvl(compt) {
  const published = publishedReflec(compt)
  if (published.length === 0) {
    return 'Not Started'
  }
  const order = ['Not Started', 'Emerging', 'Developing', 'Proficient', 'Confident']

  const sorted = [...published].sort(function (a, b) {
    return order.indexOf(b.level) - order.indexOf(a.level)
  })
  return sorted[0].level
}


// get list of curr competencies to show in dropdown
export function getAllCompts() {
  const all = []

  for (const category of currentCategories.value) {
    for (const compt of category.compt) {
      all.push(compt)
    }
  }
  return all
}

// return today's date
export function todayStr() {
  return new Date().toLocaleDateString('en-GB')
}

// get empty reflection for form
export function blankForm(comptId) {
  return {
    comptId: comptId || '',
    level: 'Emerging',
    title: '',
    startDate: '',
    endDate: '',
    year: '1',
    tasks: '',
    learnings: '',
    future: '',
    evidenceEntries: [
      {
        type: '',
        value: '',
        fileName: ''
      }
    ]
  }
}