
<template>
  <div id="app" class="p-4">
    <header>
      <h1>EngiFolio - Industry Contacts</h1>
    </header>

    <main>
      <IndustryContacts />
    </main>
  </div>
</template>

<script setup>
import IndustryContacts from './components/IndustryContact.vue';
</script>

<style>
#app {
  font-family: Arial, Helvetica, sans-serif, Helvetica, Arial, sans-serif;
  color: grey black;
  max-width: 600px;
  margin: 0 auto;
}

header {
  margin-bottom: 2rem;
  text-align: center;
}

main {
  background: whitesmoke;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0,0,0,0.05);
}
</style>
