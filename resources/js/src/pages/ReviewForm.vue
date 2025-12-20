<template>
  <form class="review-form" @submit.prevent="submitReview">

    <h4 class="form-title mb-4 text-center">
      Оставить отзыв
    </h4>

    <!-- Имя -->
    <div class="form-group mb-3">
      <input
        v-model="form.name"
        type="text"
        class="form-control custom-input"
        placeholder="Ваше имя"
        required
      />
    </div>

    <!-- Сообщение -->
    <div class="form-group mb-3">
      <textarea
        v-model="form.message"
        class="form-control custom-input"
        placeholder="Ваш отзыв"
        rows="4"
        required
      ></textarea>
    </div>

    <!-- Рейтинг -->
    <div class="rating mb-4 text-center">
      <i
        v-for="star in 5"
        :key="star"
        class="bi"
        :class="star <= form.rating ? 'bi-star-fill active' : 'bi-star'"
        @click="form.rating = star"
      ></i>
    </div>

    <button
      class="btn btn-primary w-100 submit-btn"
      :disabled="loading"
    >
      {{ loading ? 'Отправка...' : 'Отправить отзыв' }}
    </button>

    <p v-if="success" class="text-success text-center mt-3">
      Спасибо! Ваш отзыв отправлен.
    </p>

  </form>
</template>

<script setup>
import { reactive, ref, defineEmits } from 'vue'
import api from '../services/api'

const emit = defineEmits(['submitted'])

const form = reactive({
  name: '',
  message: '',
  rating: 0
})

const loading = ref(false)
const success = ref(false)

const submitReview = async () => {
  if (!form.rating) return alert('Поставьте оценку')

  loading.value = true
  success.value = false

  try {
    await api.post('/reviews', form)

    form.name = ''
    form.message = ''
    form.rating = 0

    success.value = true
    emit('submitted')
  } catch (e) {
    alert('Ошибка отправки')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* Фон формы */
.review-form {
  position: relative;
  max-width: 420px;
  margin: auto;
  padding: 36px 32px;
  border-radius: 20px;

  background: linear-gradient(145deg, #ffffff, #f3f4f6);

  box-shadow:
    0 15px 35px rgba(0, 0, 0, 0.08),
    inset 0 1px 0 rgba(255, 255, 255, 0.9);
}

/* Акцент сверху */
.review-form::before {
  content: "";
  position: absolute;
  top: 0;
  left: 24px;
  right: 24px;
  height: 4px;
  border-radius: 0 0 10px 10px;
  background: linear-gradient(90deg, #0d6efd, #4dabf7);
}

/* Заголовок */
.form-title {
  font-weight: 600;
  color: #212529;
}

/* Инпуты */
.custom-input {
  border-radius: 12px;
  padding: 12px 14px;
  border: 1px solid #e0e3e7;
  background: #fff;
  transition: all 0.25s ease;
}

.custom-input:focus {
  border-color: #0d6efd;
  box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
}

/* Рейтинг */
.rating i {
  font-size: 28px;
  margin: 0 5px;
  cursor: pointer;
  color: #ced4da;
  transition: transform 0.2s ease, color 0.2s ease;
}

.rating i:hover {
  transform: scale(1.15);
}

.rating i.active {
  color: #ffc107;
}

/* Кнопка */
.submit-btn {
  padding: 12px;
  border-radius: 12px;
  font-weight: 600;
  box-shadow: 0 10px 25px rgba(13, 110, 253, 0.25);
}

.submit-btn:disabled {
  opacity: 0.7;
}
</style>
