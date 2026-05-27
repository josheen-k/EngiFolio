import { ref } from 'vue'
import api from '@/services/api'

export function useExportData(userId) {

    // Store data for all calls
    const profile = ref(null)
    const userCompetencies = ref(null)
    const contacts = ref(null)


    // Fetches the profile and adds the contents to the file
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

  const addCertifications = async () => {
      try {
        const response = await api.get(`/profile/${userId}`);
        const profileData = response.data;

        const formattedCerts = ['"----- Certificates -----"']

        if (profileData.achievement_certs.length > 0) {
          formattedCerts.push('"-- Achievement Certificates --"')
          formattedCerts.push('"Title","Details","Issued Date"')
          profileData.achievement_certs.forEach(cert => {
            formattedCerts.push(`"${cert.title}","${cert.body || ''}","${cert.issued_date || ''}"`)
          })
        }

        if (profileData.attainment_certs.length > 0) {
          formattedCerts.push('"-- Attainment Certificates --"')
          formattedCerts.push('"Title","Details","Issued Date","Expiry Date"')
          profileData.attainment_certs.forEach(cert => {
            formattedCerts.push(`"${cert.title}","${cert.body || ''}","${cert.issued_date || ''}","${cert.expiry_date || ''}"`)
          })
        }

        formattedCerts.push('\n\n');
        return formattedCerts.join('\n');
      } catch (error) {
        console.error("Error while fetching certifications:", error);
        return '';
      }
    };

    // Fetches the competencies and adds the contents to the file
    const addCompetencies = async () => { 
      try {
        const response = await api.get(`/competency-entries/${userId}`);
        userCompetencies.value = response.data;

        const formattedComp = ['"----- Competencies -----"']
        
        const compHeader = [
          '"Competency Code"',
          '"Experience Title"',
          '"Associated Year"',
          '"Experience Tasks"',
          '"Key Learnings"',
          '"Future Applications"',
          '"Level"',
          '"Start Date"',
          '"End Date"'
        ].join(",");

        formattedComp.push(compHeader);
          if (userCompetencies.value?.length > 0) {
            userCompetencies.value.forEach(comp => {
              const row = [
                `"${comp.indicator?.display_id || ''}"`,
                `"${comp.experience_title || ''}"`,
                `"${comp.associated_year || ''}"`,
                `"${comp.experience_tasks || ''}"`,
                `"${comp.key_learnings || ''}"`,
                `"${comp.future_applications || ''}"`,
                `"${comp.entry_level?.competency_level || ''}"`,
                `"${comp.start_date || ''}"`,
                `"${comp.end_date || ''}"`
              ].join(",");
              formattedComp.push(row);
            });
          }


        formattedComp.push('\n\n');
        return formattedComp.join('\n');
        
      } catch (error) {
        console.error("Error while fetching user competencies:", error);
        return '';
      }
    };


    // Fetches the networking contacts and adds the contents to the file
    const addNetworkingContacts = async () => { 
      try {
        const response = await api.get(`/users/${userId}/industry-contacts`);
        contacts.value = response.data;

        const formattedNet = ['"----- Networking Contacts -----"'] 
        const netHeader = [
          `"Name"`,
          `"Company"`,
          `"Progress Notes"`,
          `"Date Met"`,
        ].join(",");

        formattedNet.push(netHeader);

        if (contacts.value && contacts.value.length > 0) {
          contacts.value.forEach(contact => {
            const row = [
              `"${contact.contact_name || ''}"`,
              `"${contact.company || ''}"`,
              `"${contact.progress_notes || ''}"`,
              `"${contact.date_met || ''}"`,
            ].join(",");
            formattedNet.push(row);
          });

        }

        formattedNet.push('\n\n');
        return formattedNet.join('\n');
      } catch (error) {
        console.error("Error while fetching user contacts:", error);
        return '';
      }
    };

    const addCareerPlans = async () => {
      try {
        const response = await api.get(`/career-plans/${userId}`);
        const plans = response.data;

          const formattedPlans = ['"----- Career Development Plans -----"'];
          const planHeader = [
              '"Year"',
              '"Professional Interests"',
              '"Employers of Interest"',
              '"Networking Plan"',
              '"Personal Values"',
              '"Extracurriculars"',
              '"Development Focus"',
          ].join(",");

          formattedPlans.push(planHeader);

          if (plans && plans.length > 0) {
              plans.forEach(plan => {
                  const row = [
                      `"${plan.plan_year}"`,
                      `"${plan.professional_interests || ''}"`,
                      `"${plan.employers_of_interest || ''}"`,
                      `"${plan.networking_plan || ''}"`,
                      `"${plan.personal_values || ''}"`,
                      `"${plan.extracurriculars || ''}"`,
                      `"${plan.development_focus || ''}"`,
                  ].join(",");
                  formattedPlans.push(row);
              });
          }

          formattedPlans.push('\n\n');
          return formattedPlans.join('\n');
      } catch (error) {
          console.error("Error while fetching career plans:", error);
          return '';
      }
  };

    // Fetches the goals and adds the contents to the file
    const addGoals = async () => { 
    try {
      const response = await api.get(`/user/smart-goals/${userId}`);
      const goals = response.data;

      const formattedGoals = ['"----- Goals -----"']
      
      const goalsHeader = [
        `"Goal Description"`,
        `"Timeline"`,
        `"Progress Notes"`,
        `"Learnings"`,
        `"Start Date"`,
        `"End Date"`,
        `"Completion Date"`,
        `"Completion Notes"`,
        `"Status"`,
      ].join(",");

      formattedGoals.push(goalsHeader);

      if (goals && goals.length > 0) {
        goals.forEach(goal => {
          const row = [
            `"${goal.goal_description}"`,
            `"${goal.timeline || ''}"`,
            `"${goal.progress_notes || ''}"`,
            `"${goal.learnings || ''}"`,
            `"${goal.start_date || ''}"`,
            `"${goal.end_date || ''}"`,
            `"${goal.completion_date || ''}"`,
            `"${goal.completion_notes || ''}"`,
            `"${goal.status?.status || ''}"`,
          ].join(",");

          formattedGoals.push(row);

          if (goal.action_steps && goal.action_steps.length > 0) {
            goal.action_steps.forEach(step => {
              formattedGoals.push(`"","","","","","","","","${step.step_order}. ${step.step_description}"`);
            });
          }
        });
      }

      formattedGoals.push('\n\n');
      return formattedGoals.join('\n');
    } catch (error) {
      console.error("Error while fetching user goals:", error);
      return '';
    } 
  };

  return { addProfile, addCertifications, addCompetencies, addNetworkingContacts, addCareerPlans, addGoals }
}