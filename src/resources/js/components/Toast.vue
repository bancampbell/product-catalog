<template>
    <Teleport to="body">
        <div v-if="visible" class="fixed bottom-4 right-4 z-50 animate-slide-up">
            <div :class="[
                'px-6 py-3 rounded-xl shadow-lg flex items-center gap-3',
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            ]">
                <svg v-if="type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                {{ message }}
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    show: Boolean,
    message: String,
    type: String,
    duration: { type: Number, default: 3000 }
});

const emit = defineEmits(['close']);
const visible = ref(false);

watch(() => props.show, (val) => {
    if (val) {
        visible.value = true;
        setTimeout(() => {
            visible.value = false;
            emit('close');
        }, props.duration);
    }
});
</script>

<style scoped>
@keyframes slide-up {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
.animate-slide-up {
    animation: slide-up 0.3s ease-out;
}
</style>
