import { createRouter, createWebHistory } from 'vue-router'
import PostTable from '../pages/PostTable.vue'
import Post from '../pages/Post.vue'
import Edit from '../pages/Edit.vue'
import Staff from '../pages/Staff.vue'
import Student from '../pages/Student.vue'

const routes = [
  {
    path: '/',
    name: 'postTable',
    component: PostTable
  },
  {
    path: '/post/:id',
    name: 'post',
    component: Post
  },
  {
    path: '/edit/:id',
    name: 'edit',
    component: Edit
  },
  {
    path: '/staff',
    name: 'staff',
    component: Staff
  },
  {
    path: '/student',
    name: 'student',
    component: Student
  }

]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
})

export default router
