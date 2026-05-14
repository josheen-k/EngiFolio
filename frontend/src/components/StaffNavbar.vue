<template>
  <div class="app-container">
    <div v-if="isMenuOpen" class="menu-backdrop" @click="closeMenu"></div>

    <div class="nav-wrapper">
      <div class="d-flex align-items-center gap-3 nav-left">
        <div class="navLogo"></div>

        <button
          class="menu-toggle"
          type="button"
          @click.stop="toggleMenu"
          aria-label="Toggle navigation"
        >
          <span></span>
          <span></span>
          <span></span>
        </button>

        <ul class="nav-bar">
          <li class="nav-item">
            <router-link active-class="active-link" to="/staff/dashboard" @click="closeMenu">
              Dashboard
            </router-link>
          </li>

          <li class="nav-item">
            <router-link active-class="active-link" to="/staff/competency-review" @click="closeMenu">
              Competency Reviews
            </router-link>
          </li>
          <li class="nav-item">
          <router-link active-class="active-link" to="/staff/students" @click="closeMenu" >
    My Students
  </router-link>
</li>
        </ul>
      </div>

      <div class="nav-item" ref="dropdown">
        <img
          class="rounded-circle av-img"
          src="https://img.freepik.com/free-photo/portrait-smiling-businessman_171337-1192.jpg"
          alt="Profile"
          @click="openDropdown"
        />

        <div v-if="isOpen" class="dd">
          <router-link
            to="/staff/dashboard"
            class="dd-item"
            @click="closeDropdown"
          >
            Dashboard
          </router-link>

          <router-link
            to="/"
            class="dd-item logout"
            @click="closeDropdown"
          >
            Logout
          </router-link>
        </div>
      </div>
    </div>

    <div v-if="isMenuOpen" class="mobile-menu-panel">
      <router-link
        active-class="active-link"
        to="/staff/dashboard"
        @click="closeMenu"
      >
        Dashboard
      </router-link>

      <router-link
        active-class="active-link"
        to="/staff/competency-review"
        @click="closeMenu"
      >
        Competency Reviews
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { onClickOutside } from '@vueuse/core'

const isOpen = ref(false)
const dropdown = ref(null)
const isMenuOpen = ref(false)

const openDropdown = () => {
  isOpen.value = !isOpen.value
}

const closeDropdown = () => {
  isOpen.value = false
}

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

const closeMenu = () => {
  isMenuOpen.value = false
}

onClickOutside(dropdown, () => {
  isOpen.value = false
})
</script>

<style scoped>
.nav-wrapper {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: stretch;
  background-color: #140F50;
  position: relative;
  z-index: 3;
}

.nav-left {
  position: relative;
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