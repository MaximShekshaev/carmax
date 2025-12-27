<template>
  <div>
    <h2>Частые вопросы</h2>

    <button @click="showAddForm = true">Добавить вопрос</button>

    <div v-if="showAddForm" class="add-form">
      <input v-model="newFaq.question" placeholder="Вопрос" />
      <input v-model="newFaq.answer" placeholder="Ответ" />
      <select v-model="newFaq.is_active">
        <option :value="true">Активен</option>
        <option :value="false">Неактивен</option>
      </select>
      <button @click="createFaq">Сохранить</button>
      <button @click="showAddForm = false">Отмена</button>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Вопрос</th>
          <th>Ответ</th>
          <th>Статус</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="f in faqs" :key="f.id">
          <td>{{ f.id }}</td>

          <td v-if="editId !== f.id">{{ f.question }}</td>
          <td v-else><input v-model="form.question" /></td>

          <td v-if="editId !== f.id">{{ f.answer }}</td>
          <td v-else><input v-model="form.answer" /></td>

          <td v-if="editId !== f.id">{{ f.is_active ? 'Активен' : 'Неактивен' }}</td>
          <td v-else>
            <select v-model="form.is_active">
              <option :value="true">Активен</option>
              <option :value="false">Неактивен</option>
            </select>
          </td>

          <td>
            <button v-if="editId !== f.id" @click="startEdit(f)">Изменить</button>
            <button v-else @click="save(f.id)">Сохранить</button>
            <button @click="remove(f.id)">Удалить</button>
          </td>
        </tr>
      </tbody>
    </table>
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
  if (confirm('Удалить вопрос?')) {
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

input, select
 { 
    width: 100%; padding: 4px;
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
{ 
    margin: 15px 0;
     display: flex; 
     gap: 5px; 
     flex-wrap: wrap;
     }
.add-form input, .add-form select 
{ 
    width: 200px; 
}
</style>
