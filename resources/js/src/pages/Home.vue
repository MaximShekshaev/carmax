<template>
  <main class="flex-fill py-5 bg-light">
    <div class="container">
      <h1 class="mb-4 text-center">Аренда автомобилей</h1>
      
      <!-- Список машин с бэкенда -->
      <CarList :cars="cars" />
      
      <!-- Условия аренды -->
      <Conditions :conditions="conditions" />
      
      <!-- Отзывы клиентов -->
      <Reviews :reviews="reviews" />
      
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
import Contact from '../components/Contact.vue';

// Данные для машин
const cars = ref([]);

// Условия аренды
const conditions = ref([
  { icon: 'bi bi-clock-history', title: 'Круглосуточная поддержка', description: 'Мы работаем 24 часа, 7 дней в неделю без выходных' },
  { icon: 'bi bi-car-front', title: 'Личный автопарк', description: 'У нас вы найдете автомобили как эконом класса так и премиум класса' },
  { icon: 'bi bi-stopwatch', title: 'Быстрое оформление', description: 'Оформление документов займет 10 минут. Минимальный пакет документов' },
  { icon: 'bi bi-truck', title: 'Доставка авто', description: 'Доставим автомобиль в любую часть города' },
]);

// Отзывы
const reviews = ref([
  { id: 1, name: 'Алексей П.', avatar: 'https://via.placeholder.com/50', rating: 5, text: 'Отличная компания! Машины всегда в отличном состоянии, обслуживание на высшем уровне.' },
  { id: 2, name: 'Мария С.', avatar: 'https://via.placeholder.com/50', rating: 4, text: 'Быстро оформили аренду, персонал вежливый. Советую для поездок по городу.' },
  { id: 3, name: 'Игорь К.', avatar: 'https://via.placeholder.com/50', rating: 5, text: 'Очень удобный сервис. Машину доставили прямо к дому, всё прошло без проблем.' },
]);

// Загрузка данных об автомобилях с бэкенда (API)
onMounted(async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/cars');
    cars.value = response.data;
  } catch (error) {
    console.error("Ошибка загрузки данных о машинах", error);
  }
});
</script>
