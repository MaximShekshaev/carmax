<template>
  <CarList :cars="cars" />
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "@/services/api";
import CarList from "@/components/CarList.vue";

const cars = ref([]);

onMounted(async () => {
  try {
    const res = await api.get("/cars");
    cars.value = res.data;
  } catch (e) {
    console.error("Ошибка загрузки авто", e);
  }
});
</script>
