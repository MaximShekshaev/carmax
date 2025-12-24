<template>
  <main class="main-dark">
    <!-- Welcome -->
    <section class="welcome-wrapper">
      <Welcome />
    </section>

    <div class="container py-5">
      <!-- Автопарк -->
      <section id="car-list" class="section-dark">
        <h2 class="section-title" data-aos="fade-up">
          Наш автопарк
        </h2>
        <CarList :cars="cars" />
      </section>

      <!-- Условия -->
      <section class="section-dark">
        <h2 class="section-title" data-aos="fade-up">
          Условия аренды
        </h2>
        <Conditions :conditions="Conditions" />
      </section>

      <!-- Отзывы -->
      <section class="section-dark">
        <h2 class="section-title" data-aos="fade-up">
          Отзывы клиентов
        </h2>
        <Reviews :reviews="Reviews" />
      </section>

      <!-- FAQ -->
      <section class="section-dark">
        <h2 class="section-title" data-aos="fade-up">
          Частые вопросы
        </h2>
        <Faqs />
      </section>
    </div>
  </main>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AOS from 'aos';
import 'aos/dist/aos.css';

import CarList from '../components/CarList.vue';
import Conditions from '../components/Conditions.vue';
import Reviews from '../components/Reviews.vue';
import Faqs from '../components/Faqs.vue';
import Welcome from '../components/Welcome.vue';

// Данные для машин
const cars = ref([]);

onMounted(async () => {
  // Инициализация AOS для анимаций
  AOS.init({ duration: 800, once: true });

  try {
    const response = await axios.get('http://localhost:8000/api/cars');
    cars.value = response.data;
  } catch (error) {
    console.error("Ошибка загрузки данных о машинах", error);
  }
});

// Функция плавной прокрутки
const scrollToCars = () => {
  const el = document.getElementById('car-list');
  if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
</script>

<style scoped>
.main-dark {
  min-height: 100vh;
  background: radial-gradient(circle at top, #1b1f27, #0d0f14);
  color: #e5e7eb;
}

/* WELCOME */
.welcome-wrapper {
  height: 100vh;
  width: 100%;
  background: radial-gradient(circle at top, #1b1f27, #0d0f14);
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  overflow: hidden;
}

/* СЕКЦИИ */
.section-dark {
  margin-top: 5rem;
  padding: 3rem 2rem;
  border-radius: 24px;
  background: linear-gradient(160deg, #141821, #0f1218);
  box-shadow:
    0 20px 60px rgba(0, 0, 0, 0.6),
    inset 0 1px 0 rgba(255, 255, 255, 0.04);
}

/* ЗАГОЛОВКИ */
.section-title {
  text-align: center;
  margin-bottom: 3rem;
  font-size: 2.2rem;
  font-weight: 800;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: #ffffff;
  position: relative;
}

/* ЛИНИЯ ПОД ЗАГОЛОВКОМ */
.section-title::after {
  content: '';
  display: block;
  width: 80px;
  height: 4px;
  margin: 16px auto 0;
  background: linear-gradient(90deg, #2563eb, #1e40af);
  border-radius: 10px;
}

/* SCROLL OFFSET */
section {
  scroll-margin-top: 90px;
}

/* AOS FIX */
[data-aos] {
  will-change: transform, opacity;
}
</style>
