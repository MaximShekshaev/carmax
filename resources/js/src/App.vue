<template>
  <div class="d-flex flex-column min-vh-100">

    <Header v-if="!isAdminRoute" />

    <!-- 📢 LEFT AD -->
    <div
      v-if="!isAdminRoute && leftVisible"
      class="ad-banner left"
      :class="{ 'ad-exit': leftHidden }"
    >
      <button class="close" @click="hideAd('left')">✕</button>

      <div class="ad-content">
        <h4>{{ leftAd.title }}</h4>
        <p>{{ leftAd.text }}</p>
        <span>{{ leftAd.tag }}</span>
      </div>
    </div>

    <!-- 📢 RIGHT AD -->
    <div
      v-if="!isAdminRoute && rightVisible"
      class="ad-banner right"
      :class="{ 'ad-exit': rightHidden }"
    >
      <button class="close" @click="hideAd('right')">✕</button>

      <div class="ad-content">
        <h4>{{ rightAd.title }}</h4>
        <p>{{ rightAd.text }}</p>
        <span>{{ rightAd.tag }}</span>
      </div>
    </div>

    <main
      :style="{ paddingTop: isAdminRoute ? '0px' : '80px' }"
      class="flex-grow-1 main-content"
    >
      <router-view />
    </main>

    <ScrollToTop v-if="!isAdminRoute" />
    <Footer v-if="!isAdminRoute" />
    <AiAssistant v-if="!isAdminRoute" />

    <DiscountPopup
      v-if="showPopup && !isAdminRoute"
      @close="hidePopup"
      @action="goToRent"
    />

  </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
import ScrollToTop from './components/ScrollToTop.vue'
import AiAssistant from './components/AiAssistant.vue'
import DiscountPopup from './components/DiscountPopup.vue'

const route = useRoute()
const router = useRouter()

const isAdminRoute = computed(() =>
  route.path.startsWith('/admin')
)

const showPopup = ref(false)

const leftVisible = ref(true)
const rightVisible = ref(true)

const leftHidden = ref(false)
const rightHidden = ref(false)

/* 🧠 ADS DATA */
const adsPool = [
  {
    title: "🔥 Premium Cars -40%",
    text: "BMW / Mercedes / Audi доступны сегодня",
    tag: "CARMAX VIP"
  },
  {
    title: "🚗 Luxury Fleet",
    text: "Комфортные авто бизнес-класса",
    tag: "PREMIUM"
  },
  {
    title: "⚡ Fast Rental",
    text: "Оформление за 2 минуты",
    tag: "SMART"
  }
]

const leftAd = ref(adsPool[0])
const rightAd = ref(adsPool[1])

let index = 0

function updateAds() {
  index = (index + 1) % adsPool.length
  leftAd.value = adsPool[index]
  rightAd.value = adsPool[(index + 1) % adsPool.length]
}

/* ❌ CLOSE WITH ANIMATION */
function hideAd(side) {
  if (side === 'left') {
    leftHidden.value = true
    setTimeout(() => (leftVisible.value = false), 400)
  }

  if (side === 'right') {
    rightHidden.value = true
    setTimeout(() => (rightVisible.value = false), 400)
  }
}

function hidePopup() {
  showPopup.value = false
}

function goToRent() {
  hidePopup()
}

onMounted(() => {
  if (!route.path.startsWith('/admin')) {
    setTimeout(() => {
      showPopup.value = true
    }, 2000)

    setInterval(updateAds, 5000)
  }
})
</script>

<style scoped>
.main-content {
  background: #f8fafc;
}

/* 📢 ADS BASE */
.ad-banner {
  position: fixed;
  bottom: 20px;
  width: 210px;
  padding: 22px;
  border-radius: 18px;
  background: linear-gradient(180deg, #0f0f12, #1c1c1f);
  color: white;
  box-shadow: 0 30px 80px rgba(0,0,0,0.4);
  z-index: 9999;
  text-align: center;
  border: 1px solid rgba(255,255,255,0.08);

  /* 🎬 TikTok-style entry */
  opacity: 0;
  transform: translateY(60px) scale(0.85);
  animation: adIn 0.6s ease forwards, float 3s ease-in-out infinite;
}

/* POSITION */
.left { left: 15px; }
.right { right: 15px; }

/* ✨ ENTRY */
@keyframes adIn {
  0% {
    opacity: 0;
    transform: translateY(80px) scale(0.8);
    filter: blur(8px);
  }
  100% {
    opacity: 1;
    transform: translateY(0px) scale(1);
    filter: blur(0px);
  }
}

/* 🌊 FLOAT */
@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
  100% { transform: translateY(0px); }
}

/* ❌ EXIT */
.ad-exit {
  animation: adOut 0.4s ease forwards !important;
}

@keyframes adOut {
  0% {
    opacity: 1;
    transform: translateY(0px) scale(1);
    filter: blur(0px);
  }
  100% {
    opacity: 0;
    transform: translateY(60px) scale(0.85);
    filter: blur(6px);
  }
}

/* CONTENT */
.ad-banner h4 {
  font-size: 15px;
  margin: 0;
}

.ad-banner p {
  font-size: 12px;
  color: #bbb;
  margin: 10px 0;
}

.ad-banner span {
  font-size: 11px;
  color: #ffb703;
  font-weight: bold;
}

/* CLOSE BUTTON */
.close {
  position: absolute;
  top: 8px;
  right: 10px;
  background: transparent;
  border: none;
  color: #fff;
  font-size: 14px;
  cursor: pointer;
  opacity: 0.7;
}

.close:hover {
  opacity: 1;
}
</style>