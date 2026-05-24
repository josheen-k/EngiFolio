<template>
    <Navbar/>
    <main class="cdl-page">
        <section class="cdl-shell">
             <h1 class="cdl-title">Career Development Learning Pages</h1>
             <p v-if="loading"> Loading modules...</p>
             <p v-else-if="errorMessage">{{ errorMessage }}</p>

             <table v-else class="cdl-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>updated</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="module in modules" :key="modules.cdl_id">
                        <td class="title-cell">
                            <a :href="modules.module_url" target="_blank" rel="noopener noreferrer"> {{ module.title }}</a>
                        </td>
                        <td>{{ module.description }}</td>
                        <td>{{ formatDate(module.update_at) }}</td>
                    </tr>
                </tbody>
             </table>
        </section>
    </main>
</template>
<script setup>
    import { onMounted,ref } from 'vue';
    import axios from 'axios';
    import Navbar from '@/components/Navbar.vue'

    const modules = ref([])
    const loading = ref(false)
    const errorMessage = ref('')

    const apiBaseUrl = 'http://127.0.0.1:8000/api'

    const fetchModules = async() => {
        try {
            loading.value = true
            errorMessage.value = ''

            const response = await axios.get(`${apiBaseUrl}/cdl-modules`)
            modules.value = response.data
        } catch (error) {
            console.error('Failed to load: ', error)
            errorMessage.value = 'Failed to load CDL'
        } finally {
            loading.value = false
        }
    }

    const formatDate = (value) => {
        if(!value) return ''
        return new Date(value).toLocaleDateString('en-AU')
    }
    onMounted (() => {
        fetchModules()
    })
</script>
<style>
.container {
    min-height: 100vh;
}

.cdl-page {
    min-height: 100vh;
    background: #f7f9fc;
}

.cdl-shell {
    max-width: 1200px;
    margin: 0 auto;
    padding: 32px 24px
}

.cdl-title {
    font-size: 2.2rem;
    margin-bottom: 24px;
}

.cdl-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.cdl-table th,
.cdl-table td{
    border: 1px solid #d9dfe8;
    padding: 16px;
    text-align: left;
    vertical-align: top;
}

.cdl-table th {
    background: #f3f6fa;
    font-weight: 700;
}

.title-cell a {
    color: #2563eb;
    text-decoration: underline;
}
</style>