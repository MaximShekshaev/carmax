<template>
  <div class="cars-management-section">
    <!-- ЗАГОЛОВОК И КНОПКА ДОБАВЛЕНИЯ -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="section-title m-0">Управление автопарком</h2>
      <button 
        v-if="!showAddForm" 
        @click="showAddForm = true" 
        class="btn btn-add-primary"
      >
        <i class="bi bi-plus-lg me-2"></i> Добавить автомобиль
      </button>
    </div>

    <!-- КАРТОЧКА ФОРМЫ ДОБАВЛЕНИЯ НОВОЙ МАШИНЫ -->
    <div v-if="showAddForm" class="add-car-card mb-4 animate__animated animate__fadeIn">
      <div class="card-header-custom mb-3">
        <h4 class="m-0">Новый автомобиль</h4>
      </div>
      
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label-custom">Название модели</label>
          <input v-model="newCar.name" class="form-control admin-input" placeholder="Например, Civic" />
        </div>
        <div class="col-md-4">
          <label class="form-label-custom">Бренд / Марка</label>
          <input v-model="newCar.brand" class="form-control admin-input" placeholder="Например, Honda" />
        </div>
        <div class="col-md-4">
          <label class="form-label-custom">Стоимость в сутки ($)</label>
          <input v-model.number="newCar.price_per_day" type="number" class="form-control admin-input" placeholder="Цена" />
        </div>
        <div class="col-md-6">
          <label class="form-label-custom">Ссылка на изображение</label>
          <input v-model="newCar.image" class="form-control admin-input" placeholder="https://example.com/image.jpg" />
        </div>
        <div class="col-md-6">
          <label class="form-label-custom">Статус доступности</label>
          <select v-model="newCar.available" class="form-select admin-input">
            <option :value="true">Доступен для аренды</option>
            <option :value="false">В архиве / На ремонте</option>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label-custom">Краткое описание автомобиля</label>
          <textarea v-model="newCar.description" class="form-control admin-input" rows="2" placeholder="Опишите комплектацию или особенности..."></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 mt-4">
        <button @click="showAddForm = false" class="btn btn-cancel">Отмена</button>
        <button @click="createCar" class="btn btn-save">Сохранить авто</button>
      </div>
    </div>

    <!-- ТАБЛИЦА АВТОПАРКА -->
    <div class="table-responsive table-card">
      <table class="table custom-admin-table m-0">
        <thead>
          <tr>
            <th width="60">ID</th>
            <th width="100">Фото</th>
            <th>Модель</th>
            <th>Бренд</th>
            <th>Цена / день</th>
            <th>Статус</th>
            <th width="300">Описание</th>
            <th width="160" class="text-end">Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="car in cars" :key="car.id" :class="{'table-row-editing': editId === car.id}">
            <!-- ID автомобиля -->
            <td class="align-middle text-muted fw-semibold">#{{ car.id }}</td>

            <!-- Изображение автомобиля -->
            <td class="align-middle">
              <div class="car-img-wrapper" v-if="editId !== car.id">
                <img v-if="car.image" :src="car.image" alt="Car thumbnail" />
                <div v-else class="img-placeholder"><i class="bi bi-image"></i></div>
              </div>
              <input v-else v-model="form.image" class="form-control admin-table-input" placeholder="URL" />
            </td>

            <!-- Название модели -->
            <td class="align-middle fw-bold text-dark">
              <span v-if="editId !== car.id">{{ car.name }}</span>
              <input v-else v-model="form.name" class="form-control admin-table-input" />
            </td>

            <!-- Бренд -->
            <td class="align-middle text-secondary">
              <span v-if="editId !== car.id">{{ car.brand }}</span>
              <input v-else v-model="form.brand" class="form-control admin-table-input" />
            </td>

            <!-- Цена -->
            <td class="align-middle fw-semibold text-dark">
              <span v-if="editId !== car.id">${{ car.price_per_day }}</span>
              <input v-else v-model.number="form.price_per_day" type="number" class="form-control admin-table-input" />
            </td>

            <!-- Доступность (Бейджи) -->
            <td class="align-middle">
              <span v-if="editId !== car.id" class="badge-status" :class="car.available ? 'status-active' : 'status-disabled'">
                {{ car.available ? 'Доступен' : 'Занят' }}
              </span>
              <select v-else v-model="form.available" class="form-select admin-table-input-select">
                <option :value="true">Доступен</option>
                <option :value="false">Занят</option>
              </select>
            </td>

            <!-- Описание -->
            <td class="align-middle text-truncate-custom text-muted">
              <span v-if="editId !== car.id">{{ car.description || '—' }}</span>
              <input v-else v-model="form.description" class="form-control admin-table-input" />
            </td>

            <!-- Действия -->
            <td class="align-middle text-end">
              <div class="d-flex justify-content-end gap-1" v-if="editId !== car.id">
                <button @click="startEdit(car)" class="btn btn-action-icon btn-edit-icon" title="Редактировать">
                  <i class="bi bi-pencil-semibold">Изменить</i>
                </button>
                <button @click="remove(car.id)" class="btn btn-action-icon btn-delete-icon" title="Удалить">
                  <i class="bi bi-trash">Удалить</i>
                </button>
              </div>
              <div class="d-flex justify-content-end gap-1" v-else>
                <button @click="save(car.id)" class="btn btn-action-icon btn-save-icon" title="Сохранить изменения">
                  <i class="bi bi-check-lg">Ок</i>
                </button>
                <button @click="editId = null" class="btn btn-action-icon btn-cancel-icon" title="Отмена">
                  <i class="bi bi-x-lg">Х</i>
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
import { getCars, addCar, updateCar, deleteCar } from '../../auth'

