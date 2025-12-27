<template>
  <section class="reviews container py-5">
    <div class="row g-4 mb-5">
      <div
        class="col-md-4"
        v-for="review in visibleReviews"
        :key="review.id"
      >
        <div class="review-card h-100 p-4">
          <h5 class="fw-semibold mb-1">
            {{ review.name }}
          </h5>

          <div class="review-rating mb-3">
            <span v-for="i in 5" :key="i">
              {{ i <= review.rating ? '★' : '☆' }}
            </span>
          </div>

          <p class=" mb-0">
            {{ review.message }}
          </p>
        </div>
      </div>
    </div>

    
    <div class="text-center mb-4" v-if="reviews.length > initialCount">
      <button
        class="btn btn-outline-primary px-4"
        @click="showAll = !showAll"
      >
        {{ showAll ? 'Скрыть отзывы' : 'Показать все отзывы' }}
      </button>
    </div>

    
    <div class="text-center" v-if="isLoggedIn">
      <button
        class="btn btn-outline-primary px-4 mb-4"
        @click="showForm = !showForm"
      >
        {{ showForm ? 'Закрыть форму' : 'Оставить отзыв' }}
      </button>
    </div>

    <ReviewForm
      v-if="showForm && isLoggedIn"
      class="mt-5"
      @submitted="loadReviews"
    />
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useStorage } from '@vueuse/core'
import api from '../services/api'
import ReviewForm from '../pages/ReviewForm.vue'

const reviews = ref([])
const showForm = ref(false)
const showAll = ref(false)

const initialCount = 3

const token = useStorage('token', null)
const isLoggedIn = computed(() => !!token.value)

const visibleReviews = computed(() => {
  return showAll.value ? reviews.value : reviews.value.slice(0, initialCount)
})

const loadReviews = async () => {
  try {
    const res = await api.get('/reviews')
    reviews.value = res.data
  } catch (e) {
    console.error('Ошибка загрузки отзывов', e)
  }
}

onMounted(loadReviews)
</script>

<style scoped>
.review-card {
  position: relative;
  height: 100%;
  padding: 32px 26px;
  border-radius: 22px;
  background: linear-gradient(160deg, #1b1f27, #0f1218);
  box-shadow:
    0 15px 40px rgba(0, 0, 0, 0.6),
    inset 0 1px 0 rgba(255, 255, 255, 0.04);
  transition:
    transform 0.35s ease,
    box-shadow 0.35s ease;
  color: #e5e7eb;
}

.review-card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 24px;
  right: 24px;
  height: 4px;
  border-radius: 0 0 10px 10px;
  background: linear-gradient(90deg, #2563eb, #1e40af);
}

.review-card:hover {
  transform: translateY(-10px);
  box-shadow:
    0 30px 60px rgba(37, 99, 235, 0.35),
    inset 0 1px 0 rgba(255, 255, 255, 0.06);
}

.review-card h5 {
  font-size: 1.1rem;
  font-weight: 700;
  color: #ffffff;
  letter-spacing: 0.4px;
}

.review-rating {
  color: #facc15;
  font-size: 18px;
  letter-spacing: 2px;
}

.review-card p {
  color: #9ca3af;
  line-height: 1.65;
}

.btn-outline-primary {
  border-radius: 14px;
  font-weight: 600;
  padding: 10px 28px;
  color: #3b82f6;
  border-color: #3b82f6;
  transition: all 0.3s ease;
}

.btn-outline-primary:hover {
  background: #3b82f6;
  color: #ffffff;
  box-shadow: 0 10px 30px rgba(37, 99, 235, 0.4);
  transform: translateY(-2px);
}
</style>
