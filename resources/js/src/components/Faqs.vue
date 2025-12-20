<template>
  <section class="container py-5">
    <h2 class="mb-5 text-center fw-bold text-primary">Ответы на частые вопросы</h2>

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


.faq-question {
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: color 0.3s;
}

.faq-question:hover {
  color: #0d6efd;
}


.faq-icon {
  transition: transform 0.3s ease;
  font-size: 1.2rem;
  color: #0d6efd;
}

.faq-icon.open {
  transform: rotate(180deg);
}


.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, max-height 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  max-height: 0;
}

.faq-item {
  background: #fff;
  border-radius: 1rem;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
  transition: transform 0.3s, box-shadow 0.3s;
}

.faq-item:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}


.contact-panel {
  display: flex;
  flex-direction: column;
  justify-content: start;
  background: #f8f9fa;
}

.contact-panel p i {
  margin-right: 8px;
}

.contact-panel button {
  border-radius: 0.75rem;
  font-weight: 600;
  transition: transform 0.2s, background 0.2s;
}


</style>
