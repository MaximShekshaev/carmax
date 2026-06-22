<template>
  <div class="admin-login-page min-vh-100 d-flex align-items-center justify-content-center p-4">
    
    <div class="admin-login-card p-5">
      <h2 class="title">ADMIN PANEL</h2>
      <p class="subtitle">Вход для администратора</p>

      <form @submit.prevent="login" class="login-form">
        <div class="form-group mb-3">
          <label class="custom-label">Email</label>
          <input
            type="email"
            v-model="email"
            placeholder="admin@email.com"
            class="form-control light-input"
            required
          />
        </div>

        <div class="form-group mb-4">
          <label class="custom-label">Пароль</label>
          <input
            type="password"
            v-model="password"
            placeholder="••••••••"
            class="form-control light-input"
            required
          />
        </div>

        <div v-if="error" class="error-alert p-3 mb-3 d-flex align-items-center">
          <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
          <span>{{ error }}</span>
        </div>

        <button type="submit" class="btn btn-submit-admin w-100 py-3">
          Войти в систему
        </button>

        <div class="text-center mt-4">
          <button type="button" class="btn-back-link" @click="goHome">
            <i class="bi bi-arrow-left me-2"></i>Вернуться на главную
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { adminLogin } from '../../auth'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const error = ref('')
const router = useRouter()

const login = async () => {
  try {
    const res = await adminLogin({
      email: email.value,
      password: password.value
    })

    localStorage.setItem('admin_token', res.data.token)
    router.push('/admin')
  } catch {
    error.value = 'Неверный логин или пароль администратора'
  }
}

const goHome = () => {
  router.push('/')
}
</script>

<style scoped>
/* СВЕТЛЫЙ ДИЗАЙН СТРАНИЦЫ */
.admin-login-page {
  background-color: #f9fafb;
}

/* КАРТОЧКА */
.admin-login-card {
  width: 100%;
  max-width: 440px;
  background: #ffffff;
  border: 1px solid rgba(0, 0, 0, 0.05);
  border-radius: 16px;
  box-shadow: 0 10px 35px rgba(0, 0, 0, 0.03);
  animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* ЗАГОЛОВКИ */
.title {
  text-align: center;
  font-weight: 800;
  font-size: 1.6rem;
  letter-spacing: 1.5px;
  color: #111827;
  margin-bottom: 4px;
}

.subtitle {
  text-align: center;
  font-size: 0.9rem;
  color: #6b7280;
  margin-bottom: 35px;
}

/* ФОРМА И ЛАБЕЛЫ */
.custom-label {
  font-size: 0.8rem;
  font-weight: 600;
  color: #4b5563;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 6px;
  display: block;
}

/* МИНИМАЛИСТИЧНЫЕ ИНПУТЫ */
.light-input {
  border-radius: 8px;
  background-color: #f9fafb;
  color: #111827;
  border: 1px solid #e5e7eb;
  padding: 12px 16px;
  font-size: 0.95rem;
  transition: all 0.25s ease;
}

.light-input::placeholder {
  color: #9ca3af;
}

.light-input:focus {
  box-shadow: none;
  border-color: #111827;
  background-color: #ffffff;
  color: #111827;
}

/* ОСНОВНАЯ МОНОХРОМНАЯ КНОПКА */
.btn-submit-admin {
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  background: #111827;
  color: #ffffff;
  border: 1px solid #111827;
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.btn-submit-admin:hover {
  background: #ffffff;
  color: #111827;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
}

/* КНОПКА НАЗАД В ВИДЕ ССЫЛКИ */
.btn-back-link {
  background: none;
  border: none;
  font-size: 0.9rem;
  font-weight: 500;
  color: #6b7280;
  transition: color 0.2s ease, transform 0.2s ease;
  display: inline-flex;
  align-items: center;
}

.btn-back-link:hover {
  color: #111827;
}

.btn-back-link i {
  transition: transform 0.2s ease;
}

.btn-back-link:hover i {
  transform: translateX(-3px); /* Деликатный сдвиг стрелки назад */
}

/* СТИЛИЗАЦИЯ БЛОКА ОШИБКИ */
.error-alert {
  background-color: #fef2f2;
  border: 1px solid #fca5a5;
  border-radius: 8px;
  color: #991b1b;
  font-size: 0.88rem;
  font-weight: 500;
}

/* КИНЕМАТОГРАФИЧНАЯ ПЛАВНАЯ АНИМАЦИЯ ПОЯВЛЕНИЯ КАРТОЧКИ */
@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>