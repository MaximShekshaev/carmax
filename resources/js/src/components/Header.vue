<template>
  <nav
    :class="['navbar', 'navbar-expand-lg', { scrolled: isScrolled }]"
  >
    <div class="container d-flex justify-content-between align-items-center">

      <router-link class="navbar-brand" to="/">
        CAR<span>MAX</span>
      </router-link>

      <div class="d-flex align-items-center gap-4">

        <router-link class="nav-link" to="/">Главная</router-link>
        <router-link class="nav-link" to="/admin/login">Админ</router-link>
        
        <template v-if="!isAuthenticated">
          <router-link class="nav-link" to="/login">Вход</router-link>
          <router-link class="btn btn-accent" to="/register">
            Регистрация
          </router-link>
        </template>

        <template v-else>
          <router-link class="nav-link" to="/my-rentals">
            Моя аренда
          </router-link>
          <button class="btn btn-logout" @click="handleLogout">
            Выйти
          </button>
        </template>

      </div>
    </div>
  </nav>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'
import { useStorage } from '@vueuse/core'
import { useRouter } from 'vue-router'
import { logout } from '../auth'

const router = useRouter()
const token = useStorage('token', null)
const isAuthenticated = computed(() => !!token.value)

const handleLogout = async () => {
  try {
    if (token.value) await logout(token.value)
  } finally {
    token.value = null
    router.push('/')
  }
}

const isScrolled = ref(false)
const onScroll = () => {
  isScrolled.value = window.scrollY > 20
}

onMounted(() => window.addEventListener('scroll', onScroll))
onUnmounted(() => window.removeEventListener('scroll', onScroll))
</script>

<style scoped>
/* ШАПКА В ВЕРХНЕЙ ТОЧКЕ (Прозрачно-белая) */
.navbar {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1000;
  padding: 20px 0;
  background: rgba(255, 255, 255, 0.75);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border-bottom: 1px solid rgba(0, 0, 0, 0.04);
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

/* ПРИ СКРОЛЛЕ (Становится плотнее, появляется тень) */
.navbar.scrolled {
  padding: 14px 0;
  background: rgba(255, 255, 255, 0.95);
  border-bottom: 1px solid rgba(0, 0, 0, 0.08);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
}

/* ЛОГОТИП */
.navbar-brand {
  font-size: 1.5rem;
  font-weight: 900;
  letter-spacing: 1.5px;
  color: #111827;
  text-transform: uppercase;
  text-decoration: none;
  transition: opacity 0.3s;
}

.navbar-brand:hover {
  opacity: 0.8;
}

.navbar-brand span {
  color: #6b7280; /* Спокойный серый акцент для MAX */
  font-weight: 400;
}

/* ССЫЛКИ НАВИГАЦИИ */
.nav-link {
  color: #4b5563;
  font-size: 0.95rem;
  font-weight: 500;
  position: relative;
  text-decoration: none;
  padding: 4px 0;
  transition: color 0.3s ease;
}

.nav-link:hover {
  color: #111827;
}

/* Элегантная линия подчеркивания при наведении */
.nav-link::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: 0;
  width: 0;
  height: 2px;
  background: #111827;
  transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.nav-link:hover::after {
  width: 100%;
}

/* КНОПКА: РЕГИСТРАЦИЯ (Стильный черный минимализм) */
.btn-accent {
  background: #111827;
  color: #ffffff !important;
  border-radius: 8px; /* Современный слегка скругленный квадрат вместо pill-формы */
  padding: 8px 20px;
  font-size: 0.9rem;
  font-weight: 500;
  border: 1px solid #111827;
  transition: all 0.3s ease;
  text-decoration: none;
}

.btn-accent:hover {
  background: #ffffff;
  color: #111827 !important;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

/* КНОПКА: ВЫЙТИ */
.btn-logout {
  background: transparent;
  color: #6b7280;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 8px 20px;
  font-size: 0.9rem;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-logout:hover {
  color: #ef4444;
  border-color: #fca5a5;
  background: #fef2f2;
}
</style>