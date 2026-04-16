import { createRouter, createWebHistory } from 'vue-router'

import Homepage from '@/pages/public/Homepage.vue'
import StudentDashboard from '@/pages/student/Dashboard.vue'
import StudentProfile from '@/pages/student/profile.vue'
import ProfileSettings from '@/pages/student/profileSettings.vue'
import EACompetency from '@/pages/student/eaCompetency.vue'
import CareerPlanning from '@/pages/student/careerPlanning.vue'
import CareerDevelopment from '@/pages/student/careerDevelopment.vue'
import IndustryContacts from '@/pages/student/IndustryContacts.vue';
import Networking from '@/pages/student/Networking.vue'
import Export from '@/pages/student/export.vue'
import Login from '@/pages/student/login.vue'

import GoalsPage from '../pages/student/GoalsPage.vue'
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
    path: '/student/career-development',

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

  {
    path: '/student/networking/:id',
    name: 'networking',
    component: Networking
  },

  {
    path: '/student/export/:id',
    name: 'export',
    component: Export
  },

  {
    path: '/login',
    name: 'login',
    component: Login
  },

  {
  path: '/goals/:id',
  name: 'GoalsPage',
  component: GoalsPage
  }

]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default router;