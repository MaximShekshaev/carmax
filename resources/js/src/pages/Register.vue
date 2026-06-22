<template>
  <div class="register-page d-flex justify-content-center align-items-center min-vh-100">
    
    <!-- Toast сообщение (Минималистичное всплывающее окно) -->
    <div 
      v-if="message.text" 
      class="toast-message animate__animated animate__fadeInDown" 
      :class="message.type"
    >
      <i v-if="message.type === 'success'" class="bi bi-check-circle-fill text-success me-2"></i>
      <i v-else class="bi bi-exclamation-circle-fill text-danger me-2"></i>
      {{ message.text }}
    </div>

    <!-- КАРТОЧКА РЕГИСТРАЦИИ -->
    <div class="card p-5" style="width: 420px;">
      <h2 class="text-center mb-4 register-title">Регистрация</h2>
      
      <form @submit.prevent="submit" class="d-flex flex-column gap-3">
        <input v-model="name" type="text" placeholder="Имя" required class="form-control form-control-lg" />
        <input v-model="email" type="email" placeholder="Email" required class="form-control form-control-lg" />
        <input v-model="password" type="password" placeholder="Пароль" required class="form-control form-control-lg" />
        <input v-model="password_confirmation" type="password" placeholder="Подтвердите пароль" required class="form-control form-control-lg" />
        
        <button type="submit" class="btn btn-submit btn-lg mt-2" :disabled="loading">
          {{ loading ? "Регистрация..." : "Зарегистрироваться" }}
        </button>
      </form>
      
      <p class="text-center mt-4 login-redirect mb-0">
        Уже есть аккаунт? 
        <router-link to="/login" class="login-link">Войти</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { register, login } from "../auth";
import { useRouter } from "vue-router";
import { useStorage } from "@vueuse/core";
import AOS from "aos";
import "aos/dist/aos.css";

const name = ref("");
const email = ref("");
const password = ref("");
const password_confirmation = ref("");
const loading = ref(false);

const message = ref({ text: "", type: "" });

const router = useRouter();
const token = useStorage("token", null);

const showMessage = (text, type = "success") => {
  message.value = { text, type };
  setTimeout(() => {
    message.value.text = "";
  }, 3000);
};

onMounted(() => {
  AOS.init({ duration: 600, once: true });
});

const submit = async () => {
  loading.value = true;
  try {
    await register({
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    });

    const res = await login({
      email: email.value,
      password: password.value,
    });

    token.value = res.data.token;

    showMessage("Регистрация успешна! Добро пожаловать!", "success");
    setTimeout(() => router.push("/"), 1000);
  } catch (err) {
    showMessage(err.response?.data?.message || "Ошибка регистрации или входа", "error");
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* СВЕТЛЫЙ ФОН СТРАНИЦЫ */
.register-page {
  background-color: #f9fafb;
  position: relative;
}

/* ЗАГОЛОВОК */
.register-title {
  font-size: 1.6rem;
  font-weight: 700;
  color: #111827;
  letter-spacing: -0.5px;
}

/* ТРЕНДОВЫЕ ТОСТЫ */
.toast-message {
  position: fixed;
  top: 30px;
  left: 50%;
  transform: translateX(-50%);
  padding: 14px 28px;
  border-radius: 10px;
  font-weight: 500;
  font-size: 0.95rem;
  z-index: 9999;
  min-width: 300px;
  text-align: center;
  color: #111827;
  background: #ffffff;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  border: 1px solid rgba(0, 0, 0, 0.05);
  display: flex;
  align-items: center;
  justify-content: center;
  pointer-events: none;
}

.toast-message.success {
  border-bottom: 3px solid #10b981;
}

.toast-message.error {
  border-bottom: 3px solid #ef4444;
}

/* КАРТОЧКА ФОРМЫ */
.card {
  background: #ffffff;
  border: 1px solid rgba(0, 0, 0, 0.05);
  border-radius: 16px;
  box-shadow: 0 10px 35px rgba(0, 0, 0, 0.03);
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 15px 45px rgba(0, 0, 0, 0.06);
  border-color: rgba(17, 24, 39, 0.1);
}

/* МИНИМАЛИСТИЧНЫЕ ИНПУТЫ */
input.form-control {
  border-radius: 8px;
  background-color: #f9fafb;
  color: #111827;
  border: 1px solid #e5e7eb;
  font-size: 0.95rem;
  padding: 12px 16px;
  transition: all 0.25s ease;
}

input.form-control::placeholder {
  color: #9ca3af;
}

input.form-control:focus {
  box-shadow: none;
  border-color: #111827;
  background-color: #ffffff;
  color: #111827;
}

/* КНОПКА ОТПРАВКИ */
.btn-submit {
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 500;
  background: #111827;
  color: #ffffff;
  border: 1px solid #111827;
  padding: 12px;
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.btn-submit:hover:not([disabled]) {
  background: #ffffff;
  color: #111827;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
}

.btn-submit:disabled {
  background: #f3f4f6;
  border-color: #e5e7eb;
  color: #9ca3af;
  cursor: not-allowed;
}

/* ССЫЛКА НА ВХОД */
.login-redirect {
  font-size: 0.9rem;
  color: #6b7280;
}

.login-link {
  color: #111827;
  font-weight: 600;
  text-decoration: underline;
  text-underline-offset: 4px;
  transition: color 0.2s;
}

.login-link:hover {
  color: #4b5563;
}
</style>