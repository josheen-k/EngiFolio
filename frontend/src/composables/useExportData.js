import { ref } from 'vue'
import api from '@/services/api'

export function useExportData(userId) {

    const profile = ref(null)
    const userCompetencies = ref(null)
    const contacts = ref(null)
    const plan = ref(null)

    const addProfile = async () => {
    try {
      const response = await api.get(`/profile/${userId}`)
      profile.value = response.data

      const firstName = profile.value.user?.first_name || ''
      const lastName = profile.value.user?.last_name || ''

      const formattedProfile = [
        '"----- Profile -----"',
        `"Name:","${firstName}","${lastName}"`,
        `"Preferred name:","${profile.value.preferred_name || firstName}"`,
        `"Degree:","${profile.value.degree_title}"`,
        `"Specialisation:","${profile.value.specialisation}"`,
        `"Personal Intro:","${profile.value.personal_intro}"`
      ]

      if (profile.value.links?.length > 0) {
        profile.value.links.forEach(link => {
          formattedProfile.push(`"${link.link_label}:","${link.link_url}"`)
        })
      }

      formattedProfile.push('\n\n')
      return formattedProfile.join('\n')
    } catch (error) {
      console.error("Error while fetching profile:", error)
      return ''
    }
  }
}