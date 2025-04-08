<template>
    <BaseLayout>
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Gestión de Medios</h1>
                <button @click="abrirFormulario()"
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    + Nuevo Medio
                </button>
            </div>

            <Toast v-if="toast.message" :type="toast.type" :title="toast.title" :message="toast.message" />

            <div class="overflow-x-auto bg-white rounded shadow">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="p-3">Nombre</th>
                            <th class="p-3">Ubicación</th>
                            <th class="p-3">Tipo</th>
                            <th class="p-3">Precio</th>
                            <th class="p-3">Imagen</th>
                            <th class="p-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="medio in medios" :key="medio.id" class="border-b">
                            <td class="p-3">{{ medio.name }}</td>
                            <td class="p-3">{{ medio.location }}</td>
                            <td class="p-3">{{ medio.type }}</td>
                            <td class="p-3">${{ medio.price_per_day }}</td>
                            <td class="p-3">
                                <img v-if="medio.image" :src="medio.image" class="h-16 w-24 object-cover rounded" />
                            </td>
                            <td class="p-3 flex gap-2">
                                <button @click="abrirFormulario(medio)"
                                    class="text-blue-600 hover:underline">Editar</button>
                                <button @click="eliminar(medio.id)"
                                    class="text-red-600 hover:underline">Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="mostrarFormulario" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded shadow w-full max-w-md">
                    <h2 class="text-xl font-semibold mb-4">{{ form.id ? 'Editar' : 'Crear' }} Medio</h2>

                    <div class="flex flex-col gap-2 mb-4">
                        <input v-model="form.name" placeholder="Nombre" class="p-2 border rounded" />
                        <input v-model="form.location" placeholder="Ubicación" class="p-2 border rounded" />
                        <input v-model="form.type" placeholder="Tipo" class="p-2 border rounded" />
                        <input v-model.number="form.price_per_day" type="number" min="1" placeholder="Precio por día"
                            class="p-2 border rounded" />

                        <input type="file" @change="handleImage" class="p-2 border rounded" />
                        <img v-if="preview" :src="preview" class="h-32 object-cover rounded" />
                    </div>

                    <div class="flex justify-between">
                        <button @click="guardar"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Guardar</button>
                        <button @click="cerrarFormulario" class="text-gray-600 hover:underline">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<script setup>
import BaseLayout from '../../Layouts/BaseLayout.vue'
import Toast from '@/Components/Toast.vue'
import { ref, onMounted } from 'vue'
import { getAuthenticated } from '@/api.js'
import axios from 'axios'

const medios = ref([])
const mostrarFormulario = ref(false)
const form = ref({ name: '', location: '', type: '', price_per_day: '', image: '' })
const file = ref(null)
const preview = ref(null)
const toast = ref({ type: '', title: '', message: '' })

const showToast = (type, title, message) => {
    toast.value = { type, title, message }
}

const cargarMedios = async () => {
    try {
        const res = await getAuthenticated('/api/admin/media')
        medios.value = res.data.data || res.data
    } catch (error) {
        console.error('Error al cargar medios:', error)
    }
}

const abrirFormulario = (medio = null) => {
    form.value = medio ? { ...medio } : { name: '', location: '', type: '', price_per_day: '', image: '' }
    preview.value = medio?.image || null
    file.value = null
    mostrarFormulario.value = true
}

const cerrarFormulario = () => {
    mostrarFormulario.value = false
    form.value = { name: '', location: '', type: '', price_per_day: '', image: '' }
    file.value = null
    preview.value = null
}

const handleImage = (e) => {
    file.value = e.target.files[0]
    preview.value = URL.createObjectURL(file.value)
}

const guardar = async () => {
    if (!form.value.name || !form.value.location || !form.value.type) {
        showToast('error', 'Campos incompletos', 'Todos los campos son obligatorios.')
        return
    }

    if (!form.value.price_per_day || form.value.price_per_day < 1) {
        showToast('error', 'Precio inválido', 'El precio por día debe ser al menos 1.')
        return
    }

    if (!form.value.id && !file.value) {
        showToast('error', 'Imagen requerida', 'Debes subir una imagen para el nuevo medio.')
        return
    }

    let imagePath = form.value.image

    if (file.value) {
        const formData = new FormData()
        formData.append('image', file.value)
        try {
            const res = await axios.post('/api/admin/media/upload', formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
                withCredentials: true
            })
            imagePath = res.data.url
        } catch (err) {
            showToast('error', 'Error al subir imagen', 'Verifica el archivo e intenta nuevamente.')
            return
        }
    }

    const payload = { ...form.value, image: imagePath }

    try {
        if (form.value.id) {
            await axios.put(`/api/admin/media/${form.value.id}`, payload, { withCredentials: true })
            showToast('success', 'Medio actualizado', 'El medio ha sido actualizado correctamente.')
        } else {
            await axios.post('/api/admin/media', payload, { withCredentials: true })
            showToast('success', 'Medio creado', 'Se ha creado un nuevo medio.')
        }

        await cargarMedios()
        cerrarFormulario()
    } catch (err) {
        showToast('error', 'Error', 'Ocurrió un error al guardar el medio.')
    }
}

const eliminar = async (id) => {
    if (confirm('¿Estás seguro de eliminar este medio?')) {
        await axios.delete(`/api/admin/media/${id}`, { withCredentials: true })
        await cargarMedios()
        showToast('success', 'Medio eliminado', 'El medio ha sido eliminado correctamente.')
    }
}

onMounted(cargarMedios)
</script>
