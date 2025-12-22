<template>
  <div class="login-page d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="card shadow-lg p-5 rounded-4" style="width: 400px;">
      <h2 class="text-center mb-4 fw-bold text-primary">Вход в аккаунт</h2>
      <form @submit.prevent="handleLogin" class="d-flex flex-column gap-3">
        <div>
          <input
            v-model="email"
            type="email"
            placeholder="Email"
            required
            class="form-control form-control-lg"
          />
        </div>
        <div>
          <input
            v-model="password"
            type="password"
            placeholder="Пароль"
            required
            class="form-control form-control-lg"
          />
        </div>
        <button type="submit" class="btn btn-primary btn-lg mt-2" :disabled="loading">
          {{ loading ? "Вход..." : "Войти" }}
        </button>
      </form>
      <p class="text-center mt-3">
        Нет аккаунта? 
        <router-link to="/register" class="text-primary fw-bold">Зарегистрироваться</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useStorage } from "@vueuse/core";
import Swal from "sweetalert2";
import 'sweetalert2/dist/sweetalert2.min.css';
import { login } from "../auth";

const router = useRouter();
const email = ref("");
const password = ref("");
const loading = ref(false);
const token = useStorage("token", null);

const handleLogin = async () => {
  if (!email.value || !password.value) return;

  loading.value = true;
  try {
    const { data } = await login({ email: email.value, password: password.value });
    token.value = data.token;

    await Swal.fire({
      icon: "success",
      title: "Вход выполнен",
      text: "Добро пожаловать!",
      showConfirmButton: false,
      timer: 1500,
      timerProgressBar: true,
      background: "#f0f4ff",
      color: "#1f5bcc"
    });

    router.push("/");
  } catch (err) {
    console.error(err);
    Swal.fire({
      icon: "error",
      title: "Ошибка",
      text: err.response?.data?.message || "Ошибка при входе",
      confirmButtonColor: "#1f5bcc",
      background: "#fff0f0",
      color: "#dc3545"
    });
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.login-page {
  background-color: #f8f9fa;
}

.card {
  background-color: #ffffff;
  transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

input.form-control {
  border-radius: 0.75rem;
  transition: box-shadow 0.2s, border-color 0.2s;
}

input.form-control:focus {
  box-shadow: 0 0 0 0.25rem rgba(37, 117, 252, 0.25);
  border-color: #2575fc;
}

button.btn-primary {
  border-radius: 0.75rem;
  font-weight: 600;
  transition: background 0.3s, transform 0.2s;
}

button.btn-primary:hover {
  background: #1f5bcc;
  transform: translateY(-2px);
}
</style>
