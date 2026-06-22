<template>
  <main class="main-light">
    <!-- Welcome (Экран приветствия) -->
    <section class="welcome-wrapper">
      <Welcome />
    </section>


    

    <div class="container py-4">
      <!-- Автопарк -->
      <section id="car-list" class="section-layout">
        <h2 class="section-title" data-aos="fade-up">
          Наш автопарк
        </h2>
        <CarList :cars="cars" />
      </section>

      <!-- Условия -->
      <section class="section-layout">
        <h2 class="section-title" data-aos="fade-up">
          Условия аренды
        </h2>
        <Conditions :conditions="Conditions" />
        <RentalSteps />
      </section>

      <!-- Отзывы -->
      <section class="section-layout">
        <h2 class="section-title" data-aos="fade-up">
          Отзывы клиентов
        </h2>
        <Reviews :reviews="Reviews" />
      </section>

      <!-- FAQ -->
      <section class="section-layout mb-5">
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
import RentalStats from '../components/RentalStats.vue';
import RentalSteps from '../components/RentalSteps.vue';
import CarList from '../components/CarList.vue';
import Conditions from '../components/Conditions.vue';
import Reviews from '../components/Reviews.vue';
import Faqs from '../components/Faqs.vue';
import Welcome from '../components/Welcome.vue';

const cars = ref([]);

onMounted(async () => {
  AOS.init({ duration: 800, once: true });

  try {
    const response = await axios.get('http://localhost:8000/api/cars');
    cars.value = response.data;
  } catch (error) {
    console.error("Ошибка загрузки данных о машинах", error);
  }
});

const scrollToCars = () => {
  const el = document.getElementById('car-list');
  if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
</script>

<style scoped>
/* ГЛОБАЛЬНЫЙ СВЕТЛЫЙ БЭКГРАУНД */
.main-light {
  min-height: 100vh;
  background-color: #f9fafb; /* Мягкий трендовый светлый оттенок */
  color: #111827;
}

/* ЭКРАН ПРИВЕТСТВИЯ */
.welcome-wrapper {
  height: 100vh;
  width: 100%;
  background-color: #ffffff; /* Белая основа для Welcome-секции */
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  overflow: hidden;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

/* СЕКЦИИ (Воздушный дизайн без жестких темных рамок) */
.section-layout {
  margin-top: 6rem;
  padding: 2rem 0;
}

/* ЗАГОЛОВКИ СЕКЦИЙ */
.section-title {
  text-align: center;
  margin-bottom: 3.5rem;
  font-size: 2rem;
  font-weight: 700;
  letter-spacing: -0.5px;
  text-transform: uppercase;
  color: #111827;
  position: relative;
}

/* Тонкий благородный разделитель под заголовком */
.section-title::after {
  content: '';
  display: block;
  width: 50px;
  height: 3px;
  margin: 14px auto 0;
  background-color: #111827; /* Графитовый минималистичный штрих */
  border-radius: 2px;
}

/* Скролл-оффсет для фиксированной шапки сайта */
section {
  scroll-margin-top: 100px;
}

[data-aos] {
  will-change: transform, opacity;
}
</style>