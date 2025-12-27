<template>
  <div>
    <h2>Машины</h2>

    <button @click="showAddForm = true"> Добавить машину</button>

    <div v-if="showAddForm" class="add-form">
      <input v-model="newCar.name" placeholder="Название" />
      <input v-model="newCar.brand" placeholder="Бренд" />
      <input v-model.number="newCar.price_per_day" type="number" placeholder="Цена/день" />
      <input v-model="newCar.image" placeholder="URL картинки" />
      <input v-model="newCar.description" placeholder="Описание" />
      <select v-model="newCar.available">
        <option :value="true">Да</option>
        <option :value="false">Нет</option>
      </select>
      <button @click="createCar"> Сохранить</button>
      <button @click="showAddForm = false"> Отмена</button>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Название</th>
          <th>Бренд</th>
          <th>Цена/день</th>
          <th>Доступна</th>
          <th>Картинка</th>
          <th>Описание</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="car in cars" :key="car.id">
          <td>{{ car.id }}</td>

          <td v-if="editId !== car.id">{{ car.name }}</td>
          <td v-else><input v-model="form.name" /></td>

          <td v-if="editId !== car.id">{{ car.brand }}</td>
          <td v-else><input v-model="form.brand" /></td>

          <td v-if="editId !== car.id">{{ car.price_per_day }}</td>
          <td v-else><input v-model.number="form.price_per_day" /></td>

          <td v-if="editId !== car.id">{{ car.available ? 'Да' : 'Нет' }}</td>
          <td v-else>
            <select v-model="form.available">
              <option :value="true">Да</option>
              <option :value="false">Нет</option>
            </select>
          </td>

          <td v-if="editId !== car.id">
            <img v-if="car.image" :src="car.image" width="80" />
          </td>
          <td v-else><input v-model="form.image" placeholder="URL картинки" /></td>

          <td v-if="editId !== car.id">{{ car.description }}</td>
          <td v-else><input v-model="form.description" /></td>

          <td>
            <button v-if="editId !== car.id" @click="startEdit(car)">Изменить</button>
            <button v-else @click="save(car.id)">Сохранить</button>
            <button @click="remove(car.id)">Удалить</button>
          </td>
        </tr>
      </tbody>
    </table>
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
  if (confirm('Удалить машину?')) {
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
.table
 {
  width: 100%;
   border-collapse:
    collapse; 
    margin-top: 15px; }
th, td 
{ border: 1px solid #ddd;
 padding: 8px;
  text-align: left; }

input, select 
{ width: 100%;
 padding: 4px; }

button {
  margin-right: 5px;
  margin-top: 5px;
  padding: 5px 10px;
  border-radius: 5px;
  border: none;
  cursor: pointer;
}
.add-form 
{ margin: 15px 0;
 display: flex;
  gap: 5px;
   flex-wrap: wrap; }
.add-form input, .add-form select 
{ 
  width: 150px; 

}
</style>
