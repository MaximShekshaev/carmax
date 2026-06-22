<template>
  <div class="my-rentals-page py-5">
    
    <!-- Toast сообщение (Минималистичный дизайн) -->
    <div 
      v-if="message.text" 
      class="toast-message animate__animated animate__fadeInDown" 
      :class="message.type"
    >
      <i v-if="message.type === 'success'" class="bi bi-check-circle-fill text-success me-2"></i>
      <i v-else class="bi bi-exclamation-circle-fill text-danger me-2"></i>
      {{ message.text }}
    </div>

    <!-- ГЛАВНЫЙ КОНТЕЙНЕР ДАШБОРДА -->
    <div class="big-card p-5" data-aos="fade-up">
      <h2 class="mb-5 page-title">Мои аренды</h2>

      <!-- СТАТИСТИКА -->
<div class="stats-grid mb-5">
  <div class="stat-card">
    <i class="bi bi-car-front"></i>
    <div>
      <span>{{ totalRentals }}</span>
      <p>Всего аренд</p>
    </div>
  </div>

  <div class="stat-card">
    <i class="bi bi-cash-stack"></i>
    <div>
      <span>{{ totalSpent.toLocaleString() }} ₸</span>
      <p>Потрачено</p>
    </div>
  </div>

  <div class="stat-card">
    <i class="bi bi-calendar-check"></i>
    <div>
      <span>{{ activeRentals }}</span>
      <p>Активных</p>
    </div>
  </div>

  <div class="stat-card">
    <i class="bi bi-award"></i>
    <div>
      <span>{{ loyaltyLevel }}</span>
      <p>Статус клиента</p>
    </div>
  </div>
</div>

      <!-- Лондинг-спиннер -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-loader" role="status">
          <span class="visually-hidden">Загрузка...</span>
        </div>
      </div>

      <div v-else>
        <!-- Пустое состояние -->
        <div v-if="rentals.length === 0" class="text-center text-muted py-5 empty-state">
          <i class="bi bi-calendar-x mb-3 d-block text-gray-400" style="font-size: 2.5rem;"></i>
          У вас пока нет активных или завершенных аренд.
        </div>

        <!-- Список карточек бронирования -->
        <div v-else class="rental-cards">
          <div
            v-for="rental in rentals"
            :key="rental.id"
            class="car-card p-4 mb-4"
            data-aos="zoom-in"
          >
            <div class="d-flex align-items-center flex-wrap gap-3 mb-4">
              <img
                :src="rental.car.image"
                :alt="rental.car.name"
                class="rental-img"
              />
              <div class="flex-grow-1">
                <h5 class="car-name mb-1">{{ rental.car.brand }} <span class="fw-normal text-secondary">{{ rental.car.name }}</span></h5>
                <span class="price-per-day">Цена за сутки: {{ rental.car.price_per_day.toLocaleString() }} ₸</span>
              </div>
              <div>
                <!-- Кастомный статус -->
                <span :class="['status-badge', rentalStatusClass(rental)]">
                  {{ rentalStatus(rental) }}
                </span>
              </div>
            </div>

            <!-- Детализация дат и цен -->
            <div class="rental-details border-top pt-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
              <div class="d-flex gap-4 info-dates">
                <div>
                  <span class="info-label">Дата начала</span>
                  <div class="info-value">{{ formatDate(rental.start_date) }}</div>
                </div>
                <div>
                  <span class="info-label">Дата окончания</span>
                  <div class="info-value">{{ formatDate(rental.end_date) }}</div>
                </div>
              </div>
              
              <div class="text-end me-md-4">
                <span class="info-label">Общая стоимость</span>
                <div class="total-price-value">{{ rental.total_price.toLocaleString() }} ₸</div>
              </div>
            </div>

            <!-- Действия -->
            <div class="d-flex justify-content-end align-items-center border-top mt-3 pt-3">
              <button
                class="btn btn-cancel-rental"
                :disabled="rentalStatus(rental) === 'Завершено'"
                @click="cancelRental(rental.id)"
              >
                Отменить бронирование
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
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
  if (!confirm('Вы уверены, что хотите отменить бронирование?')) return

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
  if (status === 'Активно') return 'status-active'
  if (status === 'Запланировано') return 'status-planned'
  return 'status-completed'
}

const formatDate = (dateStr) => {
  const date = new Date(dateStr)
  const day = String(date.getDate()).padStart(2, '0')
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const year = date.getFullYear()
  return `${day}.${month}.${year}`
}


const stats = ref(
  JSON.parse(localStorage.getItem('carmax_stats')) || {
    totalSpent: 0,
    totalRentals: 0
  }
)

const totalRentals = computed(() => rentals.value.length)

const totalSpent = computed(() => {
  return rentals.value.reduce(
    (sum, rental) => sum + Number(rental.total_price || 0),
    0
  )
})

const activeRentals = computed(() => {
  return rentals.value.filter(
    rental => rentalStatus(rental) === 'Активно'
  ).length
})

