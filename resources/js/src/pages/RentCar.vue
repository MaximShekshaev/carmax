<template>
  <div class="rent-car-page d-flex justify-content-center align-items-center min-vh-100 p-4">
    
    <!-- Toast сообщение -->
    <div 
      v-if="message.text" 
      class="toast-message animate__animated animate__fadeInDown" 
      :class="message.type"
    >
      <i v-if="message.type === 'success'" class="bi bi-check-circle-fill text-success me-2"></i>
      <i v-else class="bi bi-exclamation-circle-fill text-danger me-2"></i>
      {{ message.text }}
    </div>

    <!-- КАРТОЧКА ОФОРМЛЕНИЯ АРЕНДЫ -->
    <div class="card rent-card p-5">
      <h3 class="mb-4 text-center rent-title">
        Аренда: {{ car.brand }} <span class="fw-normal text-secondary">{{ car.name }}</span>
      </h3>

      <div class="car-image-wrapper mb-4 text-center">
        <img :src="car.image" :alt="car.name" class="car-image" />
      </div>

      <div class="mb-4 text-center daily-price-badge" v-if="car.price_per_day">
        <span class="label text-muted small text-uppercase fw-semibold me-1">Цена за сутки:</span>
        <span class="value">{{ car.price_per_day.toLocaleString() }} ₸</span>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-sm-6">
          <label class="form-label custom-label">Дата начала</label>
          <input type="date" v-model="startDate" class="form-control light-input" />
        </div>

        <div class="col-sm-6">
          <label class="form-label custom-label">Дата окончания</label>
          <input type="date" v-model="endDate" class="form-control light-input" />
        </div>
      </div>

      <div v-if="startDate && endDate && rentalDays > 0" class="mb-4 total-price-box p-3 text-center">
        <span class="d-block text-muted small text-uppercase mb-1">Итоговый расчет за {{ rentalDays }} {{ rentalDays === 1 ? 'день' : 'дней' }}</span>
        <div class="total-price-value">{{ totalPrice.toLocaleString() }} ₸</div>
      </div>

      <button class="btn btn-submit-rent w-100 py-3" @click="openPaymentModal" :disabled="loading">
        Перейти к оплате
      </button>
    </div>

    <!-- УВЕЛИЧЕННОЕ МОДАЛЬНОЕ ОКНО ОПЛАТЫ (НА ВЕСЬ ЭКРАН НА МОБИЛЬНЫХ) -->
    <div v-if="showPayment" class="modal-overlay d-flex justify-content-center align-items-center" @click.self="closePaymentModal">
      <div class="modal-card-large p-4 p-md-5 animate__animated animate__zoomIn">
        
        <!-- Шапка модалки -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
          <h4 class="modal-title m-0">Безопасная оплата картой</h4>
          <button class="btn-close-modal" @click="closePaymentModal" :disabled="loading">&times;</button>
        </div>

        <div class="row g-4 align-items-center">
          
          <!-- ЛЕВАЯ КОЛОНКА: Визуализация карты и сумма -->
          <div class="col-lg-6 d-flex flex-column align-items-center justify-content-center text-center">
            
            <!-- Интерактивная карта -->
            <div class="credit-card-view w-100 mb-4" :class="cardSystemClass">
              <div class="card-system-logo">
                <span v-if="cardSystemClass === 'visa'">Visa</span>
                <span v-else-if="cardSystemClass === 'mastercard'">Mastercard</span>
                <span v-else>Card</span>
              </div>
              <div class="card-chip"></div>
              <div class="card-number-view">
                {{ formattedCardNumber || '•••• •••• •••• ••••' }}
              </div>
              <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="card-holder-view text-start">
                  <span class="card-mini-label">CARDHOLDER</span>
                  <div class="card-mini-value">VALUED CUSTOMER</div>
                </div>
                <div class="card-expiry-view text-end">
                  <span class="card-mini-label">EXPIRES</span>
                  <div class="card-mini-value">{{ cardFields.expiry || 'MM/YY' }}</div>
                </div>
              </div>
            </div>

            <!-- Сумма к списанию -->
            <div class="payment-summary w-100 p-4">
              <span class="text-muted small text-uppercase d-block mb-1">Сумма к списанию</span>
              <span class="payment-amount">{{ totalPrice.toLocaleString() }} ₸</span>
            </div>
          </div>

          <!-- ПРАВАЯ КОЛОНКА: Поля формы ввода -->
          <div class="col-lg-6">
            <form @submit.prevent="confirmRent" class="h-100 d-flex flex-column justify-content-between">
              <div>
                <div class="mb-4">
                  <label class="form-label custom-label mb-2">Номер карты</label>
                  <input 
                    type="text" 
                    class="form-control light-input py-3" 
                    placeholder="0000 0000 0000 0000" 
                    :value="formattedCardNumber"
                    @input="handleCardNumberInput"
                    maxlength="19"
                    required 
                  />
                </div>

                <div class="row g-3 mb-5">
                  <div class="col-6">
                    <label class="form-label custom-label mb-2">Срок действия</label>
                    <input 
                      type="text" 
                      class="form-control light-input py-3" 
                      placeholder="MM/YY" 
                      :value="cardFields.expiry"
                      @input="handleExpiryInput"
                      maxlength="5"
                      required 
                    />
                  </div>
                  <div class="col-6">
                    <label class="form-label custom-label mb-2">CVC / CVV</label>
                    <input 
                      type="text" 
                      class="form-control light-input py-3" 
                      placeholder="000" 
                      :value="cardFields.cvc"
                      @input="handleCvcInput"
                      maxlength="3"
                      required 
                    />
                  </div>
                </div>
              </div>

              <button type="submit" class="btn btn-submit-rent w-100 py-3 btn-lg" :disabled="loading">
                {{ loading ? "Обработка платежа..." : "Оплатить сейчас" }}
              </button>
            </form>
          </div>

        </div>
      </div>
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

