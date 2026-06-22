<template>
  <div class="stats-wrapper">

    <h3 class="title">Статистика аренды</h3>

    <div class="grid">

      <div class="card">
        <span class="label">Всего аренд</span>
        <h2>{{ stats.totalRentals }}</h2>
        <p>+{{ stats.monthGrowth }}% за месяц</p>
      </div>

      <div class="card">
        <span class="label">Активные брони</span>
        <h2>{{ stats.activeBookings }}</h2>
        <p>в реальном времени</p>
      </div>

      <div class="card">
        <span class="label">Доступные авто</span>
        <h2>{{ stats.availableCars }}</h2>
        <p>готовы к аренде</p>
      </div>

      <div class="card highlight">
        <span class="label">Средний чек</span>
        <h2>{{ stats.avgPrice }}₸</h2>
        <p>за одну аренду</p>
      </div>

    </div>

    <div class="bottom">
      <div class="progress-box">
        <div class="info">
          <span>Загрузка автопарка</span>
          <b>{{ stats.fleetUsage }}%</b>
        </div>
        <div class="bar">
          <div class="fill" :style="{ width: stats.fleetUsage + '%' }"></div>
        </div>
      </div>

      <div class="note">
        ⚡ Система обновляется в реальном времени
      </div>
    </div>

  </div>
</template>

<script setup>
import { reactive } from 'vue'

const stats = reactive({
  totalRentals: 1248,
  activeBookings: 37,
  availableCars: 86,
  avgPrice: 18500,
  monthGrowth: 18,
  fleetUsage: 72
})
</script>

<style scoped>
.stats-wrapper {
  padding: 20px;
  background: #f8fafc;
  border-radius: 18px;
}

/* title */
.title {
  font-size: 18px;
  margin-bottom: 15px;
  color: #111;
  font-weight: 600;
}

/* grid */
.grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
}

/* card */
.card {
  background: white;
  border-radius: 14px;
  padding: 16px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.05);
  border: 1px solid #eee;
  transition: 0.2s;
}

.card:hover {
  transform: translateY(-3px);
}

.label {
  font-size: 12px;
  color: #777;
}

h2 {
  margin: 8px 0;
  font-size: 24px;
  color: #111;
}

.card p {
  font-size: 12px;
  color: #888;
}

/* highlight card */
.highlight {
  background: linear-gradient(135deg, #111, #2a2a2a);
  color: white;
}

.highlight h2,
.highlight p,
.highlight .label {
  color: white;
}

/* bottom */
.bottom {
  margin-top: 18px;
}

.progress-box {
  background: white;
  padding: 14px;
  border-radius: 14px;
  border: 1px solid #eee;
}

.info {
  display: flex;
  justify-content: space-between;
  font-size: 13px;
  margin-bottom: 8px;
}

.bar {
  height: 8px;
  background: #eee;
  border-radius: 20px;
  overflow: hidden;
}

.fill {
  height: 100%;
  background: linear-gradient(90deg, #111, #666);
  width: 0%;
  transition: 0.6s ease;
}

/* note */
.note {
  margin-top: 10px;
  font-size: 12px;
  color: #777;
  text-align: center;
}
</style>