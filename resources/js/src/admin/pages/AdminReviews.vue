<template>
  <div>
    <h2>Отзывы клиентов</h2>

    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Имя</th>
          <th>Сообщение</th>
          <th>Рейтинг</th>
          <th>Статус</th>
          <th>Дата</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="r in reviews" :key="r.id">
          <td>{{ r.id }}</td>

          <td v-if="editId !== r.id">{{ r.name }}</td>
          <td v-else><input v-model="form.name" /></td>

          <td v-if="editId !== r.id">{{ r.message }}</td>
          <td v-else><input v-model="form.message" /></td>

          <td v-if="editId !== r.id">{{ r.rating }}</td>
          <td v-else>
            <select v-model.number="form.rating">
              <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
            </select>
          </td>

          <td v-if="editId !== r.id">{{ r.is_active ? 'Активен' : 'Скрыт' }}</td>
          <td v-else>
            <select v-model="form.is_active">
              <option :value="true">Активен</option>
              <option :value="false">Скрыт</option>
            </select>
          </td>

          <td>{{ new Date(r.created_at).toLocaleDateString() }}</td>

          <td>
            <button v-if="editId !== r.id" @click="startEdit(r)">Изменить</button>
            <button v-else @click="save(r.id)">Сохранить</button>
            <button @click="remove(r.id)">Удалить</button>
          </td>
        </tr>
      </tbody>
    </table>
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
    console.error(e)
  }
}

const startEdit = (r) => {
  editId.value = r.id
  form.value = { ...r }
}

const save = async (id) => {
  try {
    await toggleReview(id) 
    editId.value = null
    load()
  } catch (e) {
    console.error(e)
  }
}

const remove = async (id) => {
  if (confirm('Удалить отзыв?')) {
    try {
      await deleteReview(id)
      load()
    } catch (e) {
      console.error(e)
    }
  }
}

onMounted(load)
</script>

<style scoped>
.table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}
th, td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}
input, select {
  width: 100%;
  padding: 4px;
  box-sizing: border-box;
}
button {
  margin-right: 5px;
  margin-top: 5px;
  padding: 5px 10px;
  border-radius: 5px;
  border: none;
  cursor: pointer;
}
</style>
