import { createRouter, createWebHistory} from 'vue-router'
import EventPage from '@/views/EventPage.vue'

const routes = [
    {
        path: '/events',
        component: EventPage
    }
]

const router = createRouter ({
    history: createWebHistory(),
    routes
})

export default router