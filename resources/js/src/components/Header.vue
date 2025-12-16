<template>
  <nav class="navbar navbar-expand bg-white shadow-sm py-3">
    <div class="container d-flex align-items-center justify-content-between">
      
      <!-- Лого -->
      <router-link class="navbar-brand fw-bold fs-3 text-primary" to="/">
        CAR<span class="text-dark">MAX</span>
      </router-link>

      <!-- Навигация -->
      <ul class="navbar-nav d-flex flex-row gap-4 fs-5">
        <li class="nav-item">
          <router-link class="nav-link text-dark hover-primary" to="/">Главная</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link text-dark hover-primary" to="/myrent">Моя аренда</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link text-dark hover-primary" to="/login">Админ</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link text-dark hover-primary" to="/login">Вход</router-link>
        </li>
        <li class="nav-item">
          <router-link class="btn btn-primary px-3 py-2" to="/register">Регистрация</router-link>
        </li>
        
      </ul>

    </div>
  </nav>
</template>

<script setup>
</script>

<style scoped>
header {
  position: sticky;
  top: 0;
  z-index: 50;
}
</style>
