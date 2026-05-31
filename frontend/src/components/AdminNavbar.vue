<template>
  <div class="app-container">
    <nav class="nav-wrapper">
        <div class="d-flex align-items-center gap-3 nav-left">
            <!-- <router-link to="/"> -->
                <div class="navLogo"></div>
            <!-- </router-link> -->
             <div class="nav-item">
                <router-link active-class="active-link" :to="`/admin/${$route.params.id}`" @click="closeMenu">Dashboard</router-link>
            </div>
            <div class="nav-item">
                <router-link active-class="active-link" :to="`/admin/${$route.params.id}/management`" @click="closeMenu">Management</router-link>
            </div>
        </div>

        <div class="nav-bar">
            <div class="nav-item">
                <router-link to="/" class="btn-back-custom">Sign Out</router-link>
            </div>
        </div>
    </nav>
  </div>
</template>

<script setup>        
</script>

<style scoped>
.nav-wrapper {
	width: 100%;
	max-width: 100%;
	display: flex;
	justify-content: space-between;
	align-items: stretch;
	background-color: #140F50;
	padding: 0;
	overflow-x: hidden;
	overflow-y: visible;
	position: relative;
	z-index: 3;
	overflow: visible
}

.nav-left {
	position: relative;
}

.nav-bar {
  display: flex;
  margin-right: 1rem;
  align-items: center;
}

.nav-item {
	display: flex;
	align-items: center;
	position: relative;
}

.nav-item a {
	color: #a7a7a7;
	font-family: 'Montserrat Alternates', sans-serif;
	font-size: 1.2rem;
	text-decoration: none;
	display: flex;
	align-items: center;
  padding: 0.6rem;
	transition: color 0.3s ease;
	height: 30px;
}

.nav-item a:hover,
.nav-item a.active-link {
    color: #ffffff;
}

.navLogo {
  width: 6.25rem;
  height: 3.75rem;
  margin: 0 1.5rem;
  background: linear-gradient(45deg, #d9bebe, #6b6be4);
  -webkit-mask: url('@/assets/engiFolio.png') no-repeat center;
  -webkit-mask-size: contain;
  mask: url('@/assets/engiFolio.png') no-repeat center;
  mask-size: contain;
}
</style>