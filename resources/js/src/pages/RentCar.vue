<template>
  <div class="rent-car container py-5">
    <div class="card rent-card shadow-sm p-4 mx-auto">
      <h3 class="mb-4 text-center">
        Аренда: {{ car.brand }} {{ car.name }}
      </h3>

      <!-- Фото машины -->
      <div class="car-image-wrapper mb-4 text-center">
        <img :src="car.image" :alt="car.name" class="car-image" />
      </div>

      <!-- Цена за день -->
      <div class="mb-3 text-center" v-if="car.price_per_day">
        <strong>Цена за день:</strong> {{ car.price_per_day.toLocaleString() }} ₸
      </div>

      <!-- Даты аренды -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Дата начала</label>
        <input type="date" v-model="startDate" class="form-control" />
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Дата окончания</label>
        <input type="date" v-model="endDate" class="form-control" />
      </div>

      <!-- Итоговая цена -->
      <div v-if="startDate && endDate && rentalDays > 0" class="mb-3 text-center total-price">
        <strong>Итого ({{ rentalDays }} {{ rentalDays === 1 ? 'день' : 'дней' }}):</strong>
        {{ totalPrice.toLocaleString() }} ₸
      </div>

      <!-- Кнопка аренды -->
      <button class="btn btn-primary w-100 py-2 fs-5" @click="confirmRent" :disabled="loading">
        {{ loading ? "Отправка..." : "Арендовать" }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'
import { useStorage } from '@vueuse/core'
import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

const route = useRoute()
const router = useRouter()
const token = useStorage('token', null)

const car = ref({})
const startDate = ref('')
const endDate = ref('')
const loading = ref(false)

const carId = route.params.carId

// Получение данных машины
onMounted(async () => {
  if (!carId) {
    Swal.fire({
      icon: 'error',
      title: 'Ошибка',
      text: 'Машина не выбрана',
      confirmButtonColor: '#1f5bcc'
    })
    router.push('/')
    return
  }

  try {
    const { data } = await api.get(`/cars/${carId}`)
    car.value = data
  } catch (err) {
    console.error(err)
    Swal.fire({
      icon: 'error',
      title: 'Ошибка',
      text: 'Ошибка загрузки данных машины',
      confirmButtonColor: '#1f5bcc'
    })
    router.push('/')
  }
})

// Количество дней аренды
const rentalDays = computed(() => {
  if (!startDate.value || !endDate.value) return 0
  const diff = Math.ceil((new Date(endDate.value) - new Date(startDate.value)) / (1000 * 60 * 60 * 24))
  return diff > 0 ? diff : 0
})

// Итоговая цена
const totalPrice = computed(() => {
  return rentalDays.value * (car.value.price_per_day || 0)
})

// Подтверждение аренды
const confirmRent = async () => {
  if (!startDate.value || !endDate.value) {
    return Swal.fire({
      icon: 'warning',
      title: 'Внимание',
      text: 'Выберите даты аренды',
      confirmButtonColor: '#1f5bcc'
    })
  }

  if (rentalDays.value <= 0) {
    return Swal.fire({
      icon: 'warning',
      title: 'Внимание',
      text: 'Дата окончания должна быть позже даты начала',
      confirmButtonColor: '#1f5bcc'
    })
  }

  const payload = {
    car_id: car.value.id,
    start_date: startDate.value,
    end_date: endDate.value,
    total_price: totalPrice.value
  }

  loading.value = true
  try {
    await api.post('/rentals', payload, {
      headers: { Authorization: `Bearer ${token.value}` }
    })
    Swal.fire({
      icon: 'success',
      title: 'Успех!',
      text: 'Аренда успешно оформлена',
      confirmButtonColor: '#1f5bcc'
    })
    router.push('/my-rentals')
  } catch (err) {
    console.error(err.response?.data)
    Swal.fire({
      icon: 'error',
      title: 'Ошибка',
      text: err.response?.data?.message || 'Ошибка при аренде',
      confirmButtonColor: '#1f5bcc'
    })
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.rent-card {
  max-width: 500px;
  border-radius: 20px;
  background: #ffffff;
  transition: transform 0.3s, box-shadow 0.3s;
}

.rent-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.car-image-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
}

.car-image {
  width: 100%;
  max-width: 300px;
  border-radius: 15px;
  object-fit: cover;
  transition: transform 0.3s;
}

.car-image-wrapper:hover .car-image {
  transform: scale(1.05);
}

.btn-primary {
  font-weight: 600;
  transition: transform 0.2s, background 0.2s;
}

.btn-primary:hover {
  background-color: #1f5bcc;
  transform: translateY(-2px);
}

.form-label {
  font-weight: 500;
}

.total-price {
  font-size: 1.2rem;
  color: #1f5bcc;
  font-weight: 600;
}
</style>
