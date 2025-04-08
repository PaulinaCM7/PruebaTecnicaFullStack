<template>
    <div>
        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-semibold">Medios</h2>
            <button @click="openForm()" class="bg-blue-600 text-white px-4 py-2 rounded">+ Nuevo Medio</button>
        </div>

        <div v-if="loading">Cargando...</div>

        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Nombre</th>
                    <th>Ubicación</th>
                    <th>Tipo</th>
                    <th>Precio</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="media in medios" :key="media.id" class="border-t">
                    <td class="p-2">{{ media.name }}</td>
                    <td>{{ media.location }}</td>
                    <td>{{ media.type }}</td>
                    <td>${{ media.price_per_day }}</td>
                    <td>
                        <button @click="openForm(media)" class="text-blue-500 mr-2">Editar</button>
                        <button @click="deleteMedia(media.id)" class="text-red-500">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <MediaForm v-if="formVisible" :media="selected" @close="closeForm" @saved="fetchMedios" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import MediaForm from './MediaForm.vue'

const medios = ref([])
const loading = ref(true)
const formVisible = ref(false)
const selected = ref(null)

const fetchMedios = async () => {
    loading.value = true
    const res = await axios.get('/api/admin/media')
    medios.value = res.data.data
    loading.value = false
}

const openForm = (media = null) => {
    selected.value = media
    formVisible.value = true
}

const closeForm = () => {
    selected.value = null
    formVisible.value = false
}

const deleteMedia = async (id) => {
    if (confirm('¿Eliminar medio?')) {
        await axios.delete(`/api/admin/media/${id}`)
        fetchMedios()
    }
}

onMounted(fetchMedios)
</script>
