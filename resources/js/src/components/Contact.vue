<template>
  <section class="container py-5">
    <h2 class="mb-4 fw-semibold">Ответы на частые вопросы</h2>

    <div class="faq-list">
      <div
        v-for="(item, index) in faqs"
        :key="index"
        class="faq-item border-bottom py-3"
      >
        <!-- Вопрос -->
        <div
          class="d-flex justify-content-between align-items-center faq-question"
          @click="toggle(index)"
        >
          <span class="fs-5">{{ item.q }}</span>

          <!-- Иконка -->
          <span
            class="faq-icon"
            :class="{ open: activeIndex === index }"
          >
            ▼
          </span>
        </div>

        <!-- Ответ -->
        <transition name="fade">
          <div v-if="activeIndex === index" class="mt-3 text-muted">
            {{ item.a }}
          </div>
        </transition>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from "vue"

const activeIndex = ref(0)

const faqs = [
  {
    q: "Как арендовать автомобиль?",
    a: "Выберите автомобиль, укажите даты аренды и оформите заявку онлайн."
  },
  {
    q: "Сколько стоит машина напрокат?",
    a: "Стоимость зависит от класса автомобиля и срока аренды."
  },
  {
    q: "Можно ли выехать на автомобиле межгород или за границу?",
    a: "Да, по предварительному согласованию с менеджером."
  },
  {
    q: "Как можно оплатить аренду?",
    a: "Оплата возможна наличными, банковской картой или переводом."
  },
  {
    q: "Можно ли взять автомобиль в прокат без залога?",
    a: "Да, для некоторых автомобилей доступна аренда без залога."
  },
  {
    q: "Возможно ли продлить автомобиль, не приезжая в офис?",
    a: "Да, достаточно связаться с менеджером."
  },
  {
    q: "Какие документы нужны для аренды без водителя?",
    a: "Паспорт и водительское удостоверение."
  },
  {
    q: "Есть ли у вас доставка автомобиля?",
    a: "Да, возможна доставка автомобиля по городу."
  }
]

const toggle = (index) => {
  activeIndex.value = activeIndex.value === index ? null : index
}
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
