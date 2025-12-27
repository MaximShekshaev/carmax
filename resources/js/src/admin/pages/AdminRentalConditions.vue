<template>
  <div>
    <h2>Условия аренды</h2>

    <button @click="showAddForm = true">Добавить условие</button>

    <div v-if="showAddForm" class="add-form">
      <input v-model="newCondition.title" placeholder="Название" />
      <input v-model="newCondition.description" placeholder="Описание" />
      <input v-model="newCondition.icon" placeholder="Иконка (URL)" />
      <button @click="createCondition">Сохранить</button>
      <button @click="showAddForm = false">Отмена</button>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Название</th>
          <th>Описание</th>
          <th>Иконка</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="c in conditions" :key="c.id">
          <td>{{ c.id }}</td>

          <td v-if="editId !== c.id">{{ c.title }}</td>
          <td v-else><input v-model="form.title" /></td>

          <td v-if="editId !== c.id">{{ c.description }}</td>
          <td v-else><input v-model="form.description" /></td>

          <td v-if="editId !== c.id">{{ c.icon }}</td>
          <td v-else><input v-model="form.icon" /></td>

          <td>
            <button v-if="editId !== c.id" @click="startEdit(c)">Изменить</button>
            <button v-else @click="save(c.id)">Сохранить</button>
            <button @click="remove(c.id)">Удалить</button>
          </td>
        </tr>
      </tbody>
    </table>
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
  if (confirm('Удалить условие?')) {
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
.table 
{ 
    width: 100%; 
    border-collapse: collapse;
     margin-top: 15px;
     }
th, td 
{ 
    border: 1px solid #ddd;
     padding: 8px;
      text-align: left; 
    }
input 
{ 
    width: 100%;
     padding: 4px;
      box-sizing: border-box;
     }
button 
{
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
       flex-wrap: wrap; 
    }
.add-form input
 {
     width: 200px;
     
     }

</style>