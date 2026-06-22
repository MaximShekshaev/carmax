<template>
  <div class="faq-management-section">
    <!-- ХЕДЕР СЕКЦИИ -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="section-title m-0">Часто задаваемые вопросы</h2>
      <button 
        v-if="!showAddForm" 
        @click="showAddForm = true" 
        class="btn btn-add-primary"
      >
        <i class="bi bi-plus-lg me-2"></i> Добавить вопрос
      </button>
    </div>

    <!-- КАРТОЧКА СОЗДАНИЯ НОВОГО FAQ -->
    <div v-if="showAddForm" class="add-faq-card mb-4 animate__animated animate__fadeIn">
      <div class="card-header-custom mb-3">
        <h4 class="m-0">Новая запись FAQ</h4>
      </div>
      
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label-custom">Вопрос для пользователей</label>
          <input v-model="newFaq.question" class="form-control admin-input" placeholder="Например: Какие документы нужны для аренды?" />
        </div>
        <div class="col-md-4">
          <label class="form-label-custom">Статус публикации</label>
          <select v-model="newFaq.is_active" class="form-select admin-input">
            <option :value="true">Опубликован (Активен)</option>
            <option :value="false">Черновик (Неактивен)</option>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label-custom">Подробный ответ</label>
          <textarea v-model="newFaq.answer" class="form-control admin-input" rows="3" placeholder="Напишите понятный и развернутый ответ..."></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 mt-4">
        <button @click="showAddForm = false" class="btn btn-cancel">Отмена</button>
        <button @click="createFaq" class="btn btn-save">Сохранить FAQ</button>
      </div>
    </div>

    <!-- ТАБЛИЦА С УПРАВЛЕНИЕМ ДАННЫМИ -->
    <div class="table-responsive table-card">
      <table class="table custom-admin-table m-0">
        <thead>
          <tr>
            <th width="70">ID</th>
            <th width="300">Вопрос</th>
            <th>Ответ</th>
            <th width="140">Статус</th>
            <th width="160" class="text-end">Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="f in faqs" :key="f.id" :class="{'table-row-editing': editId === f.id}">
            <!-- ID вопроса -->
            <td class="align-middle text-muted fw-semibold">#{{ f.id }}</td>

            <!-- Поле: Вопрос -->
            <td class="align-middle fw-semibold text-dark cell-text-wrap">
              <span v-if="editId !== f.id">{{ f.question }}</span>
              <input v-else v-model="form.question" class="form-control admin-table-input" />
            </td>

            <!-- Поле: Ответ -->
            <td class="align-middle text-muted cell-text-truncate">
              <span v-if="editId !== f.id" :title="f.answer">{{ f.answer }}</span>
              <textarea v-else v-model="form.answer" class="form-control admin-table-input" rows="1"></textarea>
            </td>

            <!-- Поле: Статус активности -->
            <td class="align-middle">
              <span v-if="editId !== f.id" class="badge-status" :class="f.is_active ? 'status-active' : 'status-disabled'">
                {{ f.is_active ? 'Активен' : 'Неактивен' }}
              </span>
              <select v-else v-model="form.is_active" class="form-select admin-table-input-select">
                <option :value="true">Активен</option>
                <option :value="false">Неактивен</option>
              </select>
            </td>

            <!-- Панель действий -->
            <td class="align-middle text-end">
              <div class="d-flex justify-content-end gap-1" v-if="editId !== f.id">
                <button @click="startEdit(f)" class="btn btn-action-icon btn-edit-icon">
                  Изменить
                </button>
                <button @click="remove(f.id)" class="btn btn-action-icon btn-delete-icon">
                  Удалить
                </button>
              </div>
              <div class="d-flex justify-content-end gap-1" v-else>
                <button @click="save(f.id)" class="btn btn-action-icon btn-save-icon">
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
import { getFaqs, addFaq, updateFaq, deleteFaq } from '../../auth'

