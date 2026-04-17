<template>
  <div v-if="show" class="overlay" @click.self="$emit('close')">
    <div class="modal">
      <slot />
    </div>
  </div>
</template>

<script setup>
defineProps({
  show: Boolean,
});
</script>

<style scoped>
.overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999; 
}

.modal {
  background: white;
  padding: 20px;
  border-radius: 12px;
  width: 420px;
  z-index: 10000;
}
</style>