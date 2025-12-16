
<template>
  <main class="container py-5">
    <h1 class="mb-4 text-center">Моя аренда</h1>

    <!-- Если аренд нет -->
    <div v-if="rents.length === 0" class="alert alert-info text-center">
      У вас пока нет активных аренд
    </div>

    <!-- Список аренд -->
    <div class="row g-4" v-else>
      <div class="col-md-6" v-for="rent in rents" :key="rent.id">
        <div class="card shadow-sm h-100">
          <img
            :src="rent.image"
            class="card-img-top"
            alt="car"
            style="height: 200px; object-fit: cover;"
          />

          <div class="card-body">
            <h5 class="card-title">{{ rent.car }}</h5>

            <p class="mb-1">
              <strong>Срок:</strong>
              {{ rent.start }} — {{ rent.end }}
            </p>

            <p class="mb-1">
              <strong>Цена:</strong>
              {{ rent.price }} ₸
            </p>

            <span
              class="badge"
              :class="rent.status === 'Активна' ? 'bg-success' : 'bg-secondary'"
            >
              {{ rent.status }}
            </span>
          </div>

          <div class="card-footer bg-white text-end">
            <button
              v-if="rent.status === 'Активна'"
              class="btn btn-outline-danger btn-sm"
              @click="cancelRent(rent.id)"
            >
              Отменить аренду
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref } from "vue"

const rents = ref([
  {
    id: 1,
    car: "Toyota Camry 70",
    start: "10.03.2025",
    end: "15.03.2025",
    price: "75 000",
    status: "Активна",
    image: "https://via.placeholder.com/600x400?text=Toyota+Camry"
  },
  {
    id: 2,
    car: "Hyundai Elantra",
    start: "01.02.2025",
    end: "05.02.2025",
    price: "48 000",
    status: "Завершена",
    image: "https://via.placeholder.com/600x400?text=Hyundai+Elantra"
  }
])

const cancelRent = (id) => {
  rents.value = rents.value.map(r =>
    r.id === id ? { ...r, status: "Завершена" } : r
  )
}
</script>