const cars = ref([])
const editId = ref(null)
const form = ref({})
const showAddForm = ref(false)
const newCar = ref({
  name: '',
  brand: '',
  price_per_day: 0,
  image: '',
  description: '',
  available: true
})

const load = async () => {
  const res = await getCars()
  cars.value = res.data
}

const startEdit = (car) => {
  editId.value = car.id
  form.value = { ...car }
}

const save = async (id) => {
  await updateCar(id, form.value)
  editId.value = null
  load()
}

const remove = async (id) => {
  if (confirm('Вы уверены, что хотите удалить этот автомобиль из базы данных?')) {
    await deleteCar(id)
    load()
  }
}

const createCar = async () => {
  await addCar(newCar.value)
  showAddForm.value = false
  newCar.value = {
    name: '',
    brand: '',
    price_per_day: 0,
    image: '',
    description: '',
    available: true
  }
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

/* КНОПКА ДОБАВЛЕНИЯ ПРЕДЛОЖЕНИЯ */
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

/* СТИЛИЗАЦИЯ КАРТОЧКИ ДЛЯ ФОРМЫ НОВОЙ МАШИНЫ */
.add-car-card {
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

/* ИНПУТЫ ДЛЯ ПАНЕЛИ УПРАВЛЕНИЯ */
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

/* УПРАВЛЯЮЩИЕ КНОПКИ ВНУТРИ КАРТОЧКИ ФОРМЫ */
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

/* ТАБЛИЧНЫЙ БЛОК НА ДАШБОРДЕ */
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

/* РЕЖИМ РЕДАКТИРОВАНИЯ ТАБЛИЧНОЙ СТРОКИ */
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

/* ОГРАНИЧЕНИЕ ТЕКСТА ДЛЯ КРАСИВОГО ВЫВОДА ОПИСАНИЯ */
.text-truncate-custom {
  max-width: 280px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* ОБЕРТКА МИНИАТЮРЫ КАРТИНКИ */
.car-img-wrapper {
  width: 54px;
  height: 38px;
  border-radius: 6px;
  overflow: hidden;
  background-color: #f3f4f6;
  border: 1px solid #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: center;
}
.car-img-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.img-placeholder {
  color: #9ca3af;
  font-size: 0.9rem;
}

/* СТАТУСНЫЕ БЕЙДЖИ ДЛЯ ПОЛЕЙ ДОСТУПНОСТИ */
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

/* МИНИ-КНОПКИ ТАБЛИЧНЫХ ДЕЙСТВИЙ */
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