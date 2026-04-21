<template>
  <div v-if="show">
    <div>
      <h2>View Reflection</h2>
      <p>This is empty for now.</p>
      <button @click="$emit('close')">Close</button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  show: Boolean,
  reflec: Object,
  compt: Object,
  index: Number
})
</script>