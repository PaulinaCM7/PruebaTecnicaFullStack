<template>
    <BaseLayout>
        <div v-if="usuario">
            <h1 class="text-2xl font-bold mb-4">Perfil de {{ usuario.name }}</h1>

            <div class="bg-white p-4 rounded shadow mb-6">
                <p><strong>Email:</strong> {{ usuario.email }}</p>
                <p><strong>Rol:</strong> {{ usuario.role }}</p>
                <p><strong>Total de reservas:</strong> {{ usuario.reservations.length }}</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-semibold mb-4">Reservas</h2>
                <div v-if="usuario.reservations.length === 0" class="text-gray-500">Sin reservas.</div>
                <table v-else class="w-full text-sm border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 text-left">Medio</th>
                            <th>Fechas</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="r in usuario.reservations" :key="r.id" class="border-t">
                            <td class="p-2">
                                <Link :href="`/medios/${r.media?.id}`" class="text-blue-600 hover:underline">
                                    {{ r.media?.name }}
                                </Link>
                            </td>
                            <td>{{ r.start_date }} → {{ r.end_date }}</td>
                            <td>${{ r.total }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-else class="text-gray-500">Cargando perfil...</div>
    </BaseLayout>
</template>


<script setup>
import BaseLayout from '../../Layouts/BaseLayout.vue'
import axios from 'axios'
import { onMounted, ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps(['id'])
const usuario = ref(null)

onMounted(async () => {
    const res = await axios.get(`/api/admin/perfil/${props.id}`)
    usuario.value = res.data
})

</script>
