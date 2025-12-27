<template>
  <div class="d-flex flex-column min-vh-100">
    
    <Header v-if="!isAdminRoute" />

    <main :style="{ paddingTop: isAdminRoute ? '0px' : '80px' }" class="flex-grow-1 main-content">
      <router-view /> 
    </main>

   
    <ScrollToTop v-if="!isAdminRoute" />
    <Footer v-if="!isAdminRoute" />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import ScrollToTop from './components/ScrollToTop.vue';
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'

const route = useRoute()


const isAdminRoute = computed(() => route.path.startsWith('/admin'))
</script>

<style scoped>

</style>
