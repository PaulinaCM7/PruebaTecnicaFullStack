<template>
    <BaseLayout>
        <div class="max-w-6xl mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-6">Panel de Administración</h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <Link :href="route('admin.media')"
                    class="bg-indigo-600 text-white p-6 rounded-lg shadow hover:bg-indigo-700 transition text-center">
                📸 Gestionar Medios
                </Link>

                <Link :href="route('admin.reservas')"
                    class="bg-green-600 text-white p-6 rounded-lg shadow hover:bg-green-700 transition text-center">
                    📅 Ver Reservas
                </Link>

                <Link :href="route('admin.usuarios')"
                    class="bg-yellow-600 text-white p-6 rounded-lg shadow hover:bg-yellow-700 transition text-center">
                    👤 Gestionar Usuarios
                </Link>
            </div>
        </div>
    </BaseLayout>
</template>

<script setup>
import BaseLayout from '../../Layouts/BaseLayout.vue'
import { Link } from '@inertiajs/vue3'
</script>
