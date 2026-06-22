<template>
  <div class="welcome-page d-flex flex-column justify-content-center align-items-center text-center px-4">
    
    <!-- ФОНОВОЕ ВИДЕО -->
    <div class="video-background-container">
      <video 
        autoplay 
        loop 
        muted 
        playsinline 
        poster="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=1920"
        class="bg-video"
      >
        <!-- Ссылка на плавное эстетичное видео (машина в движении/дорога) -->
        <source src="https://assets.mixkit.co/videos/preview/mixkit-luxurious-car-driving-on-a-highway-41611-large.mp4" type="video/mp4" />
        Your browser does not support the video tag.
      </video>
      <!-- Благородный светлый оверлей для читаемости текста -->
      <div class="video-overlay"></div>
    </div>

    <!-- КОНТЕНТ С ПЛАВНОЙ СТУПЕНЧАТОЙ АНИМАЦИЕЙ -->
    <div class="content-layer">
      <h1 class="welcome-title mb-3 animate-fade-slide-up">
        Путешествуйте с комфортом
      </h1>

      <p class="welcome-text mb-5 animate-fade-slide-up delay-1">
        Нам доверяют более 1000 клиентов по всему Казахстану. 
        Присоединяйтесь к ним и наслаждайтесь безупречным сервисом аренды надежных автомобилей.
      </p>

      <div class="animate-fade-slide-up delay-2">
          
      </div>
    </div>

  </div>
</template>

<script setup>

</script>

<style scoped>
/* ГЛАВНЫЙ ЭКРАН */
.welcome-page {
  width: 100%;
  height: 100vh;
  position: relative;
  overflow: hidden;
  background-color: #f9fafb; /* Запасной фон */
}

/* КОНТЕЙНЕР ДЛЯ ВИДЕО */
.video-background-container {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
}

.bg-video {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* МЯГКИЙ СВЕТЛЫЙ ФИЛЬТР ПОД СТИЛЬ MODERN LIGHT */
.video-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  /* Мягкое тонирование: сверху легкая дымка, к низу плавно переходит в чистый белый цвет сайта */
  background: linear-gradient(
    to bottom,
    rgba(249, 250, 251, 0.4) 0%,
    rgba(249, 250, 251, 0.7) 50%,
    rgba(249, 250, 251, 0.98) 100%
  );
}

/* СЛОЙ С ТЕКСТОМ */
.content-layer {
  position: relative;
  z-index: 2; /* Поверх видео */
  max-width: 850px;
  display: flex;
  flex-column: initial;
  flex-direction: column;
  align-items: center;
}

/* ТИПОГРАФИКА */
.welcome-title {
  font-size: 3.8rem;
  font-weight: 800;
  letter-spacing: -1.5px;
  color: #111827;
  line-height: 1.15;
}

.text-premium {
  font-weight: 300;
  color: #4b5563;
  font-style: italic;
}

.welcome-text {
  font-size: 1.15rem;
  font-weight: 400;
  color: #374151; /* Чуть темнее для идеального контраста на фоне видео */
  max-width: 600px;
  line-height: 1.6;
}

/* КНОПКА */
.btn-explore {
  background: #111827;
  color: #ffffff;
  font-size: 1rem;
  font-weight: 600;
  border-radius: 8px;
  border: 1px solid #111827;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  display: inline-flex;
  align-items: center;
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.btn-explore:hover {
  background: #ffffff;
  color: #111827;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
}

.arrow-icon {
  transition: transform 0.3s ease;
}

.btn-explore:hover .arrow-icon {
  transform: translateX(4px);
}

/* 
  КАСТОМНАЯ КИНЕМАТОГРАФИЧНАЯ АНИМАЦИЯ ПОЯВЛЕНИЯ (Cubic-Bezier)
  Текст не просто проявляется, а элегантно "всплывает" с замедлением в конце
*/
.animate-fade-slide-up {
  opacity: 0;
  transform: translateY(40px);
  animation: fadeSlideUp 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Ступенчатые задержки (Stagger effect) */
.delay-1 {
  animation-delay: 0.2s;
}

.delay-2 {
  animation-delay: 0.4s;
}

@keyframes fadeSlideUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* АДАПТИВНОСТЬ */
@media (max-width: 768px) {
  .welcome-title {
    font-size: 2.5rem;
    letter-spacing: -0.5px;
  }
  .welcome-text {
    font-size: 1rem;
  }
}
</style>