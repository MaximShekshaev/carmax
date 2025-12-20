<template>
  <main class="flex-fill py-5 bg-light">
    <div class="container">
      <h1 class="mb-5 text-center fw-bold text-primary">Аренда автомобилей</h1>
      
      <!-- Список машин с бэкенда -->
      <CarList :cars="cars" />
      
      <!-- Условия аренды -->
      <Conditions :conditions="Conditions" />
      
      <!-- Отзывы клиентов -->
      <Reviews :reviews="Reviews" />
      
      <!-- Контактная информация -->
      <Contact />
    </div>
  </main>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import CarList from '../components/CarList.vue';
import Conditions from '../components/Conditions.vue';
import Reviews from '../components/Reviews.vue';
import Contact from '../components/Faqs.vue';

// Данные для машин
const cars = ref([]);

onMounted(async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/cars');
    cars.value = response.data;
  } catch (error) {
    console.error("Ошибка загрузки данных о машинах", error);
  }
});
</script>
