<template>
  <div class="d-flex flex-column min-vh-100">
    <Header />

   
    <main class="flex-grow-1 main-content">
      <router-view /> 
    </main>

    <ScrollToTop />
    <Footer />
  </div>
</template>

<script setup>
import ScrollToTop from './components/ScrollToTop.vue';
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
</script>

<style scoped>
.main-content {
  padding-top: 80px; 
}
</style>