const faqs = ref([])
const editId = ref(null)
const form = ref({})
const showAddForm = ref(false)
const newFaq = ref({ question: '', answer: '', is_active: true })

const load = async () => {
  const res = await getFaqs()
  faqs.value = res.data
}

const startEdit = (f) => {
  editId.value = f.id
  form.value = { ...f }
}

const save = async (id) => {
  await updateFaq(id, form.value)
  editId.value = null
  load()
}

const remove = async (id) => {
  if (confirm('Вы действительно хотите безвозвратно удалить этот вопрос из списка?')) {
    await deleteFaq(id)
    load()
  }
}

const createFaq = async () => {
  await addFaq(newFaq.value)
  showAddForm.value = false
  newFaq.value = { question: '', answer: '', is_active: true }
  load()
}

onMounted(load)
</script>

<style scoped>
/* СТИЛИЗАЦИЯ ЗАГОЛОВКА */
.section-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #111827;
  letter-spacing: -0.5px;
}

/* КНОПКА ДОБАВЛЕНИЯ ЗАПИСИ */
.btn-add-primary {
  background-color: #111827;
  color: #ffffff;
  font-size: 0.88rem;
  font-weight: 600;
  padding: 10px 18px;
  border: 1px solid #111827;
  border-radius: 8px;
  transition: all 0.2s ease;
}
.btn-add-primary:hover {
  background-color: #ffffff;
  color: #111827;
}

/* КАРТОЧКА ФОРМЫ НОВОГО ВОПРОСА */
.add-faq-card {
  background-color: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
}
.card-header-custom h4 {
  font-size: 1.1rem;
  font-weight: 700;
  color: #111827;
}
.form-label-custom {
  font-size: 0.78rem;
  font-weight: 600;
  color: #4b5563;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  margin-bottom: 6px;
}

/* СТИЛИ ИНПУТОВ И ТЕКСТОВЫХ ПОЛЕЙ */
.admin-input {
  background-color: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  padding: 10px 12px;
  font-size: 0.9rem;
  color: #111827;
  transition: all 0.2s ease;
}
.admin-input:focus {
  background-color: #ffffff;
  border-color: #111827;
  box-shadow: none;
}

/* КНОПКИ ВНУТРИ КАРТОЧКИ ФОРМЫ */
.btn-save {
  background-color: #111827;
  color: #ffffff;
  font-size: 0.88rem;
  font-weight: 600;
  padding: 10px 20px;
  border: 1px solid #111827;
  border-radius: 6px;
}
.btn-save:hover {
  background-color: #22c55e;
  border-color: #22c55e;
  color: white;
}
.btn-cancel {
  background-color: #ffffff;
  color: #4b5563;
  font-size: 0.88rem;
  font-weight: 500;
  padding: 10px 20px;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
}
.btn-cancel:hover {
  background-color: #f9fafb;
  color: #111827;
}

/* ТАБЛИЧНАЯ ОБЛАСТЬ КОРПОРАТИВНОГО ТИПА */
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

/* СТИЛИ ЯЧЕЕК С ДЛИННЫМИ СТРОКАМИ И ТЕКСТОМ */
.cell-text-wrap {
  max-width: 300px;
  line-height: 1.4;
}
.cell-text-truncate {
  max-width: 450px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* ИНЛАЙН-ИНПУТЫ ВНУТРИ ТАБЛИЦЫ */
.table-row-editing {
  background-color: #f8fafc !important;
}
.admin-table-input {
  padding: 6px 10px;
  font-size: 0.85rem;
  border: 1px solid #cbd5e1;
  border-radius: 4px;
}

.admin-table-input-select {
  padding: 6px 24px 6px 10px;
  font-size: 0.85rem;
  border: 1px solid #cbd5e1;
  border-radius: 4px;
}

/* БЕЙДЖИ СТАТУСОВ АКТИВНОСТИ */
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

/* МИНИМАЛИСТИЧНЫЕ КНОПКИ ДЕЙСТВИЙ */
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
</style>