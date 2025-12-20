<template>
  <section class="container py-5">
    <h2 class="mb-4 fw-semibold">Ответы на частые вопросы</h2>

    <div class="faq-list">
      <div
        v-for="(item, index) in faqs"
        :key="item.id"
        class="faq-item border-bottom py-3"
      >
        <!-- Вопрос -->
        <div
          class="d-flex justify-content-between align-items-center faq-question"
          @click="toggle(index)"
        >
          <span class="fs-5">{{ item.question }}</span>

          <span
            class="faq-icon"
            :class="{ open: activeIndex === index }"
          >
            ▼
          </span>
        </div>

        <!-- Ответ -->
        <transition name="fade">
          <div
            v-if="activeIndex === index"
            class="mt-3 text-muted"
          >
            {{ item.answer }}
          </div>
        </transition>
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
  const res = await api.get('/faqs')
  faqs.value = res.data
}

onMounted(loadFaqs)
</script>

<style scoped>
.faq-question {
  cursor: pointer;
}

.faq-icon {
  transition: transform 0.3s ease;
  font-size: 14px;
}

.faq-icon.open {
  transform: rotate(180deg);
}

/* Анимация */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
