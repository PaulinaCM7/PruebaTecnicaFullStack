<template>
    <BaseLayout>
        <div class="max-w-7xl mx-auto px-4 py-6">
            <h1 class="text-3xl font-bold mb-6">Explora nuestros medios</h1>

            <input type="text" v-model="busqueda" placeholder="Buscar por ubicación..."
                class="w-full mb-6 p-2 border border-gray-300 rounded shadow-sm" />

            <select v-model="tipo"
                class="mb-6 w-full p-2 pr-8 border border-gray-300 rounded shadow-sm appearance-none bg-white">
                <option value="">Todos los tipos</option>
                <option v-for="t in tipos" :key="t" :value="t">{{ t }}</option>
            </select>

            <div v-if="loading" class="text-gray-500">Cargando medios...</div>

            <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div v-for="medio in medios" :key="medio.id"
                    class="border rounded shadow hover:shadow-lg transition p-4 bg-white">
                    <img v-if="medio.image" :src="medio.image" class="w-full h-48 object-cover rounded mb-4" />
                    <h2 class="text-xl font-semibold">{{ medio.name }}</h2>
                    <p class="text-sm text-gray-600">{{ medio.location }} - {{ medio.type }}</p>
                    <p class="text-green-600 font-bold mt-2">${{ medio.price_per_day }}/día</p>
                    <Link :href="`/medios/${medio.id}`" class="inline-block mt-4 text-blue-600 hover:underline">
                    Ver detalles
                    </Link>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { Link, usePage } from '@inertiajs/vue3'
import BaseLayout from '../Layouts/BaseLayout.vue'

const medios = ref([])
const busqueda = ref('')
const loading = ref(true)
const user = usePage().props.auth.user
const tipos = ref([])
const tipo = ref('')

const obtenerTipos = async () => {
    const res = await axios.get('/api/media-tipos')
    tipos.value = res.data
}

const cargarMedios = async () => {
    loading.value = true
    const params = {
        location: busqueda.value
    }

    if (tipo.value) {
        params.type = tipo.value
    }

    const res = await axios.get('/api/media', { params })
    medios.value = res.data.data || []
    loading.value = false
}

watch([busqueda, tipo], cargarMedios)
onMounted(() => {
    cargarMedios()
    obtenerTipos()
})

</script>
