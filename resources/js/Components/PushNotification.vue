<template>
    <teleport to="body">
        <div
            v-if="isShow"
            :class="[
                'fixed right-4 top-4 z-[100] flex w-[calc(100%-2rem)] max-w-md items-center gap-3 rounded-xl p-4 text-white shadow-xl sm:right-6 sm:top-6',
                bgColor || (type === 'error' ? 'bg-red-600' : 'bg-green-600'),
            ]"
            role="alert"
            aria-live="assertive"
        >
            <svg v-if="type === 'error'" class="h-7 w-7 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="12" cy="12" r="10" />
                <path d="m15 9-6 6m0-6 6 6" />
            </svg>
            <svg v-else class="h-7 w-7 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            <p class="flex-1 text-base font-semibold sm:text-lg">{{ title }}</p>
            <button type="button" class="rounded p-1 hover:bg-black/10" aria-label="Закрыть уведомление" @click="hide">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    </teleport>
</template>

<script setup>
import { onBeforeUnmount, onMounted } from 'vue';

defineProps({
    title: {
        type: String,
        default: ''
    },
    isShow: {
        type: Boolean,
        default: false
    },
    bgColor: {
        type: String,
        default: ''
    },
    type: {
        type: String,
        default: 'success',
        validator: (value) => ['success', 'error'].includes(value),
    }
});

const emit = defineEmits(['hide']);
let timer;

const hide = () => {
    emit('hide', false);
};

onMounted(() => {
    timer = window.setTimeout(hide, 3000);
});

onBeforeUnmount(() => window.clearTimeout(timer));
</script>



