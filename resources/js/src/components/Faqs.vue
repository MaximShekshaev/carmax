<template>
  <section class="container py-5">
    <div class="row g-4">
      
      <!-- Левый столбец: FAQ -->
      <div class="col-lg-7">
        <div class="faq-list">
          <div
            v-for="(item, index) in faqs"
            :key="item.id"
            class="faq-item p-4 mb-3"
            :class="{ active: activeIndex === index }"
          >
            <!-- Вопрос -->
            <div
              class="d-flex justify-content-between align-items-center faq-question"
              @click="toggle(index)"
            >
              <span class="faq-title">{{ item.question }}</span>
              <span class="faq-icon" :class="{ open: activeIndex === index }">
                <i class="bi bi-chevron-down"></i>
              </span>
            </div>

            <!-- Ответ с плавной JS-анимацией раскрытия -->
            <transition
              name="accordion"
              @before-enter="beforeEnter"
              @enter="enter"
              @before-leave="beforeLeave"
              @leave="leave"
            >
              <div v-if="activeIndex === index" class="faq-answer-wrapper">
                <div class="faq-answer mt-3 ps-1">
                  {{ item.answer }}
                </div>
              </div>
            </transition>
          </div>
        </div>
      </div>

      <!-- Правый столбец: Контакты -->
      <div class="col-lg-5">
        <div class="contact-panel p-4 h-100">
          <h4 class="contact-title mb-4">Свяжитесь с нами</h4>
          
          <div class="contact-info-list d-flex flex-column gap-3 mb-4">
            <p class="contact-item"><i class="bi bi-telephone"></i> +7 (701) 123-45-67</p>
            <p class="contact-item"><i class="bi bi-envelope"></i> support@carmax.kz</p>
            <p class="contact-item"><i class="bi bi-geo-alt"></i> г. Астана, Казахстан</p>
          </div>
          
          <hr class="my-4 border-light-subtle">
          
          <div class="socials-block">
            <h6 class="socials-title mb-3">Следите за нами</h6>
            <div class="d-flex gap-2">
              <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
              <a href="#" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
              <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
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

// Функции для плавной анимации высоты (Accordion)
const beforeEnter = (el) => { el.style.height = '0' }
const enter = (el) => { el.style.height = el.scrollHeight + 'px' }
const beforeLeave = (el) => { el.style.height = el.scrollHeight + 'px' }
const leave = (el) => { el.style.height = '0' }

onMounted(loadFaqs)
</script>

<style scoped>
/* --- FAQ СТИЛИ --- */
.faq-list {
  width: 100%;
}

.faq-item {
  background: #ffffff;
  border: 1px solid rgba(0, 0, 0, 0.05);
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.faq-item:hover, .faq-item.active {
  border-color: rgba(17, 24, 39, 0.15);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
}

.faq-question {
  cursor: pointer;
  user-select: none;
}

.faq-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: #111827;
  transition: color 0.25s ease;
}

.faq-item:hover .faq-title {
  color: #000000;
}

.faq-icon {
  font-size: 1rem;
  color: #9ca3af;
  transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), color 0.3s;
}

.faq-item.active .faq-icon {
  transform: rotate(180deg);
  color: #111827;
}

/* Стили ответа */
.faq-answer-wrapper {
  overflow: hidden;
  transition: height 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

.faq-answer {
  color: #4b5563;
  line-height: 1.6;
  font-size: 0.95rem;
}

/* --- КАРТОЧКА КОНТАКТОВ --- */
.contact-panel {
  background-color: #f9fafb; /* Мягкий оттенок для контраста с FAQ */
  border: 1px solid rgba(0, 0, 0, 0.04);
  border-radius: 12px;
  display: flex;
  flex-direction: column;
}

.contact-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #111827;
  letter-spacing: -0.3px;
}

.contact-item {
  display: flex;
  align-items: center;
  gap: 12px;
  color: #4b5563;
  margin: 0;
  font-size: 0.95rem;
}

.contact-item i {
  font-size: 1.1rem;
  color: #9ca3af;
}

/* Соцсети в панели */
.socials-title {
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-weight: 700;
  color: #9ca3af;
}

.socials-block a {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  color: #4b5563;
  border: 1px solid #e5e7eb;
  background: #ffffff;
  border-radius: 50%;
  font-size: 1rem;
  text-decoration: none;
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.socials-block a:hover {
  color: #111827;
  border-color: #111827;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}
</style>