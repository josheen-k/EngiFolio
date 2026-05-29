import api from '@/services/api'

// Fetches the profile and formats the output for the csv
export const addProfile = async (profileId) => {
  try {
    // Call backend to retrieve the profile data
    const response = await api.get(`/profile/${profileId}`)
    const profileData = response.data;

    // Retrieve names using optional chaining operators in case of missing data
    const firstName = profileData.user?.first_name || ''
    const lastName = profileData.user?.last_name || ''

    // Create an array for the formatted profile and add the basic information to it
    const formattedProfile = [
      '"----- Profile -----"',
      `"Name:","${firstName}","${lastName}"`,
      `"Preferred name:","${profileData.preferred_name || firstName || lastName}"`,
      `"Degree:","${profileData.degree_title || 'N/A'}"`,
      `"Specialisation:","${profileData.specialisation || 'N/A'}"`,
      `"Personal Intro:","${profileData.personal_intro || 'N/A'}"`
    ]

    // Use optional chaining to ensure that links exist within the response data
    if (profileData.links?.length > 0) {
      // For each link in the links, add the link label and url to the formatted profile
      profileData.links.forEach(link => {
        formattedProfile.push(`"${link.link_label}:","${link.link_url}"`)
      })
    }
    // Add extra space at the end of the section and convert the array to a string divided by new lines
    formattedProfile.push('\n\n')
    return formattedProfile.join('\n')
  } catch (error) {
    console.error("Error while fetching profile:", error)
    return ''
  }
}

// Fetches the certificates and formats the output for the csv
export const addCertifications = async (profileId) => {
  try {    
    // Call backend to get student certifications
    const response = await api.get(`/profile/${profileId}/certifications`);
    const profileData = response.data;

    // Create an array to store the certificate information
    const formattedCerts = ['"----- Certificates -----"']

    // Add the achievement certificates category if not empty
    if (profileData.achievement_certs?.length > 0) {
      formattedCerts.push('"-- Achievement Certificates --"')
      formattedCerts.push('"Title","Details","Issued Date"')
      // Push information about each cert to the cert array
      profileData.achievement_certs.forEach(cert => {
        formattedCerts.push([
          `"${cert.title || ''}"`,
          `"${cert.body || ''}"`,
          `"${cert.issued_date || ''}"`,
        ].join(","))
      })
      formattedCerts.push('\n');
    }

    // Add the attainment certificates category if not empty
    if (profileData.attainment_certs?.length > 0) {
      formattedCerts.push('"-- Attainment Certificates --"')
      formattedCerts.push('"Title","Details","Issued Date","Expiry Date"')
      // Push information about each cert to the cert array
      profileData.attainment_certs.forEach(cert => {
        formattedCerts.push([
          `"${cert.title || ''}"`,
          `"${cert.body || ''}"`,
          `"${cert.issued_date || ''}"`,
          `"${cert.expiry_date || ''}"`
        ].join(","))
      })
    }

    // Add extra space at the end of the section and convert the array to a string divided by new lines
    formattedCerts.push('\n\n');
    return formattedCerts.join('\n');
  } catch (error) {
    console.error("Error while fetching certifications:", error);
    return '';
  }
};

// Fetches the competencies and formats the output for the csv
export const addCompetencies = async (profileId) => { 
  try {
    // Call backend to retrieve the student's competencies
    const response = await api.get(`/competency-entries/${profileId}`);
    const userCompetencies = response.data;

    // Add competency headers to the array
    const formattedComp = ['"----- Competencies -----"']
    formattedComp.push(['"Competency Code"',
      '"Experience Title"',
      '"Associated Year"',
      '"Experience Tasks"',
      '"Key Learnings"',
      '"Future Applications"',
      '"Level"',
      '"Start Date"',
      '"End Date"']
    .join(","));

    // Use optional chaining to ensure that competencies exist within the response data
    if (userCompetencies.value?.length > 0) {
      // Add each competency, use empty string as fallback in case of errors
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

    // Add extra space at the end of the section and convert the array to a string divided by new lines
    formattedComp.push('\n\n');
    return formattedComp.join('\n');
    
  } catch (error) {
    console.error("Error while fetching user competencies:", error);
    return '';
  }
};


// Fetches the networking contacts and adds the contents to the file
export const addNetworkingContacts = async (profileId) => { 
  try {
    // Call backend to get student contacts
    const response = await api.get(`/users/${profileId}/industry-contacts`);
    const contacts = response.data;

    // Add contact headers to the array
    const formattedNet = ['"----- Networking Contacts -----"'] 
    formattedNet.push([
      `"Name"`,
      `"Company"`,
      `"Progress Notes"`,
      `"Date Met"`,
    ].join(","));

    // Use optional chaining to ensure that contacts exist within the response data
    if (contacts?.value.length > 0) {
      // Add each contact to the response array
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

    // Add extra space at the end of the section and convert the array to a string divided by new lines
    formattedNet.push('\n\n');
    return formattedNet.join('\n');
  } catch (error) {
    console.error("Error while fetching user contacts:", error);
    return '';
  }
};

export const addCareerPlans = async (profileId) => {
  try {
    // Call backend to get student career plans
    const response = await api.get(`/career-plans/${profileId}`);
    const plans = response.data;

    // Add career plans header to the array
    const formattedPlans = ['"----- Career Development Plans -----"'];
    formattedPlans.push([
        '"Year"',
        '"Professional Interests"',
        '"Employers of Interest"',
        '"Networking Plan"',
        '"Personal Values"',
        '"Extracurriculars"',
        '"Development Focus"',
    ].join(","));

    // Use optional chaining to ensure that career plans response exist within the response data
    if (plans?.length > 0) {
        // Add each plan to the formattedPlans array
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

    // Add extra space at the end of the section and convert the array to a string divided by new lines
    formattedPlans.push('\n\n');
    return formattedPlans.join('\n');
  } catch (error) {
      console.error("Error while fetching career plans:", error);
      return '';
  }
};

// Fetches the goals and adds the contents to the file
export const addGoals = async (profileId) => { 
  try {
    // Call backend to get student goals
    const response = await api.get(`/user/smart-goals/${profileId}`);
    const goals = response.data;

    // Add goals header to the array
    const formattedGoals = ['"----- Goals -----"']
    formattedGoals.push([
      `"Goal Description"`,
      `"Timeline"`,
      `"Progress Notes"`,
      `"Learnings"`,
      `"Start Date"`,
      `"End Date"`,
      `"Completion Date"`,
      `"Completion Notes"`,
      `"Status"`,
    ].join(","));

    // Use optional chaining to ensure that goals response exist within the response data
    if (goals?.length > 0) {
      // Add each goal into the formattedGoals array
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

        // For each goal loop through the goal actions steps to add them to the array as well
        if (goal.action_steps?.length > 0) {
          goal.action_steps.forEach(step => {
            formattedGoals.push(`"","","","","","","","","${step.step_order}. ${step.step_description}"`);
          });
        }
      });
    }

    // Add extra space at the end of the section and convert the array to a string divided by new lines
    formattedGoals.push('\n\n');
    return formattedGoals.join('\n');
  } catch (error) {
    console.error("Error while fetching user goals:", error);
    return '';
  } 
};