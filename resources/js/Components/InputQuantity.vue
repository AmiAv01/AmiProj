<template>
    <div
        class="flex items-center max-[500px]:justify-center h-full max-md:mt-3"
    >
        <div class="flex items-center h-full">
            <button
                @click="decCount"
                class="group rounded-l-xl px-5 py-[12px] border border-gray-200 flex items-center justify-center shadow-sm shadow-transparent transition-all duration-500 hover:bg-gray-50 hover:border-gray-300 hover:shadow-gray-300 focus-within:outline-gray-300"
            >
                <svg
                    class="stroke-gray-900 transition-all duration-500 group-hover:stroke-black"
                    xmlns="http://www.w3.org/2000/svg"
                    width="22"
                    height="22"
                    viewBox="0 0 22 22"
                    fill="none"
                >
                    <path
                        d="M16.5 11H5.5"
                        stroke=""
                        stroke-width="1.6"
                        stroke-linecap="round"
                    />
                    <path
                        d="M16.5 11H5.5"
                        stroke=""
                        stroke-opacity="0.2"
                        stroke-width="1.6"
                        stroke-linecap="round"
                    />
                    <path
                        d="M16.5 11H5.5"
                        stroke=""
                        stroke-opacity="0.2"
                        stroke-width="1.6"
                        stroke-linecap="round"
                    />
                </svg>
            </button>
            <input
                type="number"
                v-model.number="count"
                min="1"
                max="999"
                @input="changeQuantity"
                @change="enforceMinimum"
                class="border-y border-gray-200 outline-none text-gray-900 font-semibold text-lg w-full max-w-[73px] min-w-[60px] placeholder:text-gray-900 py-[9px] text-center bg-transparent"
            />
            <button
                @click="incCount"
                class="group rounded-r-xl px-5 py-[12px] border border-gray-200 flex items-center justify-center shadow-sm shadow-transparent transition-all duration-500 hover:bg-gray-50 hover:border-gray-300 hover:shadow-gray-300 focus-within:outline-gray-300"
            >
                <svg
                    class="stroke-gray-900 transition-all duration-500 group-hover:stroke-black"
                    xmlns="http://www.w3.org/2000/svg"
                    width="22"
                    height="22"
                    viewBox="0 0 22 22"
                    fill="none"
                >
                    <path
                        d="M11 5.5V16.5M16.5 11H5.5"
                        stroke=""
                        stroke-width="1.6"
                        stroke-linecap="round"
                    />
                    <path
                        d="M11 5.5V16.5M16.5 11H5.5"
                        stroke=""
                        stroke-opacity="0.2"
                        stroke-width="1.6"
                        stroke-linecap="round"
                    />
                    <path
                        d="M11 5.5V16.5M16.5 11H5.5"
                        stroke=""
                        stroke-opacity="0.2"
                        stroke-width="1.6"
                        stroke-linecap="round"
                    />
                </svg>
            </button>
        </div>

        <!-- Кастомное модальное окно подтверждения удаления -->
        <teleport to="body">
            <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4 shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">
                        Вы желаете удалить этот товар из корзины?
                    </h3>
                    <div class="flex justify-center gap-4">
                        <button
                            @click="proceedDelete"
                            class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-lg transition-colors"
                        >
                            Да
                        </button>
                        <button
                            @click="cancelDelete"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-6 rounded-lg transition-colors"
                        >
                            Нет
                        </button>
                    </div>
                </div>
            </div>
        </teleport>
    </div>
</template>

<script setup>
import { useCartStore} from "@/Store/cartStore";
import { onUnmounted, ref, watch } from "vue";
import debounce from "lodash.debounce";

const store = useCartStore();
const props = defineProps({
    quantity: {
        type: Number,
        default: 1,
    },
    detailId: {
        type: Number,
        required: true,
    }});
const count = ref(props.quantity);
const showDeleteModal = ref(false);
const saveQuantity = debounce((quantity) => {
    store.changeDetailQuantity(props.detailId, quantity);
}, 300);

watch(() => props.quantity, (newVal) => {
    count.value = newVal;
});

function incCount() {
    count.value++;
    store.changeDetailQuantity(props.detailId, count.value);
}

function decCount() {
    if (count.value > 1) {
        count.value--;
        store.changeDetailQuantity(props.detailId, count.value);
    } else {
        confirmDelete();
    }
}

function changeQuantity() {
    if (count.value === '' || count.value === null || count.value === undefined) {
        return;
    }
    if (count.value < 1) {
        confirmDelete();
        return;
    }
    saveQuantity(count.value);
}

function enforceMinimum() {
    if (count.value === '' || count.value === null || count.value === undefined || count.value < 1) {
        count.value = 1;
        store.changeDetailQuantity(props.detailId, 1);
    }
}

function confirmDelete() {
    showDeleteModal.value = true;
}

function proceedDelete() {
    showDeleteModal.value = false;
    saveQuantity.cancel();
    store.deleteDetailFromCart(props.detailId, false);
}

function cancelDelete() {
    showDeleteModal.value = false;
    count.value = 1;
    store.changeDetailQuantity(props.detailId, 1);
}

onUnmounted(() => saveQuantity.cancel());
</script>
