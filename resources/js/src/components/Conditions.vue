<template>
  <section class="conditions container py-5">
    <h2 class="text-center mb-5 fw-semibold">Условия аренды</h2>

    <div class="row g-4 text-center">
      <div
        class="col-md-3"
        v-for="item in conditions"
        :key="item.id"
      >
        <div class="condition-card h-100">
          <div class="icon-wrap mb-3">
            <i :class="`bi ${item.icon}`"></i>
          </div>

          <h5 class="fw-semibold mb-2">
            {{ item.title }}
          </h5>

          <p class="text-muted mb-0 small">
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
.conditions {
  background: linear-gradient(180deg, #f8f9fa, #ffffff);
}

/* Карточка */
.condition-card {
  position: relative;
  height: 100%;
  padding: 32px 26px;
  border-radius: 20px;

  background: linear-gradient(145deg, #ffffff, #f3f4f6);

  box-shadow:
    0 10px 25px rgba(0, 0, 0, 0.06),
    inset 0 1px 0 rgba(255, 255, 255, 0.9);

  transition:
    transform 0.35s ease,
    box-shadow 0.35s ease;
}


.condition-card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 24px;
  right: 24px;
  height: 4px;
  border-radius: 0 0 10px 10px;
  background: linear-gradient(90deg, #0d6efd, #4dabf7);
}


.condition-card:hover {
  transform: translateY(-8px);
  box-shadow:
    0 20px 45px rgba(13, 110, 253, 0.2),
    inset 0 1px 0 rgba(255, 255, 255, 1);
}


.icon-wrap {
  width: 64px;
  height: 64px;
  margin: 0 auto 16px;
  border-radius: 50%;

  display: flex;
  align-items: center;
  justify-content: center;

  background: linear-gradient(135deg, #0d6efd, #4dabf7);
  color: #fff;
  font-size: 28px;

  box-shadow: 0 10px 25px rgba(13, 110, 253, 0.35);
}


.condition-card h5 {
  font-size: 1.05rem;
  color: #212529;
}

.condition-card p {
  line-height: 1.6;
}
</style>
