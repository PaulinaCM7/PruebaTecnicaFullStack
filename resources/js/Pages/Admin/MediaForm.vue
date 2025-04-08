<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-md relative">
            <h2 class="text-xl font-semibold mb-4">
                {{ media?.id ? 'Editar Medio' : 'Nuevo Medio' }}
            </h2>

            <form @submit.prevent="save">
                <input v-model="form.name" type="text" placeholder="Nombre" class="input" required />
                <input v-model="form.location" type="text" placeholder="Ubicación" class="input" required />
                <select v-model="form.type" class="input" required>
                    <option disabled value="">Tipo</option>
                    <option value="pantalla">Pantalla</option>
                    <option value="valla">Valla</option>
                    <option value="espectacular">Espectacular</option>
                    <option value="otro">Otro</option>
                </select>
                <input v-model.number="form.price_per_day" type="number" min="1" placeholder="Precio por día" class="input"
                    required />

                <div class="my-2">
                    <input type="file" @change="uploadImage" />
                    <img v-if="form.image" :src="form.image" class="h-24 mt-2" />
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" @click="$emit('close')" class="text-gray-600">Cancelar</button>
                    <button class="bg-green-600 text-white px-4 py-2 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'

const props = defineProps({ media: Object })
const emit = defineEmits(['close', 'saved'])

const form = ref({
    name: '',
    location: '',
    type: '',
    price_per_day: '',
    image: ''
})

watch(() => props.media, (newMedia) => {
    if (newMedia) form.value = { ...newMedia }
    else form.value = { name: '', location: '', type: '', price_per_day: '', image: '' }
}, { immediate: true })

const save = async () => {
    if (form.value.id) {
        await axios.put(`/api/admin/media/${form.value.id}`, form.value)
    } else {
        await axios.post('/api/admin/media', form.value)
    }
    emit('saved')
    emit('close')
}

const uploadImage = async (e) => {
    const file = e.target.files[0]
    if (!file) return
    const formData = new FormData()
    formData.append('image', file)
    const res = await axios.post('/api/admin/media/upload', formData)
    form.value.image = res.data.url
}
</script>
