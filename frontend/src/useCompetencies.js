import { ref } from 'vue'

export const currentCategories = ref([])
export const discontinuedCategories = ref([])

// return only saved/posted reflections 
export function publishedReflec(compt) {
  if (!compt || (!compt.reflec && !compt.entries)) return []
  const list = compt.entries || compt.reflec || []
  
  return list.filter(r => {
    if (Object.prototype.hasOwnProperty.call(r, 'entry_status_id')) {
      return r.entry_status_id >= 2
    }
    return r.isDraft === false
  })
}

// get highest lvl from reflections
export function getLvl(compt) {
  const entries = compt.entries || compt.reflec || [];
  if (entries.length === 0) return 'Not Started';

  // Find highest entry for each competency
  const highestEntry = entries.reduce((prev, current) => {
    const currentWeight = current.entry_level?.competency_level_weighting || current.weight || 0;
    const prevWeight = prev.entry_level?.competency_level_weighting || prev.weight || 0;
    return currentWeight > prevWeight ? current : prev;
  });

  return highestEntry.entry_level?.competency_level || 'Not Started';
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
    indicator_id: comptId || '',
    entry_level_id: null,
    experience_title: '',
    start_date: '',
    end_date: '',
    associated_year: 1,
    experience_tasks: '',
    key_learnings: '',
    future_applications: '',
    entry_status_id: 1,
    evidenceEntries: []
  }
}
