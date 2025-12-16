<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container d-flex justify-content-between align-items-center">

      <!-- Лого -->
      <router-link class="navbar-brand fw-bold fs-3 text-primary" to="/">
        CAR<span class="text-dark">MAX</span>
      </router-link>

      <!-- Навигация -->
      <ul class="navbar-nav d-flex flex-row gap-3 align-items-center mb-0">
        <li class="nav-item">
          <router-link class="nav-link text-dark hover-primary" to="/">Главная</router-link>
        </li>
       

        <!-- Для неавторизованных -->
        <template v-if="!isAuthenticated">
          <li class="nav-item">
            <router-link class="nav-link text-dark hover-primary" to="/login">Вход</router-link>
          </li>
          <li class="nav-item">
            <router-link class="btn btn-primary px-3 py-2 rounded" to="/register">Регистрация</router-link>
          </li>
        </template>

        <!-- Для авторизованных -->
        <li class="nav-item" v-else>
          <button class="btn btn-danger px-3 py-2 rounded" @click="handleLogout">Выйти</button>
        </li>
      </ul>

    </div>
  </nav>
</template>

<script setup>
import { computed } from "vue";
import { useStorage } from "@vueuse/core"; 
import { logout } from "../auth";
import { useRouter } from "vue-router";

const router = useRouter();
const token = useStorage("token", null);

const isAuthenticated = computed(() => !!token.value);

const handleLogout = async () => {
  try {
    if (token.value) {
      await logout(token.value); 
    }
  } catch (err) {
    console.error(err); 
  } finally {
    token.value = null; 
    router.push("/"); 
  }
};
</script>

<style scoped>
.navbar {
  transition: box-shadow 0.3s, background 0.3s;
}

.navbar .nav-link {
  font-weight: 500;
  transition: color 0.2s;
}

.navbar .nav-link.hover-primary:hover {
  color: #2575fc;
}

.navbar .btn-primary {
  font-weight: 600;
  transition: transform 0.2s, background 0.2s;
}

.navbar .btn-primary:hover {
  background-color: #1f5bcc;
  transform: translateY(-1px);
}

.navbar .btn-outline-danger {
  font-weight: 600;
  transition: transform 0.2s, background 0.2s, color 0.2s;
}

.navbar .btn-outline-danger:hover {
  background-color: #dc3545;
  color: #fff;
  transform: translateY(-1px);
}



</style>
