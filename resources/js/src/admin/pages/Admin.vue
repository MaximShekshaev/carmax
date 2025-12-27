<template>
  <div class="admin-layout">
    <aside class="sidebar">
      <h3>CARMAX ADMIN</h3>
      <router-link to="/admin/cars">Машины</router-link>
      <router-link to="/admin/users">Пользователи</router-link>
      <router-link to="/admin/reviews">Отзывы</router-link>
      <router-link to="/admin/rental-conditions">Условия аренды</router-link>
      <router-link to="/admin/faqs">FAQ</router-link>
      <button class="logout" @click="logout">Выйти</button>
    </aside>

    <main class="content">
      <router-view />
    </main>
  </div>
</template>


<script setup>
import { useRouter } from 'vue-router'



const router = useRouter()

const logout = () => {
  localStorage.removeItem('admin_token')
  router.push('/')
}
</script>

<style scoped>
.admin-layout {
  display: flex;
  min-height: 100vh;
}

.sidebar {
  width: 220px;
  background: #111;
  color: white;
  padding: 20px;
}

.sidebar a {
  display: block;
  color: white;
  margin: 10px 0;
  text-decoration: none;
}

.logout {
  margin-top: 20px;
  width: 100%;
  padding: 8px;
  background: #e74c3c;
  border: none;
  color: white;
  cursor: pointer;
  border-radius: 4px;
}

.content {
  flex: 1;        
  padding: 20px;  
}

</style>
