<template>
    <div class="min-h-screen bg-gray-100 text-gray-900">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold flex items-center gap-2 text-indigo-700">
                    <span class="text-3xl">🍊</span>
                    <Link href="/" class="hover:underline">Naranti</Link>
                </h1>

                <div class="flex items-center gap-6">
                    <Link v-if="user?.role === 'cliente'" href="/reservas"
                        class="text-sm text-gray-700 hover:text-indigo-600 hover:underline">
                    Mis Reservas
                    </Link>

                    <span v-if="user" class="text-sm text-gray-600">Hola, {{ user.name }}</span>

                    <form @submit.prevent="logout">
                        <button class="text-sm text-red-600 hover:underline">Salir</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="py-10 px-4">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { usePage, router, Link } from '@inertiajs/vue3'

const user = usePage().props.auth.user

const logout = () => {
    router.post('/logout')
}
</script>
