<template>

  <footer class="footer">
    <div class="footer-content d-flex justify-content-between flex-wrap">
      <div class="footer-left ms-4 d-flex flex-column align-items-center">
        <div class="logo-img"></div>
        <span>©EngiFolio 2026</span>
      </div>
      <span>EngiFolio acknowledges the Kaurna people as the Traditional Owners of the Country<br>
        where the city of Adelaide is situated today, and pays its respects to Elders past and present.</span>
      <span class="me-4 footer-right"><u>Privacy Policy</u></span>
    </div>
  </footer>

</template>

<script>
// import Divider from './Divider.vue';

export default {
  name: 'Footer'
};
</script>

<style scoped>
.footer {
  background: #130f4d;
}

.footer-content {
  font-family: 'Maven Pro', sans-serif;
  color: #a7a7a7;
  align-items: center;
}

span {
  text-align: center;
}

.footer-left span {
  margin-top: -0.3rem;
  margin-bottom: 0.5rem;
}

.footer-right {
  cursor: pointer;
  transition: color 0.3s ease;
}

.footer-right:hover {
  color: #ffffff;
}

.logo-img {
  width: 5.5rem;
  height: 3rem;
  background: linear-gradient(45deg, #d9bebe, #6b6be4);
  -webkit-mask: url('@/assets/engiFolio.png') no-repeat center;
  -webkit-mask-size: contain;
  mask: url('@/assets/engiFolio.png') no-repeat center;
  mask-size: contain;
}
</style>