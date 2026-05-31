// Return only saved/posted reflections 
export const publishedReflec = (compt) => {
  if (!compt || !compt.reflec) return []
  const list = compt.reflec || []
  // Filter out the entries are not in draft status (status of 1)
  return list.filter(r => r.entry_status_id >= 2)
}

// get highest lvl from reflections
export const getLvl = (compt) => {
  const entries = compt.reflec || [];

  // Filter out all reflections that are marked as draft
  const published = entries.filter(e => e.entry_status_id !== 1);

  // If none exist then highest is not started
  if (published.length === 0) return 'Not Started';

  // Find highest entry for each competency, loop though and compare each weighting while returning the entry with the highest competency level
  const highestEntry = published.reduce((prev, current) => {
    const currentWeight = current.entry_level?.competency_level_weighting || 0;
    const prevWeight = prev.entry_level?.competency_level_weighting || 0;
    return currentWeight > prevWeight ? current : prev;
  });

  return highestEntry.entry_level?.competency_level || 'Not Started';
}

// Translate the raw date string into a readable australian format
export const formatDate = (dateString) => {
  // Takes a raw text string and passes it to the date constructor 
  const date = new Date(dateString);
  // Converts date to au string, numeric removes leading zeros while 2-digit allows leading zeros for minutes
  return date.toLocaleDateString('en-AU') + ', ' + 
    date.toLocaleTimeString('en-AU', { 
      hour: 'numeric', 
      minute: '2-digit', 
    });
};

// The options that the user can select for the year of the entry
export const yearOptions = [
  { value: 0, label: 'Prior to degree' },
  { value: 1, label: 'Year 1' },
  { value: 2, label: 'Year 2' },
  { value: 3, label: 'Year 3' },
  { value: 4, label: 'Year 4' }
]

// Options that the user can sort by 
export const sortByOptions = [
  { value: 'date', label: 'Date' },
  { value: 'name', label: 'Title (A–Z)' }
]

// Used by ViewReflection and AddReflection
// Evidence helpers
export const evLabel = (type) => {
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

// States what file types are accepted depending on evidence type selected
export const fileAccept = (type) => {
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

// Gives the user a prompt about file types depending on evidence type selected
export const uploadHint = (type) => {
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
