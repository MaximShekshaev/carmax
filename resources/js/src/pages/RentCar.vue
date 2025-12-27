<template>
  <div class="rent-car-page d-flex justify-content-center align-items-center min-vh-100 p-4">
    
   
    <div 
      v-if="message.text" 
      class="toast-message" 
      :class="message.type"
      data-aos="fade-down"
    >
      {{ message.text }}
    </div>

    <div class="card rent-card shadow-lg p-5">
      <h3 class="mb-4 text-center title">
        Аренда: {{ car.brand }} {{ car.name }}
      </h3>

     
      <div class="car-image-wrapper mb-4 text-center">
        <img :src="car.image" :alt="car.name" class="car-image" />
      </div>

      
      <div class="mb-3 text-center" v-if="car.price_per_day">
        <strong>Цена за день:</strong> {{ car.price_per_day.toLocaleString() }} ₸
      </div>

      
      <div class="mb-3">
        <label class="form-label fw-semibold">Дата начала</label>
        <input type="date" v-model="startDate" class="form-control dark-input" />
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Дата окончания</label>
        <input type="date" v-model="endDate" class="form-control dark-input" />
      </div>

      
      <div v-if="startDate && endDate && rentalDays > 0" class="mb-3 text-center total-price">
        <strong>Итого ({{ rentalDays }} {{ rentalDays === 1 ? 'день' : 'дней' }}):</strong>
        {{ totalPrice.toLocaleString() }} ₸
      </div>

     
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
import AOS from 'aos'
import 'aos/dist/aos.css'

const route = useRoute()
const router = useRouter()
const token = useStorage('token', null)

const car = ref({})
const startDate = ref('')
const endDate = ref('')
const loading = ref(false)
const message = ref({ text: '', type: '' })

const carId = route.params.carId


const showMessage = (text, type = 'success') => {
  message.value = { text, type }
  setTimeout(() => {
    message.value.text = ''
  }, 3000)
}

onMounted(async () => {
  AOS.init({ duration: 600, once: true })

  if (!carId) {
    showMessage('Машина не выбрана', 'error')
    setTimeout(() => router.push('/'), 1000)
    return
  }

  try {
    const { data } = await api.get(`/cars/${carId}`)
    car.value = data
  } catch (err) {
    console.error(err)
    showMessage('Ошибка загрузки данных машины', 'error')
    setTimeout(() => router.push('/'), 1000)
  }
})

const rentalDays = computed(() => {
  if (!startDate.value || !endDate.value) return 0
  const diff = Math.ceil((new Date(endDate.value) - new Date(startDate.value)) / (1000 * 60 * 60 * 24))
  return diff > 0 ? diff : 0
})

const totalPrice = computed(() => rentalDays.value * (car.value.price_per_day || 0))

const confirmRent = async () => {
  if (!startDate.value || !endDate.value) {
    return showMessage('Выберите даты аренды', 'error')
  }

  if (rentalDays.value <= 0) {
    return showMessage('Дата окончания должна быть позже даты начала', 'error')
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
    showMessage('Аренда успешно оформлена!', 'success')
    setTimeout(() => router.push('/my-rentals'), 1000)
  } catch (err) {
    console.error(err.response?.data)
    showMessage(err.response?.data?.message || 'Ошибка при аренде', 'error')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.rent-car-page {
  background: #0d0f14;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
}


.toast-message {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  padding: 12px 24px;
  border-radius: 12px;
  font-weight: 600;
  z-index: 9999;
  min-width: 250px;
  text-align: center;
  color: #fff;
  box-shadow: 0 4px 15px rgba(0,0,0,0.5);
  pointer-events: none;
  background: linear-gradient(135deg, #1b1f27, #0f1218);
}
.toast-message.success { border-left: 4px solid #2563eb; }
.toast-message.error { border-left: 4px solid #dc3545; }


.rent-card {
  max-width: 500px;
  width: 100%;
  border-radius: 24px;
  background: #1b1f27;
  color: #e5e7eb;
  box-shadow: 0 15px 40px rgba(0,0,0,0.6);
  transition: transform 0.3s, box-shadow 0.3s;
}
.rent-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 50px rgba(37, 99, 235, 0.5);
}

.title { color: #3b82f6; }

.car-image-wrapper {
  display: flex;
  justify-content: center;
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

.dark-input {
  background: #2a2c36;
  border: none;
  color: #e5e7eb;
  border-radius: 12px;
  padding: 0.5rem 0.75rem;
}
.dark-input:focus {
  outline: none;
  box-shadow: 0 0 0 2px #3b82f6;
}

.btn-primary {
  font-weight: 600;
  background: #2563eb;
  border-radius: 12px;
  transition: transform 0.2s, background 0.2s;
}
.btn-primary:hover {
  background-color: #1f5bcc;
  transform: translateY(-2px);
}

.total-price {
  font-size: 1.2rem;
  color: #3b82f6;
  font-weight: 600;
  text-align: center;
}
</style>
