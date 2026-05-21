<template>
    <div class="cdl-page">
        <Navbar/>
        <main class="cdl-shell">
            <section class="cdl-message-card">
                <h1 class="cdl-title">Career Development Learning Page</h1>
                <p class="cdl-message">To be updated at Adelaide University.</p>
            </section>
        </main>
    </div>
    
</template>
<script setup>
    import Navbar from '@/components/Navbar.vue';
</script>
<style>
.cdl-page {
      font-family: 'Martel', sans-serif;
    min-height: 100vh;
    background: #ffffff;
    display: flex;
    flex-direction: column;
}

.cdl-shell {
    flex: 1;
    max-width: 1200px;
    width: 100%;
    margin: 0 auto;
    padding: 3rem 1.5rem;
    box-sizing: border-box;
}

.cdl-message-card {
    background: #ffffff;
    border: 1px solid #d9e0e7;
    border-radius: 1rem;
    padding: 2rem;
}

.cdl-message {
    margin: 0;
    color: #4e6577;
    font-size: 1.1rem;
    line-height: 1.6;
}

.cdl-title {
    font-size: 2.2rem;
    margin-bottom: 24px;
}


</style>