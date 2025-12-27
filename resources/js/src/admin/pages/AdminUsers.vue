<template>
  <div class="container">
    <h2>Список пользователей</h2>

    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Имя</th>
          <th>Email</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>

          <td v-if="editId !== user.id">{{ user.name }}</td>
          <td v-else>
            <input v-model="form.name" />
          </td>

          <td v-if="editId !== user.id">{{ user.email }}</td>
          <td v-else>
            <input v-model="form.email" />
          </td>

          <td>
            <button v-if="editId !== user.id" @click="startEdit(user)">Изменить</button>
            <button v-else @click="save(user.id)">Сохранить</button>
            <button @click="remove(user.id)">Удалить</button>
          </td>
        </tr>
      </tbody>
    </table>
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
    alert('Ошибка: ' + err.response.data.message)
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
  if (confirm('Удалить пользователя?')) {
    await deleteUser(id)
    load()
  }
}

onMounted(load)
</script>

<style scoped>
  button {
  margin-right: 5px;
  margin-top: 5px;
  padding: 5px 10px;
  border-radius: 5px;
  border: none;
  cursor: pointer;
}
.table 
{ 
  width: 100%;
   border-collapse: collapse; 
  }
th, td 
{ 
  border: 1px solid #ddd; 
  padding: 8px;
}
input 
{ 
  width: 100%;
 }
button 
{ 
  margin-right: 5px;
 }
</style>
