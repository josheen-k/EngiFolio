import { createRouter, createWebHistory } from 'vue-router'

import Homepage from '@/pages/public/Homepage.vue'
import StudentDashboard from '@/pages/student/Dashboard.vue'
import StudentProfile from '@/pages/student/profile.vue'
import ProfileSettings from '@/pages/student/profileSettings.vue'
import EACompetency from '@/pages/student/eaCompetency.vue'
import CareerPlanning from '@/pages/student/careerPlanning.vue'
import CareerDevelopment from '@/pages/student/careerDevelopment.vue'
import IndustryContacts from '@/pages/student/IndustryContacts.vue'
import Networking from '@/pages/student/Networking.vue'
import Event from '@/pages/student/Event.vue'
import Export from '@/pages/student/export.vue'
import Login from '@/pages/student/login.vue'
import GoalsPage from '@/pages/student/GoalsPage.vue'
import CertificationSettings from '@/pages/student/editCerts.vue'
import CompetencyReview from '@/pages/staff/competencyReview.vue'
import CompetencyEntry from '@/pages/student/competencyEntry.vue'
import StaffDashboard from '@/pages/staff/staffDashboard.vue'
import StaffStudents from '@/pages/staff/staffStudents.vue'

import AdminPage from '@/pages/admin/admin.vue'
import GoalFeedback from '@/pages/staff/goalFeedback.vue'
import CDL from '@/pages/student/CDL.vue'
import PrivacyPolicy from '@/pages/public/PrivacyPolicy.vue'
import AdminSetup from '@/pages/admin/AdminSetup.vue'

const routes = [
  {
    path: '/',
    name: 'Homepage',
    component: Homepage
  },
  {
    path: '/student/dashboard/:id',
    name: 'Dashboard',
    component: StudentDashboard
  },
  {
    path: '/profile/:id',
    name: 'profile',
    component: StudentProfile
  },
  {
    path: '/settings/profile/:id',
    name: 'profile-settings',
    component: ProfileSettings
  },
  {
    path: '/student/career-development/:id',
    name: 'careerDevelopment',
    component: CareerDevelopment
  },
  {
    path: '/student/career-planning/:id',
    name: 'careerPlanning',
    component: CareerPlanning
  },
  {
    path: '/student/eaCompetency/:id',
    name: 'eaCompetency',
    component: EACompetency
  },
  {
    path: '/student/industry-contacts',
    name: 'IndustryContacts',
    component: IndustryContacts
  },
  {
    path: '/student/networking/:id',
    component: Networking,
    children: [
      {
        path: '',
        name: 'networking-events',
        component: Event,
      },
      {
        path: 'contacts',
        name: 'networking-contacts',
        component: IndustryContacts,
      }
    ]
  },
  {
    path: '/student/event',
    name: 'event',
    component: Event
  },
  {
    path: '/student/export/:id',
    name: 'export',
    component: Export
  },
  {
    path: '/student/competency-entry/:id',
    name: 'competencyEntry',
    component: CompetencyEntry
  },
  {
    path: '/student/CDL/:id',
    name: 'CDL',
    component: CDL
  },
  {
    path: '/goals/:id',
    name: 'GoalsPage',
    component: GoalsPage
  },
  {
    path: '/certification-settings/:id',
    name: 'certificationSettings',
    component: CertificationSettings
  },
  {
    path: '/login',
    name: 'login',
    component: Login
  },

  // Staff
  {
    path: '/staff/dashboard',
    name: 'staffDashboard',
    component: StaffDashboard
  },
  {
    path: '/staff/students',
    name: 'staffStudents',
    component: StaffStudents
  },
  {
    path: '/staff/competency-review',
    name: 'staffCompetencyReview',
    component: CompetencyReview
  },
  {
    path: '/staff/goal-feedback',
    name: 'goalFeedback',
    component: GoalFeedback
  },

  // Admin
  {
    path: '/admin/:id',
    name: 'admin',
    component: AdminPage,
    beforeEnter: (to) => {
      return String(to.params.id) === '1' ? true : { name: 'Homepage' }
    }
  },
  
  {
    path: '/certification-settings/:id',
    name: 'certificationSettings',
    component: CertificationSettings
  },
  {
    path: '/student/CDL/:id',
    name: 'CDL',
    component: CDL
  },

  {
    path: '/privacy-policy',
    name: 'PrivacyPolicy',
    component: PrivacyPolicy
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    }
    return { top: 0, left: 0 }
  },
})

export default router