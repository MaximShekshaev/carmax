<template>
  <div class="my-rentals-page">

    
    <div 
      v-if="message.text" 
      class="toast-message" 
      :class="message.type"
      data-aos="fade-down"
    >
      {{ message.text }}
    </div>

    <div class="big-card p-5" data-aos="fade-up">
      <h2 class="mb-4 text-center fw-bold">Мои аренды</h2>

      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Загрузка...</span>
        </div>
      </div>

      <div v-else>
        <div v-if="rentals.length === 0" class="text-center text-muted py-5">
          У вас пока нет аренд.
        </div>

        <div class="rental-cards">
          <div
            v-for="rental in rentals"
            :key="rental.id"
            class="car-card p-4 mb-4"
            data-aos="zoom-in"
          >
            <div class="d-flex align-items-center mb-3">
              <img
                :src="rental.car.image"
                :alt="rental.car.name"
                class="rental-img me-3"
              />
              <div>
                <h5 class="mb-1">{{ rental.car.brand }} {{ rental.car.name }}</h5>
                <small class="text-muted">Цена за день: {{ rental.car.price_per_day.toLocaleString() }} ₸</small>
              </div>
            </div>

            <p class="mb-1">
              <strong>С:</strong> {{ formatDate(rental.start_date) }} 
              <strong>По:</strong> {{ formatDate(rental.end_date) }}
            </p>
            <p class="mb-3">
              <strong>Итого:</strong> {{ rental.total_price.toLocaleString() }} ₸
            </p>

            <div class="d-flex justify-content-between align-items-center mt-auto">
              <span :class="rentalStatusClass(rental)" class="badge status-badge">
                {{ rentalStatus(rental) }}
              </span>
              <button
                class="btn btn-sm cancel-btn"
                :disabled="rentalStatus(rental) === 'Завершено'"
                @click="cancelRental(rental.id)"
              >
                Отказаться
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'
import { useStorage } from '@vueuse/core'
import { useRouter } from 'vue-router'
import AOS from 'aos'
import 'aos/dist/aos.css'

const rentals = ref([])
const loading = ref(false)
const message = ref({ text: '', type: '' })
const token = useStorage('token', null)
const router = useRouter()

const showMessage = (text, type = 'success') => {
  message.value = { text, type }
  setTimeout(() => { message.value.text = '' }, 3000)
}

const loadRentals = async () => {
  loading.value = true
  try {
    const { data } = await api.get('/rentals', {
      headers: { Authorization: `Bearer ${token.value}` }
    })
    rentals.value = data
  } catch (err) {
    console.error(err)
    showMessage(err.response?.data?.message || 'Ошибка при загрузке ваших аренд', 'error')
  } finally {
    loading.value = false
  }
}

const cancelRental = async (rentalId) => {
  if (!confirm) return

  try {
    await api.delete(`/rentals/${rentalId}`, {
      headers: { Authorization: `Bearer ${token.value}` }
    })
    rentals.value = rentals.value.filter(r => r.id !== rentalId)
    showMessage('Аренда отменена', 'success')
  } catch (err) {
    console.error(err)
    showMessage(err.response?.data?.message || 'Ошибка при отмене аренды', 'error')
  }
}

onMounted(() => {
  loadRentals()
  AOS.init({ duration: 700, once: true })
})

const rentalStatus = (rental) => {
  const today = new Date().toISOString().split('T')[0]
  if (today < rental.start_date) return 'Запланировано'
  if (today >= rental.start_date && today <= rental.end_date) return 'Активно'
  return 'Завершено'
}

const rentalStatusClass = (rental) => {
  const status = rentalStatus(rental)
  if (status === 'Активно') return 'badge bg-success'
  if (status === 'Запланировано') return 'badge bg-primary'
  return 'badge bg-secondary'
}

const formatDate = (dateStr) => {
  const date = new Date(dateStr)
  const day = String(date.getDate()).padStart(2, '0')
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const year = date.getFullYear()
  return `${day}.${month}.${year}`
}
</script>

<style scoped>
.my-rentals-page {
  background: #0d0f14;
  min-height: 100vh;
  padding: 2rem;
  color: #e5e7eb;
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

.big-card {
  background: #1b1f27;
  border-radius: 24px;
  padding: 3rem;
  max-width: 1200px;
  margin: 0 auto;
  box-shadow: 0 15px 40px rgba(0,0,0,0.6);
}

.rental-cards {
  display: flex;
  flex-direction: column;
}

.car-card {
  background: #2a2c36;
  border-radius: 20px;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  box-shadow: 0 10px 25px rgba(0,0,0,0.5);
  transition: transform 0.3s, box-shadow 0.3s;
}

.car-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 15px 35px rgba(37, 99, 235, 0.5);
}

.rental-img {
  width: 120px;
  height: 80px;
  object-fit: cover;
  border-radius: 12px;
  transition: transform 0.3s;
}

.rental-img:hover {
  transform: scale(1.1);
}

.status-badge {
  font-size: 0.95rem;
  padding: 0.55em 0.85em;
  font-weight: 600;
}

.cancel-btn {
  font-size: 0.85rem;
  padding: 0.35em 0.75em;
  transition: transform 0.2s, box-shadow 0.2s;
  background: #dc2626;
  color: #fff;
  border: none;
  border-radius: 12px;
}

.cancel-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.cancel-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(220,38,38,0.4);
}

.text-muted {
  font-size: 0.95rem;
  color: #9ca3af !important;
}

.spinner-border {
  width: 3rem;
  height: 3rem;
  color: #2563eb;
}
</style>
