<template>
  <section class="conditions container py-5">
    

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

          <p class=" mb-0 small">
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



.condition-card {
  position: relative;
  height: 100%;
  padding: 32px 26px;
  border-radius: 22px;
  background: linear-gradient(160deg, #1b1f27, #0f1218);
  box-shadow:
    0 15px 40px rgba(0, 0, 0, 0.6),
    inset 0 1px 0 rgba(255, 255, 255, 0.04);
  transition:
    transform 0.35s ease,
    box-shadow 0.35s ease;
  color: #e5e7eb;
}


.condition-card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 24px;
  right: 24px;
  height: 4px;
  border-radius: 0 0 10px 10px;
  background: linear-gradient(90deg, #2563eb, #1e40af);
}


.condition-card:hover {
  transform: translateY(-10px);
  box-shadow:
    0 30px 60px rgba(37, 99, 235, 0.35),
    inset 0 1px 0 rgba(255, 255, 255, 0.06);
}


.icon-wrap {
  width: 64px;
  height: 64px;
  margin: 0 auto 18px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;

  background: linear-gradient(135deg, #2563eb, #1e40af);
  color: #ffffff;
  font-size: 28px;

  box-shadow: 0 10px 30px rgba(37, 99, 235, 0.45);
}


.condition-card h5 {
  font-size: 1.1rem;
  font-weight: 700;
  color: #ffffff;
  letter-spacing: 0.4px;
}

/* ТЕКСТ */
.condition-card p {
  color: #9ca3af;
  line-height: 1.6;
}

</style>
