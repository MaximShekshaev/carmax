<template>
  <div class="admin-login-page">
    <div class="admin-login-card">
      <h2 class="title">ADMIN PANEL</h2>
      <p class="subtitle">Вход для администратора</p>

      <form @submit.prevent="login" class="login-form">
        <div class="form-group">
          <label>Email</label>
          <input
            type="email"
            v-model="email"
            placeholder="admin@email.com"
            required
          />
        </div>

        <div class="form-group">
          <label>Пароль</label>
          <input
            type="password"
            v-model="password"
            placeholder="••••••••"
            required
          />
        </div>


        
        <button type="submit" class="btn-login">
          Войти
        </button>

         <button type="button" class="btn-login" @click="goHome">
          Назад
        </button>

        <p v-if="error" class="error-message">
          {{ error }}
        </p>
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
    error.value = 'Неверный логин или пароль'
  }
}

const goHome = () => {
  router.push('/')
}
</script>

<style scoped>
.admin-login-page {
  min-height: 100vh;
  background: radial-gradient(circle at top, #111827, #020617);
  display: flex;
  align-items: center;
  justify-content: center;
}


.admin-login-card {
  width: 100%;
  max-width: 420px;
  background: linear-gradient(160deg, #1b1f27, #0b0f16);
  border-radius: 22px;
  padding: 42px 36px;
  box-shadow:
    0 30px 80px rgba(0, 0, 0, 0.8),
    inset 0 1px 0 rgba(255, 255, 255, 0.05);
  animation: fadeUp 0.6s ease;
}


.title {
  text-align: center;
  font-weight: 800;
  letter-spacing: 2px;
  color: #ffffff;
  margin-bottom: 6px;
}

.subtitle {
  text-align: center;
  font-size: 0.9rem;
  color: #9ca3af;
  margin-bottom: 32px;
}


.login-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-group label {
  font-size: 0.85rem;
  font-weight: 600;
  color: #9ca3af;
  margin-bottom: 6px;
  display: block;
}

.form-group input {
  width: 100%;
  padding: 14px 16px;
  border-radius: 14px;
  background: #020617;
  border: 1px solid rgba(255, 255, 255, 0.08);
  color: #ffffff;
  outline: none;
  transition: border 0.3s, box-shadow 0.3s;
}

.form-group input::placeholder {
  color: #6b7280;
}

.form-group input:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.25);
}

/* КНОПКА */
.btn-login {
  margin-top: 10px;
  padding: 14px;
  border-radius: 16px;
  border: none;
  background: linear-gradient(135deg, #2563eb, #1e40af);
  color: #ffffff;
  font-weight: 700;
  letter-spacing: 1px;
  cursor: pointer;
  transition: transform 0.3s, box-shadow 0.3s;
}

.btn-login:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 40px rgba(37, 99, 235, 0.45);
}

/* ОШИБКА */
.error-message {
  margin-top: 10px;
  text-align: center;
  color: #ef4444;
  font-weight: 600;
}

/* АНИМАЦИЯ */
@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(25px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
