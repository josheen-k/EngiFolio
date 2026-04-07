import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '../pages/home.vue'
import StudentProfile from '../pages/profile.vue'
import ProfileSettings from '../pages/profileSettings.vue'
import EACompetency from '../pages/eaCompetency.vue'
import CareerPlanning from '../pages/careerPlanning.vue'
import CareerDevelopment from '@/pages/careerDevelopment.vue'

const routes = [
  {
    path: '/',
    name: 'homePage',
    component: HomePage
  },

  {
    path: '/profile/username',
    name: 'profile',
    component: StudentProfile
  },

  {
    path: '/settings/profile',
    name: 'profile-settings',
    component: ProfileSettings
  },

    {
    path: '/career-development',
    name: 'careerDevelopment',
    component: CareerDevelopment
  },
  
  {
    path: '/career-planning',
    name: 'careerPlanning',
    component: CareerPlanning
  },

  {
    path: '/eaCompetency',
    name: 'eaCompetency',
    component: EACompetency
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
})

export default router
