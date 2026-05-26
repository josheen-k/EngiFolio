<template>
  <div class="app-container">
    <nav class="nav-wrapper">
        <div>
            <!-- <router-link to="/"> -->
                <div class="navLogo"></div>
            <!-- </router-link> -->
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
  height: 60px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #140F50;
  position: relative;
  z-index: 3;
}

.nav-item a {
  color: #a7a7a7;
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 1.1rem;
  text-decoration: none;
  display: flex;
  align-items: center;
  padding: 0.6rem;
  transition: color 0.3s ease;
}

.nav-item {
  display: flex;
  align-items: center;
  position: relative;
  padding-right: 20px;
}

.btn-back-custom {
    font-size: 1.2rem;
    padding: 6px 18px;
}
.menu-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.12);
  z-index: 2;
}

.mobile-menu-panel {
  display: none;
}

.nav-bar {
  display: flex;
  list-style-type: none;
  margin: 0;
  padding: 0;
  align-items: center;
  gap: 0.8rem;
}

.nav-item {
  display: flex;
  align-items: center;
  position: relative;
}

.menu-toggle {
  display: none;
  background: transparent;
  border: none;
  padding: 0.3rem;
  margin-left: 0.2rem;
  position: relative;
  z-index: 3;
  cursor: pointer;
}

.menu-toggle span {
  display: block;
  width: 1.2rem;
  height: 2px;
  background: #e8e8e8;
  margin: 3px 0;
}

.nav-item a {
  color: #a7a7a7;
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 1.1rem;
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

.av-img {
  width: 3rem;
  height: 3rem;
  object-fit: cover;
  border: 2px solid #c5c5c5;
  cursor: pointer;
  margin: 0 20px;
}

.dd {
  position: absolute;
  right: 10px;
  top: 100%;
  width: 140px;
  padding: 0.4rem;
  background: #ffffff;
  border: 1px solid #bebebe;
  border-radius: 0.8rem;
  box-shadow: 0 0.5rem 1.4rem rgba(0, 0, 0, 0.3);
}

.nav-bar {
    display: flex;
    align-items: center;
}


.dd a.dd-item {
  color: #444444;
  font-family: 'Montserrat Alternates', sans-serif;
  font-size: 0.9rem;
  display: block;
  padding: 0.5rem 0.8rem;
  text-align: center;
  text-decoration: none;
}

.dd a.dd-item:hover {
  background-color: #f1f1f1;
  color: #000000;
  border-radius: 0.5rem;
}

.dd a.dd-item.logout {
  color: #ff746c;
}

@media (max-width: 768px) {
  .menu-toggle {
    display: inline-block;
  }

  .nav-bar {
    display: none;
  }

  .mobile-menu-panel {
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 3.55rem;
    left: 0.7rem;
    min-width: 11rem;
    background: #130f4d;
    border: 1px solid #2a246d;
    border-radius: 0.65rem;
    padding: 0.35rem;
    z-index: 2;
  }

  .mobile-menu-panel a {
    color: #a7a7a7;
    font-family: 'Montserrat Alternates', sans-serif;
    text-decoration: none;
    padding: 0.55rem 0.6rem;
    border-radius: 0.45rem;
  }

  .mobile-menu-panel a:hover,
  .mobile-menu-panel a.active-link {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.08);
  }
}
</style>