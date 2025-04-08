<template>
    <BaseLayout>
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Usuarios</h1>
                <button @click="abrirFormulario()" class="bg-indigo-600 text-white px-4 py-2 rounded">+ Crear
                    Usuario</button>
            </div>

            <div class="overflow-x-auto bg-white rounded shadow">
                <table class="min-w-full table-auto text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="u in usuarios" :key="u.id" class="border-b">
                            <td class="p-3">{{ u.name }}</td>
                            <td>{{ u.email }}</td>
                            <td>{{ u.role }}</td>
                            <td class="text-center space-x-2">
                                <button class="text-blue-600 hover:underline"
                                    @click="abrirFormulario(u)">Editar</button>
                                <button class="text-red-600 hover:underline" @click="eliminar(u.id)">Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="mostrarFormulario" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                <div class="bg-white rounded shadow p-6 w-full max-w-md">
                    <h2 class="text-xl font-semibold mb-4">{{ form.id ? 'Editar' : 'Nuevo' }} Usuario</h2>
                    <div class="space-y-3 mb-4">
                        <input v-model="form.name" placeholder="Nombre" class="w-full p-2 border rounded" />
                        <input v-model="form.email" placeholder="Email" class="w-full p-2 border rounded" />
                        <select v-model="form.role" class="w-full p-2 border rounded">
                            <option value="cliente">Cliente</option>
                            <option value="admin">Admin</option>
                        </select>
                        <input v-if="!form.id" type="password" v-model="form.password" placeholder="Contraseña"
                            class="w-full p-2 border rounded" />
                    </div>
                    <div class="flex justify-between">
                        <button class="bg-green-600 text-white px-4 py-2 rounded" @click="guardar">Guardar</button>
                        <button @click="cerrarFormulario" class="text-gray-600 hover:underline">Cancelar</button>
                    </div>
                </div>
            </div>

            <Toast v-if="toast.visible" :type="toast.type" :title="toast.title" :message="toast.message" />
        </div>
    </BaseLayout>
</template>

<script setup>
import BaseLayout from '@/Layouts/BaseLayout.vue'
import Toast from '@/Components/Toast.vue'
import { ref, onMounted } from 'vue'
import axios from 'axios'

const usuarios = ref([])
const mostrarFormulario = ref(false)
const form = ref({ name: '', email: '', password: '', role: 'cliente' })
const toast = ref({ visible: false, type: 'success', title: '', message: '' })

const mostrarToast = (type, title, message) => {
    toast.value = { visible: false, type, title, message }
    setTimeout(() => (toast.value.visible = true), 0)
}

const cargarUsuarios = async () => {
    const res = await axios.get('/api/admin/usuarios')
    usuarios.value = res.data
}

const abrirFormulario = (usuario = null) => {
    form.value = usuario ? { ...usuario, password: '' } : { name: '', email: '', password: '', role: 'cliente' }
    mostrarFormulario.value = true
}

const cerrarFormulario = () => {
    form.value = { name: '', email: '', password: '', role: 'cliente' }
    mostrarFormulario.value = false
}

const guardar = async () => {
    try {
        const payload = { ...form.value }
        if (payload.id && !payload.password) delete payload.password

        if (payload.id) {
            await axios.put(`/api/admin/usuarios/${payload.id}`, payload)
            mostrarToast('success', 'Usuario actualizado', `El usuario ${payload.name} fue actualizado.`)
        } else {
            await axios.post('/api/admin/usuarios', payload)
            mostrarToast('success', 'Usuario creado', `El usuario ${payload.name} fue creado.`)
        }

        await cargarUsuarios()
        cerrarFormulario()
    } catch (err) {
        const errors = err.response?.data?.errors
        const mensaje = errors ? Object.values(errors).flat().join('\n') : 'Ocurrió un error al guardar el usuario.'
        mostrarToast('error', 'Error', mensaje)
    }
}

const eliminar = async (id) => {
    if (confirm('¿Eliminar este usuario?')) {
        await axios.delete(`/api/admin/usuarios/${id}`)
        await cargarUsuarios()
        mostrarToast('success', 'Usuario eliminado', 'El usuario fue eliminado correctamente.')
    }
}

onMounted(cargarUsuarios)
</script>