// Модалка и поля карты
const showPayment = ref(false)
const cardFields = ref({ number: '', expiry: '', cvc: '' })

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

// --- ФИЛЬТРАЦИЯ И МАСКИ ДЛЯ ВВОДА КАРТЫ ---

const formattedCardNumber = computed(() => {
  return cardFields.value.number.replace(/(\d{4})(?=\d)/g, '$1 ')
})

const cardSystemClass = computed(() => {
  const firstDigit = cardFields.value.number.charAt(0)
  if (firstDigit === '4') return 'visa'
  if (firstDigit === '5') return 'mastercard'
  return 'default'
})

const handleCardNumberInput = (e) => {
  let value = e.target.value.replace(/\D/g, '')
  if (value.length > 16) value = value.slice(0, 16)
  cardFields.value.number = value
}

const handleExpiryInput = (e) => {
  let value = e.target.value.replace(/\D/g, '')
  if (value.length > 4) value = value.slice(0, 4)
  if (value.length > 2) {
    value = value.slice(0, 2) + '/' + value.slice(2)
  }
  cardFields.value.expiry = value
}

const handleCvcInput = (e) => {
  let value = e.target.value.replace(/\D/g, '')
  if (value.length > 3) value = value.slice(0, 3)
  cardFields.value.cvc = value
}

// --- УПРАВЛЕНИЕ ОКОННЫМИ ПРОЦЕССАМИ ---

const openPaymentModal = () => {
  if (!startDate.value || !endDate.value) {
    return showMessage('Выберите даты аренды', 'error')
  }
  if (rentalDays.value <= 0) {
    return showMessage('Дата окончания должна быть позже даты начала', 'error')
  }
  showPayment.value = true
}

const closePaymentModal = () => {
  if (loading.value) return
  showPayment.value = false
  cardFields.value = { number: '', expiry: '', cvc: '' }
}

