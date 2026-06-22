<template>
  <section class="reviews container py-5">

    <div class="row g-4 mb-5">

      <div
        class="col-lg-4 col-md-6"
        v-for="review in visibleReviews"
        :key="review.id"
      >
        <div class="review-card h-100 p-4">

          <div class="quote-icon">“</div>

          <!-- HEADER -->
          <div class="review-header mb-3">

            <div class="avatar-ring">
              <div class="avatar">
                {{ getInitials(review.name) }}
              </div>
            </div>

            <div class="user-info">

              <div class="name-line">

                <h5 class="review-name">
                  {{ review.name }}
                </h5>

                <span class="verified">
                  <i class="bi bi-patch-check-fill"></i>
                </span>

              </div>

              <span class="review-date">
                {{ formatDate(review.created_at) }}
              </span>

            </div>

          </div>

          <!-- RATING -->
          <div class="review-rating mb-3">
            <span v-for="i in 5" :key="i">
              <i :class="i <= review.rating ? 'bi bi-star-fill' : 'bi bi-star'"></i>
            </span>
          </div>

          <!-- TEXT -->
          <p class="review-text mb-0">
            {{ review.message }}
          </p>

        </div>
      </div>

    </div>

    <!-- BUTTONS -->
    <div class="d-flex flex-column align-items-center gap-3">

      <div v-if="reviews.length > initialCount">
        <button
          class="btn-minimal px-4 py-2"
          @click="showAll = !showAll"
        >
          {{ showAll ? 'Скрыть отзывы' : 'Показать все отзывы' }}
        </button>
      </div>

      <div v-if="isLoggedIn">
        <button
          class="btn-minimal px-4 py-2"
          @click="showForm = !showForm"
        >
          {{ showForm ? 'Закрыть форму' : 'Оставить отзыв' }}
        </button>
      </div>

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
  return showAll.value
    ? reviews.value
    : reviews.value.slice(0, initialCount)
})

const getInitials = (name) => {
  if (!name) return '?'

  return name
    .split(' ')
    .map(word => word[0])
    .join('')
    .substring(0, 2)
    .toUpperCase()
}

const formatDate = (date) => {
  if (!date) return 'Недавно'

  return new Date(date).toLocaleDateString('ru-RU')
}

const loadReviews = async () => {
  try {
    const res = await api.get('/reviews')
    reviews.value = res.data
  } catch (e) {
    console.error(e)
  }
}

onMounted(loadReviews)
</script>

<style scoped>

.reviews {
  position: relative;
}

/* CARD */

.review-card {
  position: relative;
  overflow: hidden;
  background: #fff;
  border-radius: 22px;
  border: 1px solid rgba(0,0,0,0.05);
  box-shadow: 0 8px 30px rgba(0,0,0,0.04);
  transition: .35s ease;
}

.review-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 50px rgba(0,0,0,0.08);
}

/* QUOTE */

.quote-icon {
  position: absolute;
  top: -20px;
  right: 20px;
  font-size: 100px;
  color: rgba(0,0,0,0.03);
  line-height: 1;
}

/* HEADER */

.review-header {
  display: flex;
  align-items: center;
  gap: 14px;
}

/* INSTAGRAM RING */

.avatar-ring {
  width: 66px;
  height: 66px;
  border-radius: 50%;
  padding: 3px;

  background:
  linear-gradient(
    45deg,
    #f58529,
    #feda77,
    #dd2a7b,
    #8134af,
    #515bd4
  );

  display: flex;
  align-items: center;
  justify-content: center;
}

.avatar {
  width: 100%;
  height: 100%;
  border-radius: 50%;

  background: #111827;

  display: flex;
  align-items: center;
  justify-content: center;

  color: white;
  font-weight: 700;
  font-size: 18px;
}

.user-info {
  flex: 1;
}

.name-line {
  display: flex;
  align-items: center;
  gap: 6px;
}

.review-name {
  margin: 0;
  font-size: 17px;
  font-weight: 700;
  color: #111827;
}

.verified {
  color: #0ea5e9;
  font-size: 15px;
}

.review-date {
  font-size: 13px;
  color: #9ca3af;
}

/* STARS */

.review-rating {
  color: #f59e0b;
  display: flex;
  gap: 3px;
  font-size: 15px;
}

/* TEXT */

.review-text {
  color: #4b5563;
  line-height: 1.7;
  font-size: 15px;
}

/* BUTTON */

.btn-minimal {
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  transition: .3s;
  font-weight: 600;
}

.btn-minimal:hover {
  background: #111827;
  color: white;
  border-color: #111827;
}

/* MOBILE */

@media(max-width:768px){

  .avatar-ring{
    width:58px;
    height:58px;
  }

  .review-card{
    padding:20px !important;
  }

}

</style>