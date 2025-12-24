<template>
  <div class="welcome-page d-flex flex-column justify-content-center align-items-center text-center">
    
    <!-- Декоративные линии -->
    <div class="line line-left"></div>
    <div class="line line-right"></div>

    <h1 class="welcome-title mb-3" data-aos="fade-down">
      Аренда автомобилей
    </h1>

    <p class="welcome-text mb-4" data-aos="fade-up" data-aos-delay="150">
      Нам доверяют более 1000 клиентов по всему Казахстану. Присоединяйтесь к ним и наслаждайтесь поездками на наших надежных автомобилях. 
    
    </p>

   
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import AOS from 'aos'
import 'aos/dist/aos.css'

onMounted(() => {
  AOS.init({
    duration: 800,
    once: true,
    easing: 'ease-out-cubic'
  })
})
</script>

<style scoped>

.welcome-page {
  width: 100%;
  height: 100%;
  background: radial-gradient(circle at top, #1b1f27, #0d0f14);
  text-align: center;
  position: relative;
  overflow: hidden;
}


.welcome-title {
  font-size: 3.2rem;
  font-weight: 800;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: #ffffff;
  z-index: 2;
}

/* ТЕКСТ */
.welcome-text {
  font-size: 1.25rem;
  color: #9ca3af;
  max-width: 650px;
  z-index: 2;
}

/* КНОПКА */
.btn-accent {
  background: linear-gradient(135deg, #2563eb, #1e40af);
  color: #fff;
  border: none;
  border-radius: 50px;
  font-weight: 600;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
}

.btn-accent:hover {
  background: linear-gradient(135deg, #1e40af, #2563eb);
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(37, 99, 235, 0.4);
}

/* ДЕКОРАТИВНЫЕ ЛИНИИ */
.line {
  position: absolute;
  width: 2px;
  height: 100%;
  background: linear-gradient(
    to bottom,
    transparent,
    rgba(37, 99, 235, 0.5),
    transparent
  );
}

.line-left {
  left: 8%;
}

.line-right {
  right: 8%;
}
</style>
