<template>
  <div class="users-management-section">
    <!-- ХЕДЕР СЕКЦИИ -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="section-title m-0">Список клиентов</h2>
      <span class="badge-count">Всего: {{ users.length }}</span>
    </div>

    <!-- ТАБЛИЦА КЛИЕНТОВ -->
    <div class="table-responsive table-card">
      <table class="table custom-admin-table m-0">
        <thead>
          <tr>
            <th width="80">ID</th>
            <th>Клиент</th>
            <th>Email адрес</th>
            <th width="200" class="text-end">Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id" :class="{'table-row-editing': editId === user.id}">
            <!-- ID пользователя -->
            <td class="align-middle text-muted fw-semibold">#{{ user.id }}</td>

            <!-- Имя пользователя (+ Аватар-заглушка) -->
            <td class="align-middle">
              <div v-if="editId !== user.id" class="d-flex align-items-center gap-3">
                <div class="user-avatar-placeholder">
                  {{ user.name ? user.name.charAt(0).toUpperCase() : 'U' }}
                </div>
                <span class="fw-bold text-dark">{{ user.name }}</span>
              </div>
              <div v-else class="d-flex align-items-center gap-2">
                <i class="bi bi-person text-muted fs-5"></i>
                <input v-model="form.name" class="form-control admin-table-input" placeholder="Имя Фамилия" />
              </div>
            </td>

            <!-- Электронная почта -->
            <td class="align-middle text-muted">
              <span v-if="editId !== user.id">{{ user.email }}</span>
              <div v-else class="d-flex align-items-center gap-2">
                <i class="bi bi-envelope text-muted animate__animated animate__fadeIn"></i>
                <input v-model="form.email" type="email" class="form-control admin-table-input" placeholder="email@example.com" />
              </div>
            </td>

            <!-- Действия над аккаунтом -->
            <td class="align-middle text-end">
              <div class="d-flex justify-content-end gap-1" v-if="editId !== user.id">
                <button @click="startEdit(user)" class="btn btn-action-icon btn-edit-icon">
                  Изменить
                </button>
                <button @click="remove(user.id)" class="btn btn-action-icon btn-delete-icon">
                  Удалить
                </button>
              </div>
              <div class="d-flex justify-content-end gap-1" v-else>
                <button @click="save(user.id)" class="btn btn-action-icon btn-save-icon">
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
import { getUsers, updateUser, deleteUser } from '../../auth'

const users = ref([])
const editId = ref(null)
const form = ref({ name: '', email: '' })

const load = async () => {
  try {
    const res = await getUsers()
    users.value = res.data
  } catch (err) {
    console.error('Ошибка при загрузке списка пользователей:', err?.response?.data?.message || err.message)
  }
}

const startEdit = (user) => {
  editId.value = user.id
  form.value = { name: user.name, email: user.email }
}

const save = async (id) => {
  await updateUser(id, form.value)
  editId.value = null
  load()
}

const remove = async (id) => {
  if (confirm('Вы уверены, что хотите полностью удалить профиль клиента и закрыть ему доступ к системе?')) {
    await deleteUser(id)
    load()
  }
}

onMounted(load)
</script>

<style scoped>
/* ЗАГОЛОВОК И СЧЕТЧИК */
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

/* СТРУКТУРА КАРТОЧКИ ТАБЛИЦЫ */
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

/* ДЕЛИКАТНЫЙ АВАТАР КЛИЕНТА */
.user-avatar-placeholder {
  width: 32px;
  height: 32px;
  background-color: #f3f4f6;
  color: #4b5563;
  font-weight: 600;
  font-size: 0.85rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #e5e7eb;
  user-select: none;
}

/* ИНПУТЫ ДЛЯ СТРОК ТАБЛИЦЫ */
.table-row-editing {
  background-color: #f8fafc !important;
}

.admin-table-input {
  padding: 6px 10px;
  font-size: 0.88rem;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  background-color: #ffffff;
  color: #111827;
  transition: border-color 0.15s ease;
}

.admin-table-input:focus {
  border-color: #111827;
  box-shadow: none;
  outline: none;
}

/* КНОПКИ ДЕЙСТВИЙ НА ПАНЕЛИ */
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