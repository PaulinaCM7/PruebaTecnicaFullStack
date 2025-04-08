<template>
    <transition name="fade" mode="out-in">
        <div v-if="visible" :class="[
            'fixed top-5 right-5 px-4 py-3 rounded shadow-lg flex items-start gap-2 z-50',
            type === 'success' && 'bg-green-100 text-green-800',
            type === 'error' && 'bg-red-100 text-red-800',
            type === 'info' && 'bg-blue-100 text-blue-800',
            type === 'warning' && 'bg-yellow-100 text-yellow-800',
        ]">
            <span v-html="icon" class="mt-1"></span>
            <div>
                <p class="font-semibold">{{ title }}</p>
                <p class="text-sm">{{ message }}</p>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const props = defineProps({
    type: { type: String, default: 'info' },
    title: String,
    message: String,
    duration: { type: Number, default: 3000 }
})

const visible = ref(false)

const icon = computed(() => {
    switch (props.type) {
        case 'success':
            return '✅'
        case 'error':
            return '❌'
        case 'warning':
            return '⚠️'
        case 'info':
        default:
            return 'ℹ️'
    }
})

onMounted(() => {
    visible.value = true
    setTimeout(() => (visible.value = false), props.duration)
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
