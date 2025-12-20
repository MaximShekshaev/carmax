<template>
  <form class="review-form" @submit.prevent="submitReview">

    <h4 class="mb-4 text-center">Оставить отзыв</h4>

    <!-- Имя -->
    <input
      v-model="form.name"
      type="text"
      class="form-control mb-3"
      placeholder="Ваше имя"
      required
    />

    <!-- Сообщение -->
    <textarea
      v-model="form.message"
      class="form-control mb-3"
      placeholder="Ваш отзыв"
      rows="4"
      required
    ></textarea>

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

    <button class="btn btn-primary w-100" :disabled="loading">
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
    
    await api.post('/reviews', form, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })

    form.name = ''
    form.message = ''
    form.rating = 0

    success.value = true
    emit('submitted')
  } catch (e) {
    alert('Ошибка отправки. Возможно, вы не авторизованы.')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.review-form {
  max-width: 420px;
  margin: auto;
  padding: 32px;
  border-radius: 16px;
  background: #fff;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
}

.rating i {
  font-size: 28px;
  margin: 0 4px;
  cursor: pointer;
  color: #ced4da;
  transition: color 0.2s;
}

.rating i.active {
  color: #ffc107;
}
</style>
