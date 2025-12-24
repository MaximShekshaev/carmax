<template>
  
  <div class="row g-4 mb-5">
    <div
      class="col-md-4"
      v-for="car in cars"
      :key="car.id"
    >
      <div class="car-card d-flex flex-column h-100">
        <div class="image-wrap">
          <img :src="car.image" :alt="car.name" />
        </div>

        <div class="car-content d-flex flex-column flex-grow-1">
          <h5 class="fw-semibold mb-2 text-white">{{ car.brand }} {{ car.name }}</h5>
          <p class=" small mb-3 flex-grow-1 text-white">{{ car.description }}</p>

          <div class="mt-auto">
            <div class="price-row mb-3">
              <span class="small">Цена:</span>
              <span class="price">{{ car.price_per_day }} ₸ / сутки</span>
            </div>

            <button
              class="btn btn-primary w-100"
              :disabled="!isLoggedIn"
              @click="rentCar(car.id)"
              :title="!isLoggedIn ? 'Войдите, чтобы арендовать' : ''"
            >
              Арендовать
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useStorage } from '@vueuse/core'
import { computed } from 'vue'

defineProps({
  cars: {
    type: Array,
    required: true
  }
})

const router = useRouter()
const token = useStorage('token', null)


const isLoggedIn = computed(() => !!token.value)

const rentCar = (carId) => {
  if (!isLoggedIn.value) {
    router.push('/login')
    return
  }
  router.push(`/rent/${carId}`)
}
</script>

<style scoped>
.car-card {
  position: relative;
  border-radius: 22px;
  overflow: hidden;
  background: linear-gradient(160deg, #1b1f27, #0f1218);
  box-shadow:
    0 15px 40px rgba(0, 0, 0, 0.6),
    inset 0 1px 0 rgba(255, 255, 255, 0.04);
  transition:
    transform 0.35s ease,
    box-shadow 0.35s ease;
  display: flex;
  flex-direction: column;
  height: 100%;
}

/* HOVER */
.car-card:hover {
  transform: translateY(-10px);
  box-shadow:
    0 30px 60px rgba(37, 99, 235, 0.35),
    inset 0 1px 0 rgba(255, 255, 255, 0.06);
}

/* ИЗОБРАЖЕНИЕ */
.image-wrap {
  height: 280px;
  overflow: hidden;
  position: relative;
}

.image-wrap::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(
    to bottom,
    rgba(0, 0, 0, 0) 40%,
    rgba(0, 0, 0, 0.6)
  );
  z-index: 1;
}

.image-wrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.45s ease;
}

.car-card:hover img {
  transform: scale(1.08);
}

/* КОНТЕНТ */
.car-content {
  padding: 26px 24px;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
  color: #e5e7eb;
}

/* ЗАГОЛОВОК */
.car-content h5 {
  font-size: 1.15rem;
  font-weight: 700;
  letter-spacing: 0.5px;
  text-transform: uppercase;
  color: #ffffff;
}

/* ОПИСАНИЕ */
.car-content p {
  color: #ffffff;
  line-height: 1.5;
}

/* ЦЕНА */
.price-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-top: 1px solid rgba(255, 255, 255, 0.06);
  padding-top: 12px;
}

.price {
  font-size: 1.2rem;
  font-weight: 800;
  color: #3b82f6;
}

/* КНОПКА */
.btn-primary {
  background: linear-gradient(135deg, #2563eb, #1e40af);
  border: none;
  border-radius: 14px;
  font-weight: 600;
  letter-spacing: 0.4px;
  padding: 12px;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background: linear-gradient(135deg, #1e40af, #2563eb);
  box-shadow: 0 10px 30px rgba(37, 99, 235, 0.45);
  transform: translateY(-2px);
}

/* DISABLED */
button[disabled] {
  background: #1f2937;
  color: #6b7280;
  cursor: not-allowed;
  opacity: 0.7;
}
</style>
