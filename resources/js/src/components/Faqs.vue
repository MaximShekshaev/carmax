<template>
  <section class="container py-5">
    

    <div class="row">
      <!-- Левый столбец: FAQ -->
      <div class="col-lg-7">
        <div class="faq-list">
          <div
            v-for="(item, index) in faqs"
            :key="item.id"
            class="faq-item p-4 mb-3 rounded-3 shadow-sm"
          >
            <!-- Вопрос -->
            <div
              class="d-flex justify-content-between align-items-center faq-question"
              @click="toggle(index)"
            >
              <span class="fs-5 fw-semibold">{{ item.question }}</span>
              <span class="faq-icon" :class="{ open: activeIndex === index }">
                <i class="bi bi-chevron-down"></i>
              </span>
            </div>

            <!-- Ответ -->
            <transition name="fade">
              <div v-if="activeIndex === index" class="mt-3 text-muted ps-2">
                {{ item.answer }}
              </div>
            </transition>
          </div>
        </div>
      </div>

      <!-- Правый столбец: Контакты -->
      <div class="col-lg-5 mt-4 mt-lg-0">
        <div class="contact-panel p-4 rounded-3 shadow-sm bg-light h-80">
          <h4 class="fw-bold mb-3">Свяжитесь с нами</h4>
          <p class="mb-2"><i class="bi bi-telephone"></i> +7 (701) 123-45-67</p>
          <p class="mb-2"><i class="bi bi-envelope"></i> support@carmax.kz</p>
          <p class="mb-2"><i class="bi bi-geo-alt"></i> г. Астана, Казахстан</p>
          <div class="mt-4">
            <h6 class="fw-semibold mb-2">Следите за нами:</h6>
            <div class="d-flex gap-3">
              <a href="#" class="text-primary fs-4"><i class="bi bi-facebook"></i></a>
              <a href="#" class="text-info fs-4"><i class="bi bi-twitter"></i></a>
              <a href="#" class="text-danger fs-4"><i class="bi bi-instagram"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'

const faqs = ref([])
const activeIndex = ref(null)

const toggle = (index) => {
  activeIndex.value = activeIndex.value === index ? null : index
}

const loadFaqs = async () => {
  try {
    const res = await api.get('/faqs')
    faqs.value = res.data
  } catch (err) {
    console.error('Ошибка загрузки FAQ:', err)
  }
}

onMounted(loadFaqs)
</script>

<style scoped>
.faq-list {
  width: 100%;
}

/* ===== FAQ ITEM ===== */
.faq-item {
  background: linear-gradient(160deg, #1b1f27, #0f1218);
  border-radius: 1.25rem;
  box-shadow:
    0 15px 40px rgba(0, 0, 0, 0.6),
    inset 0 1px 0 rgba(255, 255, 255, 0.04);
  transition:
    transform 0.3s ease,
    box-shadow 0.3s ease;
}

.faq-item:hover {
  transform: translateY(-5px);
  box-shadow:
    0 30px 60px rgba(37, 99, 235, 0.35);
}

/* ===== ВОПРОС ===== */
.faq-question {
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: #e5e7eb;
  transition: color 0.25s ease;
}

.faq-question:hover {
  color: #3b82f6;
}

/* ===== ИКОНКА ===== */
.faq-icon {
  font-size: 1.25rem;
  color: #3b82f6;
  transition: transform 0.3s ease;
}

.faq-icon.open {
  transform: rotate(180deg);
}

/* ===== АНИМАЦИЯ ===== */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease, max-height 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  max-height: 0;
}

/* ===== ОТВЕТ ===== */
.faq-item .text-muted {
  color: #9ca3af !important;
  line-height: 1.6;
}

/* ===== КОНТАКТЫ ===== */
.contact-panel {
  background: linear-gradient(160deg, #1b1f27, #0f1218);
  border-radius: 1.25rem;
  box-shadow:
    0 18px 45px rgba(0, 0, 0, 0.6),
    inset 0 1px 0 rgba(255, 255, 255, 0.04);
  display: flex;
  flex-direction: column;
}

.contact-panel h4 {
  color: #ffffff;
}

.contact-panel p {
  color: #d1d5db;
  font-size: 0.95rem;
}

.contact-panel p i {
  margin-right: 8px;
  color: #3b82f6;
}

/* ===== СОЦСЕТИ ===== */
.contact-panel a {
  transition: transform 0.25s ease, color 0.25s ease;
}

.contact-panel a:hover {
  transform: translateY(-3px);
  color: #60a5fa !important;
}
</style>
