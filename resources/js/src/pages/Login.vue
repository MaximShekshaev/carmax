<template>
  <div class="login-page d-flex justify-content-center align-items-center min-vh-100">
    
    <!-- Toast сообщение -->
    <div 
      v-if="message.text" 
      class="toast-message" 
      :class="message.type" 
      data-aos="fade-down"
    >
      {{ message.text }}
    </div>

    <div class="card shadow-lg p-5 rounded-4" style="width: 400px;">
      <h2 class="text-center mb-4 fw-bold text-primary">Вход в аккаунт</h2>

      <form @submit.prevent="handleLogin" class="d-flex flex-column gap-3">
        <input v-model="email" type="email" placeholder="Email" required class="form-control form-control-lg" />
        <input v-model="password" type="password" placeholder="Пароль" required class="form-control form-control-lg" />

        <button type="submit" class="btn btn-primary btn-lg mt-2" :disabled="loading">
          {{ loading ? "Вход..." : "Войти" }}
        </button>
      </form>

      <p class="text-center mt-3 text-light">
        Нет аккаунта? 
        <router-link to="/register" class="text-primary fw-bold">Регистрация</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { login } from "../auth";
import { useRouter } from "vue-router";
import { useStorage } from "@vueuse/core";
import AOS from "aos";
import "aos/dist/aos.css";

const email = ref("");
const password = ref("");
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


const handleLogin = async () => {
  loading.value = true;
  try {
    const res = await login({
      email: email.value,
      password: password.value,
    });

    token.value = res.data.token;

    showMessage("Вход выполнен! Добро пожаловать!", "success");
    setTimeout(() => router.push("/"), 1000);
  } catch (err) {
    showMessage(err.response?.data?.message || "Ошибка при входе", "error");
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.login-page {
  background: radial-gradient(circle at top, #1b1f27, #0f1218);
  position: relative;
}


.toast-message {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  padding: 12px 24px;
  border-radius: 12px;
  font-weight: 600;
  z-index: 9999;
  min-width: 250px;
  text-align: center;
  color: #fff;
  box-shadow: 0 4px 15px rgba(0,0,0,0.5);
  pointer-events: none;
  background: linear-gradient(135deg, #1b1f27, #0f1218);
}

.toast-message.success {
  border-left: 4px solid #2563eb;
}

.toast-message.error {
  border-left: 4px solid #dc3545;
}


.card {
  background: linear-gradient(160deg, #1b1f27, #0f1218);
  color: #e5e7eb;
  transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6);
}

input.form-control {
  border-radius: 0.75rem;
  background: #0f1218;
  color: #e5e7eb;
  border: 1px solid #3b3f49;
  transition: box-shadow 0.2s, border-color 0.2s;
}

input.form-control::placeholder {
  color: #9ca3af;
}

input.form-control:focus {
  box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.25);
  border-color: #2563eb;
  background: #1b1f27;
  color: #fff;
}

button.btn-primary {
  border-radius: 0.75rem;
  font-weight: 600;
  background: linear-gradient(135deg, #2563eb, #1e40af);
  color: #fff;
  transition: background 0.3s, transform 0.2s;
}

button.btn-primary:hover {
  background: linear-gradient(135deg, #1e40af, #2563eb);
  transform: translateY(-2px);
}

p.text-light a {
  color: #2563eb;
}
</style>
