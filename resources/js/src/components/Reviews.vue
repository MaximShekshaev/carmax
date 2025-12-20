<template>
  <section class="reviews container py-5">
    <h2 class="mb-5 text-center fw-bold text-primary">Отзывы клиентов</h2>

    <div class="row g-4 mb-5">
      <div
        class="col-md-4"
        v-for="review in reviews"
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

          <p class="text-muted mb-0">
            {{ review.message }}
          </p>

        </div>
      </div>
    </div>

    
    <div class="text-center" v-if="isLoggedIn">
      <button
        class="btn btn-outline-primary px-4"
        @click="showForm = !showForm"
      >
        {{ showForm ? 'Закрыть форму' : 'Оставить отзыв' }}
      </button>
    </div>

    <!-- Форма -->
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


const token = useStorage('token', null)


const isLoggedIn = computed(() => !!token.value)

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
  border-radius: 20px;
  background: linear-gradient(145deg, #ffffff, #f3f4f6);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06),
              inset 0 1px 0 rgba(255, 255, 255, 0.9);
  transition: transform 0.35s ease, box-shadow 0.35s ease;
}

.review-card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 24px;
  right: 24px;
  height: 4px;
  border-radius: 0 0 10px 10px;
}

.review-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 45px rgba(13, 110, 253, 0.2),
              inset 0 1px 0 rgba(255, 255, 255, 1);
}

.review-card h5 {
  font-size: 1.05rem;
  color: #212529;
}

.review-rating {
  color: #ffc107;
  font-size: 18px;
  letter-spacing: 2px;
}

.review-card p {
  line-height: 1.6;
}
</style>
