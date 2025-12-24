<template>
  <div class="login-page d-flex justify-content-center align-items-center min-vh-100">
    <transition name="fade-up">
      <div class="login-card" v-show="mounted">
        <h2 class="title">Вход в аккаунт</h2>

        <form @submit.prevent="handleLogin" class="form d-flex flex-column gap-3">
          <input
            v-model="email"
            type="email"
            placeholder="Email"
            required
          />

          <input
            v-model="password"
            type="password"
            placeholder="Пароль"
            required
          />

          <button :disabled="loading">
            {{ loading ? 'Вход...' : 'Войти' }}
          </button>
        </form>

        <p class="bottom-text">
          Нет аккаунта? <router-link to="/register">Регистрация</router-link>
        </p>

        <!-- Toast -->
        <transition name="toast">
          <div v-if="toast.show" class="toast" :class="toast.type">
            {{ toast.message }}
          </div>
        </transition>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useStorage } from '@vueuse/core';
import { login } from '../auth';

const router = useRouter();
const email = ref('');
const password = ref('');
const loading = ref(false);
const mounted = ref(false);
const token = useStorage('token', null);

// Локальный toast
const toast = ref({
  show: false,
  message: '',
  type: '' // success / error
});

const showToast = (message, type = 'success') => {
  toast.value.message = message;
  toast.value.type = type;
  toast.value.show = true;
  setTimeout(() => (toast.value.show = false), 2000);
};

onMounted(() => mounted.value = true);

const handleLogin = async () => {
  if (!email.value || !password.value) return;
  loading.value = true;

  try {
    const { data } = await login({ email: email.value, password: password.value });
    token.value = data.token;
    showToast('Вход выполнен', 'success');
    router.push('/');
  } catch (err) {
    console.error(err);
    showToast(err.response?.data?.message || 'Ошибка при входе', 'error');
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.login-page {
  background: radial-gradient(circle at top, #1b1f27, #0f1218);
  width: 100%;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.login-card {
  background: linear-gradient(145deg, #1b1f27, #0f1218);
  padding: 3rem;
  border-radius: 22px;
  width: 400px;
  color: #e5e7eb;
  box-shadow: 0 15px 40px rgba(0,0,0,0.6);
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* Inputs */
input {
  background: #2a2c36;
  border: 1px solid #3b3f49;
  border-radius: 12px;
  padding: 0.75rem 1rem;
  color: #e5e7eb;
}
input::placeholder {
  color: #9ca3af;
}
input:focus {
  outline: none;
  box-shadow: 0 0 0 2px #2563eb;
  border-color: #2563eb;
  background: #1b1f27;
  color: #fff;
}

/* Button */
button {
  background: linear-gradient(135deg, #2563eb, #1e40af);
  border: none;
  color: #fff;
  padding: 0.75rem;
  border-radius: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}
button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(37, 99, 235, 0.45);
}
button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Toast */
.toast {
  position: absolute;
  top: -50px;
  right: 0;
  left: 0;
  margin: auto;
  padding: 0.5rem 1rem;
  border-radius: 12px;
  width: fit-content;
  min-width: 180px;
  text-align: center;
  font-weight: 600;
  color: #fff;
  z-index: 999;
  opacity: 0.95;
  background: #1b1f27;
  border-left: 4px solid transparent;
}
.toast.success {
  border-left-color: #2563eb;
}
.toast.error {
  border-left-color: #dc2626;
}

/* Toast анимация */
.toast-enter-active, .toast-leave-active {
  transition: all 0.3s ease;
}
.toast-enter-from {
  transform: translateY(-20px);
  opacity: 0;
}
.toast-enter-to {
  transform: translateY(0);
  opacity: 1;
}
.toast-leave-from {
  transform: translateY(0);
  opacity: 1;
}
.toast-leave-to {
  transform: translateY(-20px);
  opacity: 0;
}

/* Fade-up анимация формы */
.fade-up-enter-active, .fade-up-leave-active {
  transition: all 0.5s ease;
}
.fade-up-enter-from {
  transform: translateY(30px);
  opacity: 0;
}
.fade-up-enter-to {
  transform: translateY(0);
  opacity: 1;
}
.fade-up-leave-from {
  transform: translateY(0);
  opacity: 1;
}
.fade-up-leave-to {
  transform: translateY(30px);
  opacity: 0;
}

/* Текст */
.title {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 2rem;
  color: #3b82f6;
}
.bottom-text {
  margin-top: 1rem;
  font-size: 0.9rem;
  color: #9ca3af;
}
.bottom-text a {
  color: #2563eb;
  font-weight: 600;
}
</style>
