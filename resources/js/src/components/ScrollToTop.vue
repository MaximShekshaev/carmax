<template>
  <button 
    v-show="visible" 
    class="scroll-to-top"
    @click="scrollTop"
  >
    ↑
  </button>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const visible = ref(false)

const checkScroll = () => {
  visible.value = window.scrollY > 200 
}

const scrollTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

onMounted(() => {
  window.addEventListener('scroll', checkScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', checkScroll)
})
</script>

<style scoped>
.scroll-to-top {
  position: fixed;
  right: 20px;
  bottom: 30px;
  width: 50px;      
  height: 50px;      
  background-color: #0d6efd;
  color: white;
  border: none;
  border-radius: 50%; 
  font-size: 25px;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  transition: background 0.3s, transform 0.2s;
  z-index: 9999;
  display: flex;     
  align-items: center;
  justify-content: center;
}


.scroll-to-top:hover {
  background-color: #1f5bcc;
  transform: translateY(-2px);
}
</style>