const loyaltyLevel = computed(() => {
  const count = totalRentals.value

  if (count >= 15) return 'VIP'
  if (count >= 8) return 'Gold'
  if (count >= 3) return 'Silver'

  return 'New'
})

watch(
  [totalRentals, totalSpent],
  () => {
    localStorage.setItem(
      'carmax_stats',
      JSON.stringify({
        totalRentals: totalRentals.value,
        totalSpent: totalSpent.value
      })
    )
  },
  { immediate: true }
)
</script>

<style scoped>
.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
}

.stat-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  padding: 18px;
  display: flex;
  align-items: center;
  gap: 15px;
  transition: .3s;
}

.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 25px rgba(0,0,0,.05);
}

.stat-card i {
  font-size: 28px;
  color: #111827;
}

.stat-card span {
  display: block;
  font-size: 1.2rem;
  font-weight: 700;
  color: #111827;
}

.stat-card p {
  margin: 0;
  color: #6b7280;
  font-size: .85rem;
}

@media (max-width: 992px) {
  .stats-grid {
    grid-template-columns: repeat(2,1fr);
  }
}

@media (max-width: 576px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }
}

.my-rentals-page {
  background-color: #f9fafb;
  min-height: 100vh;
  color: #111827;
}

/* ЗАГОЛОВОК */
.page-title {
  font-size: 1.8rem;
  font-weight: 700;
  letter-spacing: -0.5px;
  color: #111827;
}

/* ЭЛЕГАНТНЫЕ ТОСТЫ */
.toast-message {
  position: fixed;
  top: 30px;
  left: 50%;
  transform: translateX(-50%);
  padding: 14px 28px;
  border-radius: 10px;
  font-weight: 500;
  font-size: 0.95rem;
  z-index: 9999;
  min-width: 300px;
  text-align: center;
  color: #111827;
  background: #ffffff;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  border: 1px solid rgba(0, 0, 0, 0.05);
  display: flex;
  align-items: center;
  justify-content: center;
  pointer-events: none;
}
.toast-message.success { border-bottom: 3px solid #10b981; }
.toast-message.error { border-bottom: 3px solid #ef4444; }

/* БОЛЬШАЯ БЕЛАЯ ПАНЕЛЬ ДАШБОРДА */
.big-card {
  background: #ffffff;
  border: 1px solid rgba(0, 0, 0, 0.05);
  border-radius: 16px;
  max-width: 1000px;
  margin: 0 auto;
  box-shadow: 0 4px 25px rgba(0, 0, 0, 0.02);
}

/* КАРТОЧКА АВТОМОБИЛЯ В СПИСКЕ */
.car-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.car-card:hover {
  transform: translateY(-4px);
  border-color: rgba(17, 24, 39, 0.15);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
}

/* ИЗОБРАЖЕНИЕ АВТО */
.rental-img {
  width: 110px;
  height: 70px;
  object-fit: cover;
  border-radius: 8px;
  background-color: #f3f4f6;
}

.car-name {
  font-size: 1.1rem;
  font-weight: 700;
  color: #111827;
}

.price-per-day {
  font-size: 0.85rem;
  color: #6b7280;
}

/* ТРЕНДОВЫЕ МИНИМАЛИСТИЧНЫЕ СТАТУС-БЕЙДЖИ */
.status-badge {
  font-size: 0.8rem;
  font-weight: 600;
  padding: 6px 14px;
  border-radius: 20px;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

.status-active {
  background-color: #ecfdf5;
  color: #065f46;
}

.status-planned {
  background-color: #eff6ff;
  color: #1e40af;
}

.status-completed {
  background-color: #f3f4f6;
  color: #374151;
}

/* ДЕТАЛИЗАЦИЯ ИНФОРМАЦИИ */
.border-top {
  border-top: 1px solid #f3f4f6 !important;
}

.info-label {
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: #9ca3af;
  display: block;
  margin-bottom: 2px;
}

.info-value {
  font-size: 0.95rem;
  font-weight: 600;
  color: #374151;
}

.total-price-value {
  font-size: 1.2rem;
  font-weight: 700;
  color: #111827;
}

/* КНОПКА ОТМЕНЫ (Утонченный деструктивный экшен) */
.btn-cancel-rental {
  background: transparent;
  border: 1px solid #f3f4f6;
  color: #ef4444;
  font-size: 0.85rem;
  font-weight: 500;
  padding: 8px 16px;
  border-radius: 6px;
  transition: all 0.2s ease;
}

.btn-cancel-rental:hover:not(:disabled) {
  background-color: #fef2f2;
  border-color: #fca5a5;
}

.btn-cancel-rental:disabled {
  display: none; /* Прячем кнопку отмены, если поездка завершена */
}

/* СПИННЕР И ПУСТЫЕ СОСТОЯНИЯ */
.empty-state {
  font-size: 0.95rem;
  color: #6b7280;
}

.spinner-loader {
  display: inline-block;
  width: 2.5rem;
  height: 2.5rem;
  border: 3px solid #e5e7eb;
  border-radius: 50%;
  border-top-color: #111827;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>