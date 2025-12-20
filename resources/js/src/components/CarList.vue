<template>
  <div class="row g-4 mb-5">
    <div
      class="col-md-4"
      v-for="car in cars"
      :key="car.id"
    >
      <div class="car-card d-flex flex-column h-100">

        <!-- Фото -->
        <div class="image-wrap">
          <img
            :src="car.image"
            :alt="car.name"
          />
        </div>

        <!-- Контент -->
        <div class="car-content d-flex flex-column flex-grow-1">
          <h5 class="fw-semibold mb-2">
            {{ car.brand }} {{ car.name }}
          </h5>

          <p class="text-muted small mb-3 flex-grow-1">
            {{ car.description }}
          </p>

          <div class="mt-auto">
            <div class="price-row mb-3">
              <span class="text-muted small">Цена за день</span>
              <span class="price">
                {{ car.price_per_day }} ₸ / сутки
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
  position: relative;
  border-radius: 20px;
  overflow: hidden;

  background: linear-gradient(145deg, #ffffff, #f3f4f6);

  box-shadow:
    0 10px 25px rgba(0, 0, 0, 0.06),
    inset 0 1px 0 rgba(255, 255, 255, 0.9);

  transition:
    transform 0.35s ease,
    box-shadow 0.35s ease;

  display: flex;
  flex-direction: column;
  height: 100%;
}

.car-card:hover {
  transform: translateY(-8px);
  box-shadow:
    0 22px 45px rgba(13, 110, 253, 0.2),
    inset 0 1px 0 rgba(255, 255, 255, 1);
}

.image-wrap {
  height: 300px;
  overflow: hidden;
}

.image-wrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.car-card:hover img {
  transform: scale(1.05);
}

.car-content {
  padding: 24px 22px;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.price-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.price {
  font-size: 1.1rem;
  font-weight: 700;
  color: #0d6efd;
}
</style>