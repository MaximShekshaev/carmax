<template>
  <transition name="fade">
    <div v-if="visible" class="overlay" @click.self="closePopup">

      <div class="popup">

        <button class="close-btn" @click="closePopup">✕</button>

        <h2 class="title">Персональное предложение</h2>
        <p class="subtitle">Система подобрала оптимальную скидку</p>

        <div class="discount">
          -{{ discount }}%
        </div>

        <div class="text">
          <p>{{ aiMessage }}</p>
        </div>

        <ul class="list">
          <li>✔ Прозрачные условия аренды</li>
          <li>✔ Проверенные автомобили</li>
          <li>✔ Быстрое оформление</li>
        </ul>

        <div class="timer">
          Закроется через {{ timeLeft }} сек
        </div>

        <button class="btn" @click="closePopup">
          Понятно
        </button>

      </div>

    </div>
  </transition>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const visible = ref(true)
const timeLeft = ref(12)

const discount = ref(0)
const aiMessage = ref("")

const emit = defineEmits(['close'])

function closePopup() {
  visible.value = false
  emit('close')
}

function generateAIDiscount() {
  const hour = new Date().getHours()
  const visited = parseInt(localStorage.getItem("visit_count") || "1")

  let base = Math.floor(Math.random() * 20) + 15

  if (visited > 3) base += 8
  if (hour >= 18 || hour <= 6) base += 5

  if (base > 50) base = 50

  discount.value = base

  if (base >= 40) {
    aiMessage.value = "Премиальное предложение доступно для вас сейчас"
  } else if (base >= 30) {
    aiMessage.value = "Оптимальная скидка рассчитана системой"
  } else {
    aiMessage.value = "Базовое выгодное предложение для аренды"
  }
}

onMounted(() => {
  const count = parseInt(localStorage.getItem("visit_count") || "0") + 1
  localStorage.setItem("visit_count", count)

  generateAIDiscount()

  const interval = setInterval(() => {
    timeLeft.value--

    if (timeLeft.value <= 0) {
      clearInterval(interval)
      closePopup()
    }
  }, 1000)
})
</script>

<style scoped>
.overlay {
  position: fixed;
  inset: 0;
  background: rgba(15, 15, 18, 0.75);
  display: flex;
  justify-content: center;
  align-items: center;
  backdrop-filter: blur(10px);
  z-index: 99999;
}

.popup {
  width: 390px;
  background: linear-gradient(180deg, #ffffff, #f7f8fa);
  border-radius: 18px;
  padding: 28px;
  text-align: center;
  position: relative;
  border: 1px solid #e6e6e6;
  box-shadow: 0 30px 80px rgba(0, 0, 0, 0.25);
  animation: pop 0.25s ease;
}

.close-btn {
  position: absolute;
  top: 12px;
  right: 14px;
  border: none;
  background: transparent;
  font-size: 18px;
  cursor: pointer;
  color: #444;
}

.title {
  font-size: 18px;
  font-weight: 600;
  color: #1c1c1c;
  letter-spacing: 0.5px;
}

.subtitle {
  font-size: 13px;
  color: #777;
  margin-bottom: 10px;
}

.discount {
  font-size: 54px;
  font-weight: 700;
  color: #111;
  margin: 15px 0;
  letter-spacing: -1px;
}

.text p {
  color: #555;
  font-size: 14px;
}

.list {
  list-style: none;
  padding: 0;
  margin: 15px 0;
}

.list li {
  margin: 6px 0;
  color: #333;
  font-size: 13px;
}

.timer {
  font-size: 12px;
  color: #888;
  margin: 12px 0;
}

.btn {
  width: 100%;
  padding: 12px;
  border: 1px solid #111;
  background: #111;
  color: #fff;
  border-radius: 10px;
  font-weight: 500;
  cursor: pointer;
  transition: 0.2s ease;
  letter-spacing: 0.3px;
}

.btn:hover {
  background: #fff;
  color: #111;
}

@keyframes pop {
  from {
    transform: scale(0.85);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>