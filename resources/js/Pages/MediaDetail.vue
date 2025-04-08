<template>
    <BaseLayout>
        <div class="max-w-3xl mx-auto px-4 py-6">
            <h1 class="text-3xl font-bold mb-4">{{ medio?.name }}</h1>
            <img :src="medio?.image" class="w-full h-64 object-cover rounded shadow mb-4" />

            <p class="text-gray-600">Ubicación: {{ medio?.location }}</p>
            <p class="text-gray-600">Tipo: {{ medio?.type }}</p>
            <p class="text-green-600 font-bold text-lg mb-4">
                Precio por día: ${{ medio?.price_per_day }}
            </p>

            <div class="bg-white border rounded p-4 shadow">
                <h2 class="text-lg font-semibold mb-2">Reservar este medio</h2>

                <Datepicker
                    v-model="fechas"
                    range
                    :disabled-dates="fechasOcupadas"
                    placeholder="Selecciona el rango de fechas"
                    class="mb-4"
                />

                <div v-if="total > 0" class="text-green-600 font-semibold mb-2">
                    Total a pagar: ${{ total }}
                </div>

                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" @click="reservar">
                    Confirmar Reserva
                </button>

                <div v-if="error" class="text-red-600 mt-2">{{ error }}</div>
                <div v-if="success" class="text-green-600 mt-2">{{ success }}</div>
            </div>
        </div>

        <Toast v-if="toast.visible" :type="toast.type" :title="toast.title" :message="toast.message" />
    </BaseLayout>
</template>

<script setup>
import BaseLayout from '../Layouts/BaseLayout.vue'
import { ref, onMounted, computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import Datepicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import Toast from '@/Components/Toast.vue'

const page = usePage()
const medio = ref(null)
const fechasOcupadas = ref([])
const fechas = ref([])
const total = ref(0)
const error = ref('')
const success = ref('')

const toast = ref({ visible: false, type: 'success', title: '', message: '' })

const showToast = (type, title, message) => {
    toast.value = { visible: true, type, title, message }
    setTimeout(() => (toast.value.visible = false), 3000)
}

onMounted(async () => {
    const res = await axios.get(`/api/media/${page.props.id}`)
    medio.value = res.data
    fechasOcupadas.value = res.data.availabilities.map(a => new Date(a.date))
})

const reservar = async () => {
    if (!fechas.value || fechas.value.length !== 2) return

    const [inicio, fin] = fechas.value.map(d => d.toISOString().slice(0, 10))

    try {
        const res = await axios.post('/api/reservations', {
            media_id: medio.value.id,
            start_date: inicio,
            end_date: fin,
        })
        success.value = `Reserva confirmada. Total: $${res.data.total}`
        error.value = ''
        showToast('success', 'Reserva confirmada', success.value)
    } catch (err) {
        error.value = err.response?.data?.error || 'Error al reservar'
        success.value = ''
        showToast('error', 'Error al reservar', error.value)
    }
}

const calcularTotal = computed(() => {
    if (!fechas.value || fechas.value.length !== 2) return 0
    const [inicio, fin] = fechas.value
    const dias = Math.floor((fin - inicio) / (1000 * 60 * 60 * 24)) + 1
    return dias * (medio.value?.price_per_day || 0)
})

watch(fechas, () => {
    total.value = calcularTotal.value
})
</script>

