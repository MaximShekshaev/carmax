<template>
  <section class="conditions container py-5">
    <h2 class="text-center mb-5 fw-semibold">Условия</h2>

    <div class="row g-4 text-center">
      <div
        class="col-md-3"
        v-for="(item, index) in conditions"
        :key="index"
      >
        <div class="condition-card h-100 p-4">
          <h5 class="fw-semibold mb-3">
            {{ item.title }}
          </h5>
          <p class="text-muted mb-0">
            {{ item.text }}
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const conditions = [
  {
    title: "Круглосуточная поддержка",
    text: "Мы работаем 24 часа, 7 дней в неделю без выходных"
  },
  {
    title: "Личный автопарк",
    text: "У нас вы найдете автомобили как эконом класса, так и премиум класса"
  },
  {
    title: "Быстрое оформление",
    text: "Оформление документов займет 10 минут. Минимальный пакет документов"
  },
  {
    title: "Доставка авто",
    text: "Доставим автомобиль в любую часть города"
  }
]
</script>

<style scoped>
.condition-card {
  border-radius: 16px;
  background: #ffffff;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.condition-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}
</style>
