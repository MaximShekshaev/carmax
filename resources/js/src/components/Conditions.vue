<template>
  <section class="conditions container py-5">
    <div class="row g-4 text-center">
      <div
        class="col-md-3"
        v-for="item in conditions"
        :key="item.id"
      >
        <div class="condition-card h-100 p-4">
          <!-- ОБЕРТКА ИКОНКИ -->
          <div class="icon-wrap mb-4">
            <i :class="`bi ${item.icon}`"></i>
          </div>

          <!-- ЗАГОЛОВОК -->
          <h5 class="condition-title mb-2">
            {{ item.title }}
          </h5>

          <!-- ОПИСАНИЕ -->
          <p class="condition-text mb-0 small">
            {{ item.description }}
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'

const conditions = ref([])

onMounted(async () => {
  try {
    const res = await api.get('/rental-conditions')
    conditions.value = res.data
  } catch (e) {
    console.error('Ошибка загрузки условий аренды', e)
  }
})
</script>

<style scoped>
/* --- КАРТОЧКА УСЛОВИЙ --- */
.condition-card {
  position: relative;
  background: #ffffff;
  border: 1px solid rgba(0, 0, 0, 0.05);
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

/* Ховер эффект карточки */
.condition-card:hover {
  transform: translateY(-6px);
  border-color: rgba(17, 24, 39, 0.12);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.04);
}

/* --- ОБЕРТКА ИКОНКИ --- */
.icon-wrap {
  width: 56px;
  height: 56px;
  margin: 0 auto;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f9fafb;
  border: 1px solid #e5e7eb;
  color: #4b5563;
  font-size: 22px;
  transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

/* Анимация иконки при ховере на всю карточку */
.condition-card:hover .icon-wrap {
  background-color: #111827;
  border-color: #111827;
  color: #ffffff;
  transform: scale(1.05);
}

/* --- ТЕКСТ И ТИПОГРАФИКА --- */
.condition-title {
  font-size: 1.05rem;
  font-weight: 600;
  color: #111827;
  letter-spacing: -0.2px;
}

.condition-text {
  color: #4b5563;
  line-height: 1.6;
  font-size: 0.9rem;
}
</style>