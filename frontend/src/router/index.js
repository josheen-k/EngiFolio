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
import GoalsPage from '@/pages/student/GoalsPage.vue'
import CertificationSettings from '@/pages/student/editCerts.vue'
import CompetencyReview from '@/pages/staff/competencyReview.vue'
import competencyEntry from '@/pages/student/competencyEntry.vue'
import staffDashboard from '@/pages/staff/staffDashboard.vue'
import staffStudents from '@/pages/staff/staffStudents.vue'

const routes = [
  {
    path: '/',
    name: 'Homepage',
    component: Homepage
  },
  {
    path: '/student/dashboard/:id',
    name: 'Dashboard',
    component: StudentDashboard,
    meta: { requiresAuth: true, role: 'student' }
  },
  {
    path: '/profile/:id',
    name: 'profile',
    component: StudentProfile,
    meta: { requiresAuth: true, roles: ['staff', 'student'] }

  },
  {
    path: '/settings/profile/:id',
    name: 'profile-settings',
    component: ProfileSettings
  },

    {
    path: '/student/career-development/:id',
    name: 'careerDevelopment',
    component: CareerDevelopment,
    meta: { requiresAuth: true, role: 'student' }
  },
  {
    path: '/student/career-planning/:id',
    name: 'careerPlanning',
    component: CareerPlanning,
    meta: { requiresAuth: true, role: 'student' }
  },
  {
    path: '/student/eaCompetency/:id',
    name: 'eaCompetency',
    component: EACompetency,
    meta: { requiresAuth: true, role: 'student' }
  },
  {
    path: '/student/industry-contacts',
    name: 'IndustryContacts',
    component: IndustryContacts,
    meta: { requiresAuth: true, role: 'student' }
  },

  {
    path: '/student/networking/:id',
    name: 'networking',
    component: Networking,
    meta: { requiresAuth: true, role: 'student' }
  },

  {
    path: '/student/export/:id',
    name: 'export',
    component: Export,
    meta: { requiresAuth: true, role: 'student' }
  },

  {
    path: '/login',
    name: 'login',
    component: Login
  },

  {
    path: '/goals/:id',
    name: 'GoalsPage',
    component: GoalsPage,
    meta: { requiresAuth: true, role: 'student' }
  },

  {
    path: '/certification-settings/:id',
    name: 'certificationSettings',
    component: CertificationSettings
  },

  {
    path: '/student/competency-entry/:id',
    name: 'competencyEntry',
    component: competencyEntry,
    meta: { requiresAuth: true, role: 'student' }
  },

  {
    path: '/staff/competency-review',
    name: 'staffCompetencyReview',
    component: CompetencyReview,
    meta: { requiresAuth: true, role: 'staff' }
  },
  {
    path: '/staff/dashboard',
    name: 'staffDashboard',
    component: staffDashboard,
    meta: { requiresAuth: true, role: 'staff' }
  },
  {
    path: '/staff/students',
    name: 'staffStudents',
    component: staffStudents,
    meta: { requiresAuth: true, role: 'staff' }
  },

]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

router.beforeEach((to, from, next) => {
  const user = JSON.parse(localStorage.getItem('user'))

  if (to.meta.requiresAuth && !user) {
    return next('/login')
  }

  if (to.meta.roles && user && !to.meta.roles.includes(user.role)) {
    if (user.role === 'staff') return next('/staff/dashboard')
    if (user.role === 'student') return next(`/student/dashboard/${user.user_id}`)
    return next('/')
  }

  if (to.meta.role && user && to.meta.role !== user.role) {
    if (user.role === 'staff') return next('/staff/dashboard')
    if (user.role === 'student') return next(`/student/dashboard/${user.user_id}`)
    return next('/')
  }

  next()
})
export default router;