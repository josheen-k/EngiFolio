import { createRouter, createWebHistory } from 'vue-router'
// import App from './App.vue';
// import Create from './components/Create.vue';
// import Index from './components/Index.vue';
// import Edit from './components/Edit.vue';


// import { createApp } from 'vue';


// // Define your routes
// const routes = [
//     {
//         name: 'Create',
//         path: '/create',
//         component: Create
//     },
//     {
//         name: 'Index',
//         path: '/',
//         component: Index
//     },
//     {
//         name: 'Edit',
//         path: '/edit/:id',
//         component: Edit
//     }
// ];

// // Create the router instance
// const router = createRouter({
//     history: createWebHistory(),
//     routes,
// });

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [],
})

// // Create and mount the root instance
// createApp(App)
//     .use(router)
//     .mount('#app');

export default router
