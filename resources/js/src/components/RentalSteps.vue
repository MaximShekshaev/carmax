<template>
  <section class="steps-wrapper">

    <!-- LEFT -->
    <div class="left">
      <h2 class="title">
        4 шага — и машина ваша
      </h2>

      <p class="subtitle">
        Быстрый процесс аренды без лишних действий
      </p>

      
      <div class="slider" @mouseenter="pause = true" @mouseleave="pause = false">
        <div
          class="track"
          :style="{ transform: `translateX(-${current * 100}%)` }"
        >
          <div class="slide" v-for="(img, i) in images" :key="i">
            <img :src="img" />
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT -->
    <div class="right">

      <div class="line"></div>

      <div class="step" v-for="(step, i) in steps" :key="i">

        <div class="circle">
          {{ step.num }}
        </div>

        <div class="card">
          <h3>Шаг {{ step.num }}</h3>
          <p v-html="step.text"></p>
        </div>

      </div>

    </div>

  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const steps = [
  {
    num: "1",
    text: "Выберите авто в <span class='green'>каталоге</span>"
  },
  {
    num: "2",
    text: "Заполните форму и внесите предоплату"
  },
  {
    num: "3",
    text: "Загрузите документы и дождитесь подтверждения"
  },
  {
    num: "4",
    text: "Получите автомобиль и начинайте поездку"
  }
]

const images = [
  "https://images.unsplash.com/photo-1503376780353-7e6692767b70",
  "https://images.unsplash.com/photo-1492144534655-ae79c964c9d7",
]

const current = ref(0)
const pause = ref(false)


onMounted(() => {
  setInterval(() => {
    if (!pause.value) {
      current.value = (current.value + 1) % images.length
    }
  }, 3500)
})
</script>

<style scoped>

/* MAIN */
.steps-wrapper {
  display: grid;
  grid-template-columns: 1fr 1.3fr;
  gap: 50px;
  padding: 50px;
  background: #ffffff;
  border-radius: 22px;
  box-shadow: 0 25px 80px rgba(0,0,0,0.08);
}

/* LEFT */
.title {
  font-size: 38px;
  font-weight: 800;
  color: #111827;
  line-height: 1.1;
}

.subtitle {
  margin-top: 12px;
  color: #6b7280;
}

/* 🖼️ BIG SLIDER */
.slider {
  margin-top: 25px;
  width: 100%;
  height: 320px;
  overflow: hidden;
  border-radius: 18px;
  box-shadow: 0 20px 50px rgba(0,0,0,0.15);
}

.track {
  display: flex;
  height: 100%;
  transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.slide {
  min-width: 100%;
  height: 100%;
  flex-shrink: 0;
}

.slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: contrast(1.05) saturate(1.05);
  transition: transform 0.5s ease;
}

.slide img:hover {
  transform: scale(1.05);
}

/* RIGHT SIDE */
.right {
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 22px;
}

.line {
  position: absolute;
  left: 20px;
  top: 0;
  bottom: 0;
  width: 2px;
  background: linear-gradient(to bottom, #000, transparent);
}

/* STEP */
.step {
  display: flex;
  gap: 16px;
}

.circle {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: #000;
  color: white;
  font-weight: 800;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2;
}

.card {
  flex: 1;
  background: rgba(255,255,255,0.8);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(0,0,0,0.06);
  border-radius: 14px;
  padding: 16px 18px;
  transition: 0.25s;
}

.card:hover {
  transform: translateX(6px);
  box-shadow: 0 15px 35px rgba(0,0,0,0.08);
}

.card h3 {
  margin: 0;
  font-size: 14px;
  color: #9ca3af;
}

.card p {
  margin-top: 6px;
  font-size: 15px;
  color: #111827;
}

/* highlight */
:deep(.green) {
  color: #000;
  font-weight: 600;
}

/* RESPONSIVE */
@media (max-width: 900px) {
  .steps-wrapper {
    grid-template-columns: 1fr;
  }

  .slider {
    height: 220px;
  }
}
</style>