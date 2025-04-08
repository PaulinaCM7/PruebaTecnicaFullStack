<template>
    <BaseLayout>
        <Toast v-if="toast.show" :type="toast.type" :title="toast.title" :message="toast.message" />

        <h1 class="text-2xl font-bold mb-4">Reservas</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <input v-model="filtros.cliente" placeholder="Cliente" class="input" />
            <input v-model="filtros.medio" placeholder="Medio" class="input" />
            <Datepicker
                v-model="filtros.rango"
                range
                placeholder="Rango de fechas"
                :format="'yyyy-MM-dd'"
                class="input"
            />
        </div>

        <div class="mb-4">
            <button @click="buscar" class="bg-blue-600 text-white px-4 py-2 rounded">Buscar</button>
            <button @click="reset" class="ml-2 text-sm text-gray-500 hover:underline">Limpiar</button>
        </div>

        <div v-if="loading">Cargando reservas...</div>

        <table v-else class="w-full text-sm border mb-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Cliente</th>
                    <th>Medio</th>
                    <th>Fechas</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="r in reservas" :key="r.id" class="border-t">
                    <td class="p-2">
                        <Link :href="route('admin.perfil', r.user?.id)" class="text-blue-600 hover:underline">
                            {{ r.user?.name }}
                        </Link>
                    </td>
                    <td>
                        <Link :href="`/medios/${r.media?.id}`" class="text-blue-600 hover:underline">
                            {{ r.media?.name }}
                        </Link>
                    </td>
                    <td>{{ r.start_date }} → {{ r.end_date }}</td>
                    <td>${{ r.total }}</td>
                </tr>
            </tbody>
        </table>

        <div v-if="paginacion.total > paginacion.per_page" class="flex gap-2">
            <button
                v-for="n in paginacion.last_page"
                :key="n"
                @click="cambiarPagina(n)"
                :class="[
                    'px-3 py-1 border rounded',
                    paginacion.current_page === n ? 'bg-blue-600 text-white' : 'bg-white'
                ]"
            >
                {{ n }}
            </button>
        </div>
    </BaseLayout>
</template>

<script setup>
import BaseLayout from '../../Layouts/BaseLayout.vue'
import { Link } from '@inertiajs/vue3'
import axios from 'axios'
import { ref, watch } from 'vue'
import Toast from '@/Components/Toast.vue'
import Datepicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const reservas = ref([])
const paginacion = ref({})
const paginaActual = ref(1)
const loading = ref(false)

const filtros = ref({
    cliente: '',
    medio: '',
    rango: null, // [start, end]
})

const toast = ref({
    show: false,
    type: 'info',
    title: '',
    message: ''
})

const showToast = (type, title, message) => {
    toast.value = { show: true, type, title, message }
    setTimeout(() => (toast.value.show = false), 3000)
}

const buscar = async () => {
    loading.value = true

    const params = {
        cliente: filtros.value.cliente,
        medio: filtros.value.medio,
        page: paginaActual.value
    }

    if (filtros.value.rango && filtros.value.rango.length === 2) {
        params.fecha_inicio = filtros.value.rango[0].toISOString().slice(0, 10)
        params.fecha_fin = filtros.value.rango[1].toISOString().slice(0, 10)
    }

    try {
        const res = await axios.get('/api/admin/reservations', { params })
        reservas.value = res.data.data
        paginacion.value = res.data
        showToast('success', 'Búsqueda completada', 'Las reservas se han cargado correctamente.')
    } catch (e) {
        showToast('error', 'Error', 'Hubo un problema al cargar las reservas.')
    }

    loading.value = false
}

const cambiarPagina = (pagina) => {
    paginaActual.value = pagina
    buscar()
}

const reset = () => {
    filtros.value = { cliente: '', medio: '', rango: null }
    paginaActual.value = 1
    buscar()
}

buscar()
</script>
