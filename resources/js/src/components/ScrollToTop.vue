<template>
  <transition name="fade-bounce">
    <button 
      v-show="visible" 
      class="scroll-to-top"
      @click="scrollTop"
      aria-label="Наверх"
    >
      <span class="arrow-icon">↑</span>
    </button>
  </transition>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const visible = ref(false)

const checkScroll = () => {
  visible.value = window.scrollY > 300 
}

const scrollTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

onMounted(() => {
  window.addEventListener('scroll', checkScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', checkScroll)
})
</script>

<style scoped>
/* КНОПКА «НАВЕРХ» (Минималистичный modern-flat стиль) */
.scroll-to-top {
  position: fixed;
  right: 30px;
  bottom: 110px; /* было 30px */
  width: 46px;
  height: 46px;
  background-color: #ffffff;
  color: #111827;
  border: 1px solid #e5e7eb;
  border-radius: 50%;
  font-size: 20px;
  cursor: pointer;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);

  z-index: 100000; /* выше AI */

  display: flex;
  align-items: center;
  justify-content: center;

  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.arrow-icon {
  display: inline-block;
  transform: translateY(-1px); /* Оптическое выравнивание стрелки */
  transition: transform 0.2s ease;
}

/* ИНТЕРАКТИВНЫЙ ХОВЕР */
.scroll-to-top:hover {
  background-color: #111827;
  color: #ffffff;
  border-color: #111827;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

.scroll-to-top:hover .arrow-icon {
  transform: translateY(-3px); /* Эффект легкого взлета стрелки */
}

/* ПЛАВНАЯ АНИМАЦИЯ ПОЯВЛЕНИЯ КНОПКИ */
.fade-bounce-enter-active {
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.fade-bounce-leave-active {
  transition: all 0.2s cubic-bezier(0.36, 0.07, 0.19, 0.97);
}

.fade-bounce-enter-from {
  opacity: 0;
  transform: scale(0.5) translateY(10px);
}
.fade-bounce-leave-to {
  opacity: 0;
  transform: scale(0.8);
}
</style>