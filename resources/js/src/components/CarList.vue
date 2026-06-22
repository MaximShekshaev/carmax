<template>
  <div>

    <!-- 🧊 FILTER PANEL -->
    <div class="filter-panel mb-4">

      <div class="filter-title">
        Фильтры
      </div>

      <div class="row g-3 align-items-end">

        <!-- SEARCH -->
        <div class="col-md-4">
          <label class="filter-label">Поиск</label>
          <input
            v-model="filters.search"
            class="form-control modern-input"
            placeholder="Бренд или модель..."
          />
        </div>

        <!-- MIN PRICE -->
        <div class="col-md-3">
          <label class="filter-label">Цена от</label>
          <input
            v-model.number="filters.minPrice"
            type="number"
            class="form-control modern-input"
            placeholder="₸"
          />
        </div>

        <!-- MAX PRICE -->
        <div class="col-md-3">
          <label class="filter-label">Цена до</label>
          <input
            v-model.number="filters.maxPrice"
            type="number"
            class="form-control modern-input"
            placeholder="₸"
          />
        </div>

        <!-- RESET -->
        <div class="col-md-2 d-grid">
          <button class="btn-reset" @click="resetFilters">
            Сброс
          </button>
        </div>

      </div>

      <!-- QUICK FILTER CHIPS -->
      <div class="chips mt-3">
        <button @click="setPrice(0, 20000)">до 20k</button>
        <button @click="setPrice(20000, 50000)">20k - 50k</button>
        <button @click="setPrice(50000, 100000)">50k+</button>
      </div>

    </div>

    <!-- 🚗 CARS -->
    <div class="row g-4">
      <div
        class="col-md-4"
        v-for="car in filteredCars"
        :key="car.id"
      >

        <div class="car-card">

          <div class="image-wrap">
            <img :src="car.image" />
          </div>

          <div class="car-content">

            <h5 class="car-title">
              {{ car.brand }}
              <span>{{ car.name }}</span>
            </h5>

            <p class="desc">
              {{ car.description }}
            </p>

            <div class="price-row">
              <span>{{ car.price_per_day }} ₸ / сутки</span>
            </div>

            <button class="btn-rent" @click="rentCar(car.id)">
              Арендовать
            </button>

          </div>

        </div>

      </div>
    </div>

  </div>
</template>

<script setup>
import { computed, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useStorage } from '@vueuse/core'

const props = defineProps({
  cars: Array
})

const router = useRouter()
const token = useStorage('token', null)

const filters = reactive({
  search: '',
  minPrice: null,
  maxPrice: null
})

const filteredCars = computed(() => {
  return props.cars.filter(car => {

    const s = filters.search.toLowerCase()

    const matchSearch =
      !s ||
      car.brand.toLowerCase().includes(s) ||
      car.name.toLowerCase().includes(s)

    const matchMin =
      !filters.minPrice || car.price_per_day >= filters.minPrice

    const matchMax =
      !filters.maxPrice || car.price_per_day <= filters.maxPrice

    return matchSearch && matchMin && matchMax
  })
})

const resetFilters = () => {
  filters.search = ''
  filters.minPrice = null
  filters.maxPrice = null
}

const setPrice = (min, max) => {
  filters.minPrice = min
  filters.maxPrice = max
}

const rentCar = (id) => {
  if (!token.value) return router.push('/login')
  router.push(`/rent/${id}`)
}
</script>

<style scoped>

/* 🌫 FILTER PANEL (GLASS EFFECT) */
.filter-panel {
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(0,0,0,0.05);
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.04);
}

.filter-title {
  font-weight: 700;
  font-size: 18px;
  margin-bottom: 15px;
  color: #111827;
}

.filter-label {
  font-size: 12px;
  color: #6b7280;
  margin-bottom: 6px;
  display: block;
}

/* inputs */
.modern-input {
  border-radius: 10px;
  border: 1px solid #e5e7eb;
  padding: 10px 12px;
  transition: 0.2s;
}

.modern-input:focus {
  border-color: #111827;
  box-shadow: 0 0 0 3px rgba(17,24,39,0.1);
}

/* reset button */
.btn-reset {
  background: transparent;
  border: 1px solid #e5e7eb;
  padding: 10px;
  border-radius: 10px;
  transition: 0.2s;
}

.btn-reset:hover {
  background: #111827;
  color: white;
}

/* chips */
.chips {
  display: flex;
  gap: 10px;
}

.chips button {
  border: 1px solid #e5e7eb;
  background: white;
  padding: 6px 12px;
  border-radius: 999px;
  font-size: 12px;
  transition: 0.2s;
}

.chips button:hover {
  background: #111827;
  color: white;
}

/* CARDS */
.car-card {
  border-radius: 16px;
  overflow: hidden;
  background: white;
  border: 1px solid #eee;
  transition: 0.3s;
}

.car-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}

.image-wrap {
  height: 200px;
  overflow: hidden;
}

.image-wrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.car-content {
  padding: 16px;
}

.car-title {
  font-weight: 700;
  font-size: 16px;
}

.car-title span {
  font-weight: 400;
  color: #6b7280;
}

.desc {
  font-size: 13px;
  color: #6b7280;
  margin: 10px 0;
}

.price-row {
  font-weight: 700;
  margin-bottom: 10px;
}

.btn-rent {
  width: 100%;
  background: #111827;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 10px;
  transition: 0.3s;
}

.btn-rent:hover {
  background: white;
  color: #111827;
  border: 1px solid #111827;
}

</style>