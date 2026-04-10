import { createRouter, createWebHistory } from 'vue-router'
import IndustryContact from '../components/IndustryContact.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: IndustryContact,
  },
  {
    path: '/industry-contacts',
    name: 'IndustryContacts',
    component: IndustryContact,
  },

];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [],
})

export default router
