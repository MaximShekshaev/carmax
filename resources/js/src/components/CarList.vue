<template>
  <div class="row g-4 mb-5">
    <div class="col-md-4" v-for="car in cars" :key="car.id">
      <div class="card car-card h-100 border-0">

        <!-- Фото -->
        <div class="position-relative">
          <img
            :src="car.image"
            class="card-img-top"
            :alt="car.name"
          />

         
        </div>

        <!-- Контент -->
        <div class="card-body d-flex flex-column">
          <h5 class="card-title fw-semibold">{{ car.name }}</h5>

          <p class="card-text text-muted small">
            {{ car.description }}
          </p>

          <div class="mt-auto">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted small">Цена за день</span>
              <span class="fs-5 fw-bold text-primary">
                {{car.price_per_day}} ₸
              </span>
            </div>

            <button class="btn btn-primary w-100">
              Арендовать
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  cars: {
    type: Array,
    required: true
  }
})
</script>

<style scoped>
.car-card {
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.car-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 18px 40px rgba(0, 0, 0, 0.15);
}

.card-img-top {
  height: 220px;
  object-fit: cover;
}
</style>
