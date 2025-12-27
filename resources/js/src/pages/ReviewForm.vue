<template>
  
  <transition name="form-fade-slide">
    <div class="review-form-card">

      <h4 class="form-title mb-4 text-center">
        Оставить отзыв
      </h4>

      <form @submit.prevent="submitReview">

        
        <div class="mb-3">
          <label class="form-label">Ваше имя</label>
          <input
            v-model="form.name"
            type="text"
            class="form-control"
            placeholder="Введите имя"
            required
          />
        </div>

       
        <div class="mb-3">
          <label class="form-label">Оценка</label>
          <div class="rating-input">
            <span
              v-for="i in 5"
              :key="i"
              @click="form.rating = i"
              :class="{ active: i <= form.rating }"
            >
              ★
            </span>
          </div>
        </div>

       
        <div class="mb-4">
          <label class="form-label">Отзыв</label>
          <textarea
            v-model="form.message"
            rows="4"
            class="form-control"
            placeholder="Поделитесь впечатлениями..."
            required
          ></textarea>
        </div>

        <div class="text-center">
          <button class="btn btn-primary px-5">
            Отправить отзыв
          </button>
        </div>

      </form>
    </div>
  </transition>

 
  <transition name="toast-fade">
    <div v-if="showToast" class="toast-success">
      <i class="bi bi-check-circle"></i>
      Отзыв отправлен
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
    setTimeout(() => (showToast.value = false), 2200)

  } catch (e) {
    console.error('Ошибка отправки отзыва', e)
  }
}
</script>

<style scoped>

.review-form-card {
  max-width: 520px;
  margin: 0 auto;
  padding: 36px 32px;
  border-radius: 24px;

  background: linear-gradient(160deg, #1b1f27, #0f1218);
  box-shadow:
    0 25px 60px rgba(0, 0, 0, 0.65),
    inset 0 1px 0 rgba(255, 255, 255, 0.04);

  color: #e5e7eb;
}

.form-title {
  font-weight: 800;
  letter-spacing: 0.6px;
  color: #ffffff;
}

.form-label {
  font-size: 0.9rem;
  color: #9ca3af;
}

.form-control {
  background: #0b0f15;
  border: 1px solid #1f2937;
  color: #e5e7eb;
  border-radius: 14px;
  padding: 12px 16px;
}

.form-control::placeholder {
  color: #6b7280;
}

.form-control:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
}


.rating-input {
  display: flex;
  gap: 8px;
  font-size: 26px;
  cursor: pointer;
}

.rating-input span {
  color: #374151;
  transition: 0.25s;
}

.rating-input span.active {
  color: #facc15;
}

.rating-input span:hover {
  transform: scale(1.2);
}


.btn-primary {
  border-radius: 16px;
  padding: 12px 36px;
  font-weight: 700;
  background: linear-gradient(135deg, #2563eb, #1e40af);
  border: none;
  box-shadow: 0 15px 40px rgba(37, 99, 235, 0.4);
  transition: 0.3s;
}

.btn-primary:hover {
  transform: translateY(-3px);
}


.form-fade-slide-enter-active {
  transition: all 0.45s ease;
}

.form-fade-slide-enter-from {
  opacity: 0;
  transform: translateY(30px) scale(0.97);
}


.toast-success {
  position: fixed;
  bottom: 24px;
  right: 24px;

  display: flex;
  align-items: center;
  gap: 8px;

  padding: 10px 16px;
  border-radius: 14px;

  background: #111827;
  color: #e5e7eb;
  font-size: 0.85rem;

  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
}

.toast-success i {
  color: #22c55e;
  font-size: 1rem;
}


.toast-fade-enter-active,
.toast-fade-leave-active {
  transition: all 0.3s ease;
}

.toast-fade-enter-from,
.toast-fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}
</style>
