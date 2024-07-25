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

const emit = defineEmits(["close"]);

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
    /*if (props.closeable) {
        emit("close");
    }*/
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
        <Transition leave-active-class="duration-200 ">
            <div
                v-show="show"
                class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
                scroll-region
            >
                <Transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-show="show"
                        class="fixed inset-0 transform transition-all"
                    >
                        <div
                            class="mx-auto mt-10 inset-0 w-[600px] bg-gray-200 rounded-lg"
                        >
                            <div
                                class="flex items-center px-4 justify-between bg-green-500"
                            >
                                <p
                                    class="text-2xl p-4 rounded-tr-[15px] rounded-tl-[15px] text-white"
                                >
                                    {{ title }}
                                </p>
                                <p
                                    @click="close"
                                    class="text-white text-5xl cursor-pointer hover:text-red-400"
                                >
                                    &times;
                                </p>
                            </div>

                            <div
                                v-show="show"
                                class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto"
                                :class="maxWidthClass"
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
