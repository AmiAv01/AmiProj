<script setup>
import { computed, onMounted, onUnmounted, watch } from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: "2xl",
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    title: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["close", "closeModal"]);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = null;
        }
    }
);

const close = () => {
    if (!props.closeable) return;
    emit("close");
    emit("closeModal");
};

const closeOnEscape = (e) => {
    if (e.key === "Escape" && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener("keydown", closeOnEscape));

onUnmounted(() => {
    document.removeEventListener("keydown", closeOnEscape);
    document.body.style.overflow = null;
});

const maxWidthClass = computed(() => {
    return {
        sm: "sm:max-w-sm",
        md: "sm:max-w-md",
        lg: "sm:max-w-lg",
        xl: "sm:max-w-xl",
        "2xl": "sm:max-w-2xl",
    }[props.maxWidth];
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-show="show"
                class="fixed inset-0 z-[70] overflow-y-auto bg-slate-950/55 px-4 py-6 backdrop-blur-[2px] sm:py-10"
                scroll-region
                @mousedown.self="close"
            >
                <Transition
                    enter-active-class="duration-200 ease-out"
                    enter-from-class="translate-y-4 scale-[0.98] opacity-0"
                    enter-to-class="translate-y-0 scale-100 opacity-100"
                    leave-active-class="duration-150 ease-in"
                    leave-from-class="translate-y-0 scale-100 opacity-100"
                    leave-to-class="translate-y-4 scale-[0.98] opacity-0"
                >
                    <div
                        v-show="show"
                        class="mx-auto flex min-h-full items-center justify-center"
                        @mousedown.self="close"
                    >
                        <div
                            class="w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
                            :class="maxWidthClass"
                            role="dialog"
                            aria-modal="true"
                            aria-labelledby="modal-title"
                        >
                            <div
                                class="flex items-center justify-between gap-4 border-b border-slate-200 px-5 py-5 sm:px-6"
                            >
                                <h2 id="modal-title" class="text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                                    {{ title }}
                                </h2>
                                <button
                                    type="button"
                                    @click="close"
                                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-600"
                                    aria-label="Закрыть"
                                >
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M4.47 4.47a.75.75 0 0 1 1.06 0L10 8.94l4.47-4.47a.75.75 0 1 1 1.06 1.06L11.06 10l4.47 4.47a.75.75 0 1 1-1.06 1.06L10 11.06l-4.47 4.47a.75.75 0 0 1-1.06-1.06L8.94 10 4.47 5.53a.75.75 0 0 1 0-1.06Z" /></svg>
                                </button>
                            </div>

                            <div
                                v-show="show"
                                class="bg-white"
                            >
                                <slot v-if="show" />
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
