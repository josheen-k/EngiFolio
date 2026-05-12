<template>
  <div class="d-flex gap-2">
    <img
      class="icon-btn"
      src="@/assets/edit.png"
      title="Edit"
      @click="$emit('edit')"
    />

    <img
      class="icon-btn"
      src="@/assets/delete.png"
      title="Delete"
      @click="$emit('delete')"
    />
  </div>
</template>

<script setup>
defineEmits(['edit', 'delete'])
</script>

<style scoped>
.icon-btn {
  width: 1.8rem;
  height: 1.8rem;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.icon-btn:hover {
  transform: scale(1.1);
}
</style>