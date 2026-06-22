<template>
  <div class="admin-layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">

      <div>
        <div class="sidebar-brand">
          <span class="brand-main">CARMAX</span>
          <span class="brand-badge">ADMIN</span>
        </div>

        <nav class="sidebar-menu">

          <router-link to="/admin/cars" class="menu-item">
            <i class="bi bi-car-front me-2"></i>
            Машины
          </router-link>

          <router-link to="/admin/users" class="menu-item">
            <i class="bi bi-people me-2"></i>
            Пользователи
          </router-link>

          <router-link to="/admin/reviews" class="menu-item">
            <i class="bi bi-chat-square-text me-2"></i>
            Отзывы
          </router-link>

          <router-link to="/admin/rental-conditions" class="menu-item">
            <i class="bi bi-file-earmark-text me-2"></i>
            Условия аренды
          </router-link>

          <router-link to="/admin/faqs" class="menu-item">
            <i class="bi bi-question-circle me-2"></i>
            FAQ
          </router-link>

        </nav>
      </div>

      <div class="sidebar-footer">
        <button class="btn-logout-sidebar" @click="logout">
          <i class="bi bi-box-arrow-left me-2"></i>
          Выйти
        </button>
      </div>

    </aside>

    <!-- CONTENT -->
    <main class="content-wrapper">
      <div class="content-inner animate__animated animate__fadeIn">
        <router-view />
      </div>
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

/* 🌐 BASE */
.admin-layout {
  display: flex;
  min-height: 100vh;
  background: #f4f6fb;
  font-family: system-ui, -apple-system, sans-serif;
}

/* 🧊 SIDEBAR PREMIUM GLASS */
.sidebar {
  width: 270px;
  padding: 32px 22px;
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;

  background: rgba(255, 255, 255, 0.75);
  backdrop-filter: blur(16px);
  border-right: 1px solid rgba(0,0,0,0.06);
}

/* BRAND */
.sidebar-brand {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 40px;
}

.brand-main {
  font-size: 18px;
  font-weight: 800;
  letter-spacing: -0.5px;
  color: #111827;
}

.brand-badge {
  font-size: 10px;
  font-weight: 700;
  padding: 3px 6px;
  border-radius: 6px;
  background: #111827;
  color: white;
}

/* MENU */
.sidebar-menu {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.menu-item {
  position: relative;
  display: flex;
  align-items: center;
  gap: 10px;

  padding: 12px 14px;
  border-radius: 10px;

  font-size: 14px;
  font-weight: 500;
  color: #4b5563;

  text-decoration: none;
  transition: 0.25s ease;
}

/* hover glow */
.menu-item:hover {
  background: white;
  color: #111827;
  box-shadow: 0 10px 25px rgba(0,0,0,0.06);
  transform: translateX(3px);
}

/* ACTIVE STATE */
.menu-item.router-link-active {
  background: #111827;
  color: white;
  box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.menu-item.router-link-active::before {
  content: '';
  position: absolute;
  left: -10px;
  top: 10px;
  bottom: 10px;
  width: 4px;
  border-radius: 10px;
  background: #111827;
}

/* ICON */
.menu-item i {
  font-size: 16px;
  opacity: 0.8;
}

/* FOOTER */
.sidebar-footer {
  margin-top: 40px;
}

/* LOGOUT */
.btn-logout-sidebar {
  width: 100%;
  padding: 12px;
  border-radius: 10px;

  background: transparent;
  border: 1px solid rgba(0,0,0,0.08);

  font-size: 14px;
  color: #6b7280;

  transition: 0.2s;
}

.btn-logout-sidebar:hover {
  background: #fee2e2;
  border-color: #fecaca;
  color: #ef4444;
}

/* CONTENT */
.content-wrapper {
  flex: 1;
  margin-left: 270px;
  padding: 40px;
}

.content-inner {
  max-width: 1300px;
  margin: 0 auto;
}

/* 📱 RESPONSIVE */
@media (max-width: 768px) {
  .admin-layout {
    flex-direction: column;
  }

  .sidebar {
    position: relative;
    width: 100%;
  }

  .content-wrapper {
    margin-left: 0;
    padding: 20px;
  }
}

</style>