<template>
  <div class="register-page d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="card shadow-lg p-5 rounded-4" style="width: 400px;">
      <h2 class="text-center mb-4 fw-bold text-primary">Регистрация</h2>
      <form @submit.prevent="submit" class="d-flex flex-column gap-3">
        <input v-model="name" type="text" placeholder="Имя" required class="form-control form-control-lg" />
        <input v-model="email" type="email" placeholder="Email" required class="form-control form-control-lg" />
        <input v-model="password" type="password" placeholder="Пароль" required class="form-control form-control-lg" />
        <input v-model="password_confirmation" type="password" placeholder="Подтвердите пароль" required class="form-control form-control-lg" />
        <button type="submit" class="btn btn-primary btn-lg mt-2">Зарегистрироваться</button>
      </form>
      <p class="text-center mt-3">
        Уже есть аккаунт? 
        <router-link to="/login" class="text-primary fw-bold">Войти</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { register, login } from "../auth";
import { useRouter } from "vue-router";
import { useStorage } from "@vueuse/core";

const name = ref("");
const email = ref("");
const password = ref("");
const password_confirmation = ref("");

const router = useRouter();
const token = useStorage("token", null);

const submit = async () => {
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
    
    router.push("/"); 
  } catch (err) {
    alert(err.response?.data?.message || "Ошибка регистрации или входа");
  }
};
</script>

<style scoped>
.register-page {
  background-color: #f8f9fa; /* светлый фон страницы */
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
