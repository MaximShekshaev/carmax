<template>
  <nav :class="['navbar', 'navbar-expand-lg', 'navbar-light', 'bg-white', 'py-3', { 'shadow-sm': isScrolled }]" ref="navbar">
    <div class="container d-flex justify-content-between align-items-center">

      <!-- Лого -->
      <router-link class="navbar-brand fw-bold fs-3 text-primary" to="/">
        CAR<span class="text-dark">MAX</span>
      </router-link>

      <!-- Навигация -->
      <div class="d-flex align-items-center gap-3">

        <!-- Общие ссылки -->
        <router-link class="nav-link text-dark hover-primary" to="/">Главная</router-link>

        <!-- Для неавторизованных -->
        <template v-if="!isAuthenticated">
          <router-link class="nav-link text-dark hover-primary" to="/login">Вход</router-link>
          <router-link class="btn btn-primary px-3 py-2 rounded" to="/register">Регистрация</router-link>
        </template>

        <!-- Для авторизованных -->
        <template v-else>
          <router-link class="nav-link text-dark hover-primary" to="/my-rentals">Моя аренда</router-link>
          <button class="btn btn-danger px-3 py-2 rounded" @click="handleLogout">Выйти</button>
        </template>

      </div>

    </div>
  </nav>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from "vue";
import { useStorage } from "@vueuse/core"; 
import { logout } from "../auth";
import { useRouter } from "vue-router";

const router = useRouter();
const token = useStorage("token", null);
const isAuthenticated = computed(() => !!token.value);

const handleLogout = async () => {
  try {
    if (token.value) await logout(token.value);
  } catch (err) {
    console.error(err);
  } finally {
    token.value = null;
    router.push("/");
  }
};

const isScrolled = ref(false);
const handleScroll = () => {
  isScrolled.value = window.scrollY > 20; 
};

onMounted(() => window.addEventListener('scroll', handleScroll));
onUnmounted(() => window.removeEventListener('scroll', handleScroll));
</script>

<style scoped>
.navbar {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 999;
  transition: box-shadow 0.3s, background 0.3s;
  background: #fff;
}

.navbar.shadow-sm {
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.navbar .nav-link {
  font-weight: 500;
  transition: color 0.2s;
}

.navbar .nav-link.hover-primary:hover {
  color: #2575fc;
}

.navbar .btn-primary,
.navbar .btn-danger {
  font-weight: 600;
  transition: transform 0.2s, background 0.2s;
}

.navbar .btn-primary:hover {
  background-color: #1f5bcc;
  transform: translateY(-1px);
}

.navbar .btn-danger:hover {
  background-color: #dc3545;
  color: #fff;
  transform: translateY(-1px);
}
</style>