const confirmRent = async () => {
  if (cardFields.value.number.length < 16 || cardFields.value.expiry.length < 5 || cardFields.value.cvc.length < 3) {
    return showMessage('Неверно заполнены данные карты', 'error')
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
    
    showMessage('Оплата прошла успешно! Аренда оформлена.', 'success')
    showPayment.value = false
    
    setTimeout(() => router.push('/my-rentals'), 1500)
  } catch (err) {
    console.error(err.response?.data)
    showMessage(err.response?.data?.message || 'Ошибка при проведении платежа', 'error')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* ГЛОБАЛЬНЫЙ СВЕТЛЫЙ СТИЛЬ */
.rent-car-page {
  background-color: #f9fafb;
  min-height: 100vh;
}

/* ТОСТЫ */
.toast-message {
  position: fixed;
  top: 30px;
  left: 50%;
  transform: translateX(-50%);
  padding: 14px 28px;
  border-radius: 10px;
  font-weight: 500;
  font-size: 0.95rem;
  z-index: 10000;
  min-width: 300px;
  text-align: center;
  color: #111827;
  background: #ffffff;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  border: 1px solid rgba(0, 0, 0, 0.05);
  display: flex;
  align-items: center;
  justify-content: center;
}
.toast-message.success { border-bottom: 3px solid #10b981; }
.toast-message.error { border-bottom: 3px solid #ef4444; }

/* КАРТОЧКА ЗАКАЗА */
.rent-card {
  max-width: 520px;
  width: 100%;
  border-radius: 16px;
  background: #ffffff;
  border: 1px solid rgba(0, 0, 0, 0.05);
  box-shadow: 0 10px 35px rgba(0, 0, 0, 0.03);
}

.rent-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #111827;
}

.car-image-wrapper {
  background-color: #f3f4f6;
  border-radius: 12px;
  padding: 10px;
}

.car-image {
  width: 100%;
  max-height: 200px;
  object-fit: contain;
}

.daily-price-badge .value {
  font-size: 1.15rem;
  font-weight: 700;
  color: #111827;
}

.custom-label {
  font-size: 0.85rem;
  font-weight: 600;
  color: #4b5563;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

.light-input {
  border-radius: 8px;
  background-color: #f9fafb;
  color: #111827;
  border: 1px solid #e5e7eb;
  padding: 10px 14px;
}

.total-price-box {
  background-color: #f9fafb;
  border: 1px dashed #e5e7eb;
  border-radius: 8px;
}

.total-price-value {
  font-size: 1.4rem;
  font-weight: 800;
  color: #111827;
}

.btn-submit-rent {
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  background: #111827;
  color: #ffffff;
  border: 1px solid #111827;
  transition: all 0.2s ease;
}

.btn-submit-rent:hover:not([disabled]) {
  background: #ffffff;
  color: #111827;
}

.btn-submit-rent:disabled {
  background: #f3f4f6;
  border-color: #e5e7eb;
  color: #9ca3af;
  cursor: not-allowed;
}

/* МОДАЛЬНОЕ ОКНО ПОВЫШЕННОГО РАЗМЕРА И FULLSCREEN НА МОБИЛЬНЫХ */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.45);
  backdrop-filter: blur(6px);
  z-index: 999;
}

.modal-card-large {
  background: #ffffff;
  width: 100%;
  height: 100%; /* По умолчанию на весь экран */
  max-width: 100vw;
  max-height: 100vh;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
}

/* Медиа-запрос для планшетов и ПК: модалка превращается в большую аккуратную карточку */
@media (min-width: 768px) {
  .modal-card-large {
    width: 90%;
    max-width: 850px;
    height: auto;
    max-height: 90vh;
    border-radius: 24px;
    box-shadow: 0 30px 70px rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(0, 0, 0, 0.05);
  }
}

.modal-title {
  font-weight: 700;
  color: #111827;
}

.btn-close-modal {
  background: transparent;
  border: none;
  font-size: 2rem;
  color: #9ca3af;
  cursor: pointer;
  line-height: 1;
}

.btn-close-modal:hover {
  color: #111827;
}

/* КАРТА */
.credit-card-view {
  height: 215px;
  max-width: 360px;
  width: 100%; /* Гарантирует, что карта займет всю доступную ширину колонки */
  border-radius: 16px;
  padding: 20px 22px; /* Чуть уменьшили боковые отступы, освобождая место для цифр */
  color: #ffffff;
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
  transition: all 0.4s ease;
  overflow: hidden;
  box-sizing: border-box; /* Чтобы padding не раздувал карту наружу */
  
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

.card-number-view {
  /* Уменьшили размер с 1.55rem до 1.35rem, чтобы строка не ломалась */
  font-size: 1.35rem; 
  font-weight: 700;
  /* Набор моноширинных шрифтов */
  font-family: 'SF Mono', SFMono-Regular, Consolas, 'Liberation Mono', Menlo, Courier, monospace;
  /* Чуть уменьшили интервал (был 3px), теперь цифры стоят плотнее и ровнее */
  letter-spacing: 2px; 
  margin-top: 25px;
  margin-bottom: 10px;
  text-align: left; /* Выравнивание по левому краю смотрится на картах аккуратнее */
  
  /* ЖЕСТКИЙ ЗАПРЕТ НА ПЕРЕНОС СТРОКИ */
  white-space: nowrap; 
  width: 100%;
  
  /* Эффект объема */
  text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.6), 1px 1px 1px rgba(255, 255, 255, 0.2);
  color: #f9fafb;
}
/* Градиенты для платежных систем */
.credit-card-view.default {
  background: linear-gradient(135deg, #374151 0%, #111827 100%);
}
.credit-card-view.visa {
  background: linear-gradient(135deg, #1d4ed8 0%, #1e3a8a 100%);
}
.credit-card-view.mastercard {
  background: linear-gradient(135deg, #6b21a8 0%, #4c1d95 100%);
}

.card-chip {
  width: 48px;
  height: 36px;
  background: linear-gradient(135deg, #ffe066 0%, #f59e0b 100%); /* Сделали чип золотистым для лучшего контраста */
  border-radius: 6px;
  margin-top: 5px;
  box-shadow: inset 0 1px 1px rgba(255,255,255,0.5);
}

.card-system-logo {
  position: absolute;
  top: 24px;
  right: 24px;
  font-weight: 900;
  font-style: italic;
  font-size: 1.4rem;
  letter-spacing: 1px;
  text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}


.card-mini-label {
  font-size: 0.65rem;
  color: rgba(255, 255, 255, 0.7);
  display: block;
  letter-spacing: 1px;
  font-family: system-ui, -apple-system, sans-serif;
}

.card-mini-value {
  font-size: 0.9rem;
  font-weight: 600;
  letter-spacing: 1.5px;
  /* Для срока действия тоже используем моноширинный шрифт */
  font-family: 'SF Mono', SFMono-Regular, Consolas, monospace;
  text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.5), 1px 1px 1px rgba(255, 255, 255, 0.2);
}

.payment-summary {
  background-color: #f9fafb;
  border-radius: 12px;
  border: 1px solid #e5e7eb;
  max-width: 360px;
}

.payment-amount {
  font-size: 1.5rem;
  font-weight: 800;
  color: #10b981;
}
</style>