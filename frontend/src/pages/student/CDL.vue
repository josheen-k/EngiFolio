<template>
    <Navbar/>
    <main class="container py-4">
        <h1>CDL</h1>
    </main>
</template>
<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router'
    import Navbar from '@/components/Navbar.vue'
</script>
<style>
.container {
    min-height: 100vh;
}
</style>