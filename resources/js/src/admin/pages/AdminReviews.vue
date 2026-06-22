<template>
  <div class="reviews-management-section">
    <!-- ХЕДЕР СЕКЦИИ -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="section-title m-0">Отзывы клиентов</h2>
      <span class="badge-count">Всего отзывов: {{ reviews.length }}</span>
    </div>

    <!-- ТАБЛИЦА С ОТЗЫВАМИ -->
    <div class="table-responsive table-card">
      <table class="table custom-admin-table m-0">
        <thead>
          <tr>
            <th width="70">ID</th>
            <th width="180">Автор</th>
            <th width="320">Сообщение</th>
            <th width="140">Рейтинг</th>
            <th width="130">Статус</th>
            <th width="120">Дата</th>
            <th width="160" class="text-end">Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="r in reviews" :key="r.id" :class="{'table-row-editing': editId === r.id}">
            <!-- ID отзыва -->
            <td class="align-middle text-muted fw-semibold">#{{ r.id }}</td>

            <!-- Автор -->
            <td class="align-middle">
              <div v-if="editId !== r.id" class="fw-bold text-dark">{{ r.name }}</div>
              <input v-else v-model="form.name" class="form-control admin-table-input" />
            </td>

            <!-- Текст отзыва -->
            <td class="align-middle text-muted cell-text-truncate">
              <span v-if="editId !== r.id" :title="r.message">{{ r.message }}</span>
              <textarea v-else v-model="form.message" class="form-control admin-table-input" rows="1"></textarea>
            </td>

            <!-- Звездный рейтинг -->
            <td class="align-middle">
              <div v-if="editId !== r.id" class="stars-wrapper" :title="`Оценка: ${r.rating} из 5`">
                <i v-for="n in 5" :key="n" 
                   class="bi" 
                   :class="n <= r.rating ? 'bi-star-fill text-warning-custom' : 'bi-star text-muted-stars'">
                </i>
              </div>
              <select v-else v-model.number="form.rating" class="form-select admin-table-input-select">
                <option v-for="n in 5" :key="n" :value="n">{{ n }} ★</option>
              </select>
            </td>

            <!-- Статус публикации -->
            <td class="align-middle">
              <span v-if="editId !== r.id" class="badge-status" :class="r.is_active ? 'status-active' : 'status-disabled'">
                {{ r.is_active ? 'Активен' : 'Скрыт' }}
              </span>
              <select v-else v-model="form.is_active" class="form-select admin-table-input-select">
                <option :value="true">Активен</option>
                <option :value="false">Скрыт</option>
              </select>
            </td>

            <!-- Дата создания -->
            <td class="align-middle text-secondary fs-7">
              {{ new Date(r.created_at).toLocaleDateString('ru-RU', { day: '2-digit', month: '2-digit', year: 'numeric' }) }}
            </td>

            <!-- Действия -->
            <td class="align-middle text-end">
              <div class="d-flex justify-content-end gap-1" v-if="editId !== r.id">
                <button @click="startEdit(r)" class="btn btn-action-icon btn-edit-icon">
                  Изменить
                </button>
                <button @click="remove(r.id)" class="btn btn-action-icon btn-delete-icon">
                  Удалить
                </button>
              </div>
              <div class="d-flex justify-content-end gap-1" v-else>
                <button @click="save(r.id)" class="btn btn-action-icon btn-save-icon">
                  Ок
                </button>
                <button @click="editId = null" class="btn btn-action-icon btn-cancel-icon">
                  Х
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getReviews, toggleReview, deleteReview } from '../../auth'

const reviews = ref([])
const editId = ref(null)
const form = ref({})

const load = async () => {
  try {
    const res = await getReviews()
    reviews.value = res.data
  } catch (e) {
    console.error('Ошибка при получении отзывов:', e)
  }
}

const startEdit = (r) => {
  editId.value = r.id
  form.value = { ...r }
}

const save = async (id) => {
  try {
    await toggleReview(id, form.value) 
    editId.value = null
    load()
  } catch (e) {
    console.error('Ошибка при сохранении изменений отзыва:', e)
  }
}

const remove = async (id) => {
  if (confirm('Вы уверены, что хотите удалить этот отзыв без возможности восстановления?')) {
    try {
      await deleteReview(id)
      load()
    } catch (e) {
      console.error('Ошибка при удалении отзыва:', e)
    }
  }
}

onMounted(load)
</script>

<style scoped>
/* ЗАГОЛОВОК И СЧЕТЧИК КАРТОЧЕК */
.section-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #111827;
  letter-spacing: -0.5px;
}

.badge-count {
  font-size: 0.82rem;
  font-weight: 600;
  background-color: #f3f4f6;
  color: #4b5563;
  padding: 6px 14px;
  border-radius: 50rem;
}

/* ОФОРМЛЕНИЕ ТАБЛИЧНОГО БЛОКА */
.table-card {
  background-color: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
}

.custom-admin-table thead th {
  background-color: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
  color: #4b5563;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: 14px 20px;
}

.custom-admin-table tbody td {
  padding: 14px 20px;
  border-bottom: 1px solid #f3f4f6;
  font-size: 0.92rem;
}

.custom-admin-table tbody tr:hover {
  background-color: #fafafa;
}

/* БЕЗОПАСНОЕ УСЕЧЕНИЕ ДЛИННЫХ ОТЗЫВОВ */
.cell-text-truncate {
  max-width: 320px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* СТИЛИЗАЦИЯ ИКОНОК РЕЙТИНГА */
.stars-wrapper {
  display: flex;
  gap: 2px;
  font-size: 0.95rem;
}
.text-warning-custom {
  color: #fbbf24; /* Фирменное премиальное золото для звезд */
}
.text-muted-stars {
  color: #e5e7eb;
}

/* ТАБЛИЧНЫЕ ИНПУТЫ ДЛЯ РЕДАКТИРОВАНИЯ */
.table-row-editing {
  background-color: #f8fafc !important;
}

.admin-table-input {
  padding: 6px 10px;
  font-size: 0.85rem;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
}

.admin-table-input-select {
  padding: 6px 24px 6px 10px;
  font-size: 0.85rem;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
}

/* ЦВЕТНЫЕ ИНДИКАТОРЫ СТАТУСОВ */
.badge-status {
  display: inline-flex;
  align-items: center;
  padding: 4px 10px;
  font-size: 0.75rem;
  font-weight: 600;
  border-radius: 50rem;
}
.status-active {
  background-color: #dcfce7;
  color: #15803d;
}
.status-disabled {
  background-color: #f3f4f6;
  color: #4b5563;
}

/* КНОПКИ ДЕЙСТВИЙ */
.btn-action-icon {
  background: none;
  border: none;
  padding: 6px 10px;
  font-size: 0.82rem;
  font-weight: 500;
  border-radius: 4px;
  transition: all 0.15s ease;
}

.btn-edit-icon {
  color: #4b5563;
  background-color: #f3f4f6;
}

.btn-edit-icon:hover {
  color: #111827;
  background-color: #e5e7eb;
}

.btn-delete-icon {
  color: #ef4444;
}

.btn-delete-icon:hover {
  background-color: #fef2f2;
}

.btn-save-icon {
  color: #22c55e;
  background-color: #dcfce7;
}

.btn-cancel-icon {
  color: #6b7280;
  background-color: #f3f4f6;
}

.fs-7 {
  font-size: 0.82rem;
}
</style>