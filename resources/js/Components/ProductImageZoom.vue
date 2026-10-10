<script setup>
import { nextTick, onBeforeUnmount, ref, watch } from 'vue';

const props = defineProps({
    src: {
        type: String,
        required: true,
    },
    alt: {
        type: String,
        default: 'Изображение товара',
    },
});

const isOpen = ref(false);
const trigger = ref(null);
const closeButton = ref(null);
let previousBodyOverflow = '';

const open = async () => {
    isOpen.value = true;
    await nextTick();
    closeButton.value?.focus();
};

const close = async () => {
    isOpen.value = false;
    await nextTick();
    trigger.value?.focus();
};

const handleKeydown = (event) => {
    if (event.key === 'Escape' && isOpen.value) {
        close();
    }
};

watch(isOpen, (open) => {
    if (open) {
        previousBodyOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        document.addEventListener('keydown', handleKeydown);
        return;
    }

    document.body.style.overflow = previousBodyOverflow;
    document.removeEventListener('keydown', handleKeydown);
});

onBeforeUnmount(() => {
    document.removeEventListener('keydown', handleKeydown);
    if (isOpen.value) {
        document.body.style.overflow = previousBodyOverflow;
    }
});
</script>

<template>
    <button
        ref="trigger"
        type="button"
        class="group relative block aspect-square w-full overflow-hidden rounded-xl border border-slate-100 bg-slate-50 p-4 text-left transition hover:border-blue-200 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 sm:p-6"
        aria-haspopup="dialog"
        aria-label="Увеличить изображение товара"
        @click="open"
    >
        <img
            class="h-full w-full object-contain mix-blend-multiply transition-transform duration-200 group-hover:scale-[1.03]"
            :src="props.src"
            :alt="props.alt"
        />

        <span
            class="absolute bottom-3 right-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/95 text-slate-700 opacity-90 shadow-md ring-1 ring-slate-200 transition group-hover:bg-blue-600 group-hover:text-white group-focus-visible:bg-blue-600 group-focus-visible:text-white"
            aria-hidden="true"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="11" cy="11" r="7" stroke-width="2" />
                <path d="m20 20-4-4M11 8v6M8 11h6" stroke-linecap="round" stroke-width="2" />
            </svg>
        </span>
    </button>

    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isOpen"
                class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-950/90 p-4 backdrop-blur-sm sm:p-8"
                role="dialog"
                aria-modal="true"
                aria-label="Увеличенное изображение товара"
                @mousedown.self="close"
            >
                <button
                    ref="closeButton"
                    type="button"
                    class="absolute right-4 top-4 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-white sm:right-6 sm:top-6"
                    aria-label="Закрыть увеличенное изображение"
                    @click="close"
                >
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path d="m6 6 12 12M18 6 6 18" stroke-linecap="round" stroke-width="2" />
                    </svg>
                </button>

                <img
                    class="max-h-[calc(100dvh-5rem)] max-w-full select-none object-contain"
                    :src="props.src"
                    :alt="props.alt"
                    @mousedown.stop
                />
            </div>
        </Transition>
    </Teleport>
</template>
