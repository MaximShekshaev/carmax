<template>
  <section class="container py-5">
    <h2 class="text-center mb-5 fw-semibold">Отзывы клиентов</h2>

    <div class="row g-4">
      <div
        class="col-md-4"
        v-for="(review, index) in reviews"
        :key="index"
      >
        <div class="review-card h-100 p-4">
          
          <!-- Имя -->
          <h5 class="fw-semibold mb-1">{{ review.name }}</h5>

          <!-- Оценка -->
          <div class="mb-3 text-warning">
            <span v-for="i in 5" :key="i">
              {{ i <= review.rating ? "★" : "☆" }}
            </span>
          </div>

          <!-- Текст -->
          <p class="text-muted mb-0">
            {{ review.text }}
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const reviews = [
  {
    name: "Алексей",
    rating: 5,
    text: "Отличный сервис! Машину привезли вовремя, состояние идеальное."
  },
  {
    name: "Марина",
    rating: 4,
    text: "Все понравилось, быстрое оформление и вежливый персонал."
  },
  {
    name: "Дамир",
    rating: 5,
    text: "Брал авто на неделю, цена адекватная, обязательно обращусь снова."
  }
]
</script>

<style scoped>
.review-card {
  border-radius: 16px;
  background: #ffffff;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.review-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
}
</style>
