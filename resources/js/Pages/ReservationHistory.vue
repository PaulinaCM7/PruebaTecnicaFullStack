<template>
    <BaseLayout>
        <h1 class="text-2xl font-bold mb-4">Mis Reservas</h1>

        <div v-if="cargando">Cargando...</div>
        <div v-else>
            <div v-for="r in reservas" :key="r.id" class="border p-4 rounded mb-3">
                <h2 class="text-lg font-semibold">{{ r.media.name }}</h2>
                <p>📅 Del {{ r.start_date }} al {{ r.end_date }}</p>
                <p>💰 Total: ${{ r.total }}</p>
            </div>
        </div>
    </BaseLayout>
</template>

<script setup>
import BaseLayout from '../Layouts/BaseLayout.vue'
import axios from 'axios'
import { ref, onMounted } from 'vue'

const reservas = ref([])
const cargando = ref(true)

onMounted(async () => {
    const res = await axios.get('/api/reservations/history')
    reservas.value = res.data
    cargando.value = false
})
</script>
