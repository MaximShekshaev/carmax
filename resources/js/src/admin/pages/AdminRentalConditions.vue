<template>
  <div class="conditions-management-section">
    <!-- ХЕДЕР СЕКЦИИ -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="section-title m-0">Условия аренды</h2>
      <button 
        v-if="!showAddForm" 
        @click="showAddForm = true" 
        class="btn btn-add-primary"
      >
        <i class="bi bi-plus-lg me-2"></i> Добавить условие
      </button>
    </div>

    <!-- КАРТОЧКА СОЗДАНИЯ НОВОГО УСЛОВИЯ -->
    <div v-if="showAddForm" class="add-condition-card mb-4 animate__animated animate__fadeIn">
      <div class="card-header-custom mb-3">
        <h4 class="m-0">Новое условие аренды</h4>
      </div>
      
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label-custom">Название условия</label>
          <input v-model="newCondition.title" class="form-control admin-input" placeholder="Например: Возраст водителя" />
        </div>
        <div class="col-md-6">
          <label class="form-label-custom">Иконка (URL или CSS класс)</label>
          <input v-model="newCondition.icon" class="form-control admin-input" placeholder="Например: bi-person-badge или URL" />
        </div>
        <div class="col-12">
          <label class="form-label-custom">Детальное описание</label>
          <textarea v-model="newCondition.description" class="form-control admin-input" rows="2" placeholder="Опишите подробные требования или ограничения для арендатора..."></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 mt-4">
        <button @click="showAddForm = false" class="btn btn-cancel">Отмена</button>
        <button @click="createCondition" class="btn btn-save">Сохранить условие</button>
      </div>
    </div>

    <!-- ТАБЛИЦА С УСЛОВИЯМИ -->
    <div class="table-responsive table-card">
      <table class="table custom-admin-table m-0">
        <thead>
          <tr>
            <th width="70">ID</th>
            <th width="100" class="text-center">Иконка</th>
            <th width="240">Название</th>
            <th>Описание</th>
            <th width="160" class="text-end">Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="c in conditions" :key="c.id" :class="{'table-row-editing': editId === c.id}">
            <!-- ID условия -->
            <td class="align-middle text-muted fw-semibold">#{{ c.id }}</td>

            <!-- Иконка (Превью) -->
            <td class="align-middle text-center">
              <div v-if="editId !== c.id" class="d-flex justify-content-center">
                <div class="icon-preview-box">
                  <!-- Проверка: картинка или класс иконки -->
                  <img v-if="c.icon && (c.icon.startsWith('http') || c.icon.startsWith('/'))" :src="c.icon" alt="icon" />
                  <i v-else-if="c.icon" class="bi" :class="c.icon"></i>
                  <i v-else class="bi bi-app text-muted"></i>
                </div>
              </div>
              <input v-else v-model="form.icon" class="form-control admin-table-input text-center" placeholder="Иконка" />
            </td>

            <!-- Название -->
            <td class="align-middle fw-bold text-dark">
              <span v-if="editId !== c.id">{{ c.title }}</span>
              <input v-else v-model="form.title" class="form-control admin-table-input" />
            </td>

            <!-- Описание -->
            <td class="align-middle text-muted cell-text-wrap">
              <span v-if="editId !== c.id">{{ c.description || '—' }}</span>
              <textarea v-else v-model="form.description" class="form-control admin-table-input" rows="2"></textarea>
            </td>

            <!-- Действия -->
            <td class="align-middle text-end">
              <div class="d-flex justify-content-end gap-1" v-if="editId !== c.id">
                <button @click="startEdit(c)" class="btn btn-action-icon btn-edit-icon">
                  Изменить
                </button>
                <button @click="remove(c.id)" class="btn btn-action-icon btn-delete-icon">
                  Удалить
                </button>
              </div>
              <div class="d-flex justify-content-end gap-1" v-else>
                <button @click="save(c.id)" class="btn btn-action-icon btn-save-icon">
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
import { getRentalConditions, addRentalCondition, updateRentalCondition, deleteRentalCondition } from '../../auth'

const conditions = ref([])
const editId = ref(null)
const form = ref({})
const showAddForm = ref(false)
const newCondition = ref({
  title: '',
  description: '',
  icon: ''
})

const load = async () => {
  const res = await getRentalConditions()
  conditions.value = res.data
}

const startEdit = (c) => {
  editId.value = c.id
  form.value = { ...c }
}

const save = async (id) => {
  await updateRentalCondition(id, form.value)
  editId.value = null
  load()
}

const remove = async (id) => {
  if (confirm('Вы уверены, что хотите удалить это условие аренды? Данное действие может повлиять на отображение правил на главной странице.')) {
    await deleteRentalCondition(id)
    load()
  }
}

const createCondition = async () => {
  await addRentalCondition(newCondition.value)
  showAddForm.value = false
  newCondition.value = { title: '', description: '', icon: '' }
  load()
}

onMounted(load)
</script>

<style scoped>
/* ЗАГОЛОВОК СЕКЦИИ */
.section-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #111827;
  letter-spacing: -0.5px;
}

/* КНОПКА ДОБАВЛЕНИЯ ПРАВИЛА */
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

/* КАРТОЧКА ФОРМЫ */
.add-condition-card {
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

/* СТИЛИЗАЦИЯ СТАНДАРТНЫХ ИНПУТОВ */
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

/* УПРАВЛЕНИЕ КНОПКАМИ ФОРМЫ */
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

/* ТАБЛИЦА КОРПОРАТИВНОГО ТИПА */
.table-card {
  background-color: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
}
.custom-admin-table {
  width: 100%;
  border-collapse: collapse;
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

/* ЯЧЕЙКА ТЕКСТА ОПИСАНИЯ */
.cell-text-wrap {
  max-width: 400px;
  line-height: 1.5;
}

/* ПРЕВЬЮ КОМПОНЕНТА ИКОНОК */
.icon-preview-box {
  width: 36px;
  height: 36px;
  background-color: #f3f4f6;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  color: #111827;
}
.icon-preview-box img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 7px;
}

/* ТАБЛИЧНЫЕ ИНПУТЫ ДЛЯ РЕДАКТИРОВАНИЯ */
.table-row-editing {
  background-color: #f8fafc !important;
}
.admin-table-input {
  padding: 6px 10px;
  font-size: 0.85rem;
  border: 1px solid #cbd5e1;
  border-radius: 4px;
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
</style>