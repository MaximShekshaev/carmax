<template>
  <nav
    :class="['navbar', 'navbar-expand-lg', { scrolled: isScrolled }]"
  >
    <div class="container d-flex justify-content-between align-items-center">

      <!-- ЛОГО -->
      <router-link class="navbar-brand" to="/">
        CAR<span>MAX</span>
      </router-link>

      <!-- НАВИГАЦИЯ -->
      <div class="d-flex align-items-center gap-4">

        <router-link class="nav-link" to="/">Главная</router-link>
        <router-link class="nav-link" to="/admin/login">Админ</router-link>
        <!-- ГОСТЬ -->
        <template v-if="!isAuthenticated">
          <router-link class="nav-link " to="/login">Вход</router-link>
          <router-link class="btn btn-accent" to="/register">
            Регистрация
          </router-link>
        </template>

        <!-- АВТОРИЗОВАН -->
        <template v-else>
          <router-link class="nav-link" to="/my-rentals">
            Моя аренда
          </router-link>
          <button class="btn btn-outline-danger" @click="handleLogout">
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
  isScrolled.value = window.scrollY > 30
}

onMounted(() => window.addEventListener('scroll', onScroll))
onUnmounted(() => window.removeEventListener('scroll', onScroll))
</script>

<style scoped>

.navbar {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1000;
  padding: 18px 0;
  background: rgba(13, 15, 20, 0.9);
  backdrop-filter: blur(12px);
  transition: all 0.35s ease;
}

/* ПРИ СКРОЛЛЕ */
.navbar.scrolled {
  background: rgba(10, 12, 18, 0.95);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
}

/* ЛОГО */
.navbar-brand {
  font-size: 1.6rem;
  font-weight: 900;
  letter-spacing: 1px;
  color: #2563eb;
  text-transform: uppercase;
}

.navbar-brand span {
  color: #ffffff;
}

/* ССЫЛКИ */
.nav-link {
  color: #e5e7eb;
  font-weight: 500;
  position: relative;
  transition: color 0.3s;
}

.nav-link:hover {
  color: #3b82f6;
}

.nav-link::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: -6px;
  width: 0;
  height: 2px;
  background: #3b82f6;
  transition: width 0.3s ease;
}

.nav-link:hover::after {
  width: 100%;
}

.nav-link.muted {
  color: #9ca3af;
}

/* КНОПКИ */
.btn-accent {
  background: linear-gradient(135deg, #2563eb, #1e40af);
  color: #fff;
  border-radius: 999px;
  padding: 8px 22px;
  font-weight: 600;
  border: none;
  transition: all 0.3s ease;
}

.btn-accent:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(37, 99, 235, 0.45);
}

.btn-outline-danger {
  border-radius: 999px;
  padding: 8px 20px;
  font-weight: 600;
  background: transparent;
  border: 1px solid #ef4444;
  color: #ef4444;
  transition: all 0.3s ease;
}

.btn-outline-danger:hover {
  background: #ef4444;
  color: #fff;
  box-shadow: 0 10px 30px rgba(239, 68, 68, 0.35);
}
</style>
