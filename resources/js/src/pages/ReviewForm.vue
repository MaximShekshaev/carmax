<template>
  <!-- Анимация появления формы -->
  <transition name="form-fade-slide">
    <div class="review-form-card">
      <h4 class="form-title mb-4 text-center">Оставить отзыв</h4>

      <form @submit.prevent="submitReview">
        <!-- Поле: Имя -->
        <div class="mb-3">
          <label class="form-label custom-label">Ваше имя</label>
          <input
            v-model="form.name"
            type="text"
            class="form-control light-input"
            placeholder="Введите ваше имя"
            required
          />
        </div>

        <!-- Поле: Интерактивный рейтинг -->
        <div class="mb-3">
          <label class="form-label custom-label">Оценка сервиса</label>
          <div class="rating-container">
            <span
              v-for="i in 5"
              :key="i"
              @click="form.rating = i"
              :class="{ active: i <= form.rating }"
              class="rating-star"
            >
              ★
            </span>
          </div>
        </div>

        <!-- Поле: Сообщение -->
        <div class="mb-4">
          <label class="form-label custom-label">Ваш отзыв</label>
          <textarea
            v-model="form.message"
            rows="4"
            class="form-control light-input"
            placeholder="Поделитесь вашими впечатлениями от поездки..."
            required
          ></textarea>
        </div>

        <!-- Кнопка отправки -->
        <div class="text-center">
          <button class="btn btn-submit-review px-5 py-2.5">
            Отправить отзыв
          </button>
        </div>
      </form>
    </div>
  </transition>

  <!-- Кастомный Toast (Минималистичный стиль сверху) -->
  <transition name="toast-fade">
    <div v-if="showToast" class="toast-success-badge">
      <i class="bi bi-check-circle-fill text-success me-2"></i>
      Отзыв успешно опубликован
    </div>
  </transition>
</template>

<script setup>
import { reactive, ref } from 'vue'
import api from '../services/api'

const emit = defineEmits(['submitted'])

const form = reactive({
  name: '',
  rating: 5,
  message: ''
})

const showToast = ref(false)

const submitReview = async () => {
  try {
    await api.post('/reviews', form)

    emit('submitted')

    form.name = ''
    form.rating = 5
    form.message = ''

    showToast.value = true
    setTimeout(() => (showToast.value = false), 2500)

  } catch (e) {
    console.error('Ошибка отправки отзыва', e)
  }
}
</script>

<style scoped>
/* КАРТОЧКА ФОРМЫ (Светлый премиальный дизайн) */
.review-form-card {
  max-width: 500px;
  margin: 0 auto;
  padding: 40px 35px;
  border-radius: 16px;
  background: #ffffff;
  border: 1px solid rgba(0, 0, 0, 0.05);
  box-shadow: 0 10px 35px rgba(0, 0, 0, 0.03);
  color: #111827;
}

.form-title {
  font-size: 1.4rem;
  font-weight: 700;
  letter-spacing: -0.5px;
  color: #111827;
}

/* СТИЛИЗАЦИЯ ЛАБЕЛОВ И ИНПУТОВ */
.custom-label {
  font-size: 0.8rem;
  font-weight: 600;
  color: #4b5563;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 6px;
}

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
}

/* ИНТЕРАКТИВНЫЙ РЕЙТИНГ (ЗВЕЗДЫ) */
.rating-container {
  display: flex;
  gap: 6px;
  font-size: 28px;
  line-height: 1;
}

.rating-star {
  color: #e5e7eb;
  cursor: pointer;
  transition: transform 0.2s ease, color 0.2s ease;
  user-select: none;
}

.rating-star.active {
  color: #f59e0b; /* Элегантный янтарный оттенок */
}

.rating-star:hover {
  transform: scale(1.15);
}

/* КНОПКА ОТПРАВКИ ФОРМЫ */
.btn-submit-review {
  border-radius: 8px;
  font-size: 0.95rem;
  font-weight: 600;
  background: #111827;
  color: #ffffff;
  border: 1px solid #111827;
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.btn-submit-review:hover {
  background: #ffffff;
  color: #111827;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
  transform: translateY(-1px);
}

/* ТОСТ УВЕДОМЛЕНИЯ (Минималистичный, всплывает сверху) */
.toast-success-badge {
  position: fixed;
  top: 30px;
  left: 50%;
  transform: translateX(-50%);
  padding: 12px 24px;
  border-radius: 10px;
  font-weight: 500;
  font-size: 0.9rem;
  z-index: 9999;
  background: #ffffff;
  color: #111827;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
  border: 1px solid rgba(0, 0, 0, 0.05);
  border-bottom: 3px solid #10b981;
  display: flex;
  align-items: center;
}

/* АНИМАЦИИ VUE */
.form-fade-slide-enter-active {
  transition: all 0.4s ease;
}
.form-fade-slide-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.toast-fade-enter-active,
.toast-fade-leave-active {
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.toast-fade-enter-from,
.toast-fade-leave-to {
  opacity: 0;
  transform: translate(-50%, -15px);
}
</style>