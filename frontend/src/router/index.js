import { createRouter, createWebHistory } from 'vue-router'
import Homepage from '@/pages/public/Homepage.vue'
import StudentDashboard from '../pages/student/Dashboard.vue'
import StudentProfile from '../pages/student/profile.vue'
import ProfileSettings from '../pages/student/profileSettings.vue'
import EACompetency from '../pages/student/eaCompetency.vue'
import CareerPlanning from '../pages/student/careerPlanning.vue'
import CareerDevelopment from '@/pages/student/careerDevelopment.vue'
import Event from '@/pages/student/Event.vue'
import Networking from '@/pages/student/Networking.vue'

const routes = [
  {
    path: '/',
    name: 'Homepage',
    component: Homepage
  },

  {
    path: '/student/dashboard',
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
    name: 'careerDevelopment',
    component: CareerDevelopment
  },
  
  {
    path: '/student/career-planning',
    name: 'careerPlanning',
    component: CareerPlanning
  },

  {
    path: '/student/eaCompetency',
    name: 'eaCompetency',
    component: EACompetency
  },

  {
    path: '/student/networking',
    name: 'networking',
    component: Networking
  },

  {
    path: '/student/event',
    name: 'event',
    component: Event
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
})

export default router;
