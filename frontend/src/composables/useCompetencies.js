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

  const published = entries.filter(e => e.entry_status_id !== 1);

  if (published.length === 0) return 'Not Started';

  // Find highest entry for each competency
  const highestEntry = published.reduce((prev, current) => {
    const currentWeight = current.entry_level?.competency_level_weighting || 0;
    const prevWeight = prev.entry_level?.competency_level_weighting || 0;
    return currentWeight > prevWeight ? current : prev;
  });

  return highestEntry.entry_level?.competency_level || 'Not Started';
}

// Makes the date a readable format
export const formatDate = (dateString) => {
  const date = new Date(dateString);
  
  return date.toLocaleDateString('en-AU') + ', ' + 
    date.toLocaleTimeString('en-AU', { 
      hour: 'numeric', 
      minute: '2-digit', 
    }).toLowerCase();
};

export const yearOptions = [
  { value: 0, label: 'Prior to degree' },
  { value: 1, label: 'Year 1' },
  { value: 2, label: 'Year 2' },
  { value: 3, label: 'Year 3' },
  { value: 4, label: 'Year 4' }
]

export const sortByOptions = [
  { value: 'date', label: 'Date' },
  { value: 'name', label: 'Title (A–Z)' }
]

// Used by ViewReflection and AddReflection
// evidence helpers
export function evLabel(type) {
  switch (type) {
    case 'url':
      return 'URL'
    case 'document':
      return 'File'
    case 'image':
      return 'Image'
    case 'video':
      return 'Video'
    default:
      return type || 'File'
  }
}

export function fileAccept(type) {
  switch (type) {
    case 'image':
      return 'image/*'
    case 'video':
      return 'video/*'
    case 'document':
      return '.pdf,.doc,.docx,.txt,.ppt,.pptx'
    default:
      return '*'
  }
}

export function uploadHint(type) {
  switch (type) {
    case 'image':
      return 'PNG, JPG, JPEG, GIF'
    case 'video':
      return 'MP4, MOV'
    case 'document':
      return 'PDF, DOC, DOCX, TXT, PPT, PPTX'
    default:
      return ''
  }
}
