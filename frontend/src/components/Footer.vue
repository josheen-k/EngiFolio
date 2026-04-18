<template>

  <footer class="footer">
    <Divider />

    <div class="footer-content d-flex justify-content-between flex-wrap gap-3 px-4 px-md-5 py-4">
      <span>©EngiFolio 2026</span>
      <div class="logo-img"></div>
      <span><u>Privacy Policy</u></span>
    </div>
  </footer>

</template>

<script>
import Divider from './Divider.vue';

export default {
  name: 'Footer',
  components: {
    Divider
  }
};
</script>

<style scoped>

.footer-content {
  margin-top: -3.5rem;
  font-family: 'Maven Pro', sans-serif;
  color: #2b2b2b;
}

.logo-img {
		width: 6.25rem;
		height: 3.75rem;
		background: linear-gradient(45deg, #d9bebe, #6b6be4);
		-webkit-mask: url('@/assets/engiFolio.png') no-repeat center;
		-webkit-mask-size: contain;
		mask: url('@/assets/engiFolio.png') no-repeat center;
		mask-size: contain;
	}
</style>