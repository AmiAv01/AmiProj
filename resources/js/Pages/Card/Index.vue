<template>
    <layout>
        <!-- Всплывающее уведомление при добавлении в корзину -->
        <push v-if="isShow" :isShow="isShow" @hide="hideModal" :title="`Добавлено в корзину`">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </push>

        <section class="py-6 bg-white md:py-10 antialiased">
            <div class="w-full max-w-8xl px-6 mx-auto">
                <div class="flex flex-col lg:flex-row gap-8 items-stretch">

                    <!-- Левый блок: Основная карточка товара -->
                    <div class="border rounded-lg p-6 bg-white shadow-sm flex flex-col justify-center flex-grow lg:w-2/3">
                        <div class="flex flex-col md:flex-row gap-8 items-start">
                            <!-- Блок с изображением товара (слева) -->
                            <div class="shrink-0 w-full md:w-1/3 lg:w-1/4">
                                <img
                                    class="w-full max-h-80 object-contain"
                                    :src="imageUrl"
                                    alt="Product image"
                                />
                            </div>

                            <!-- Блок с текстовым описанием (справа) -->
                            <div class="w-full md:w-2/3 lg:w-3/4">
                                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4">
                                    {{ editTitle(detail.dt_typec) }}
                                    {{ isEmpty ? detail.dt_code : detail.dt_invoice }}
                                    {{ isEmpty ? detail.dt_firm : '' }}
                                </h1>

                                <p v-if="isEmpty" class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4">
                                    (CARGO # <span>{{ Array.from(cargoIds).join() }}</span>)
                                </p>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4" v-if="!isEmpty">
                                    <div>
                                        <p class="text-gray-600"><span class="text-gray-500">OEM:</span> <strong>{{ detail.dt_oem }}</strong></p>
                                        <p class="text-gray-600"><span class="text-gray-500">CARGO:</span> <strong>{{ detail.dt_cargo }}</strong></p>
                                        <p class="text-gray-600"><span class="text-gray-500">Бренд:</span> <strong>{{ detail.fr_code }}</strong></p>
                                        <p class="text-gray-600"><span class="text-gray-500">Комментарий:</span> <strong>{{ detail.dt_comment }}</strong></p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between border-t pt-4" v-if="!isEmpty">
                                    <div>
                                        <p class="text-gray-600">
                                            <span class="text-gray-500">Наличие:</span>
                                            <span v-if="detail.ostc" class="text-green-500 ml-2 font-semibold">{{ detail.ostc }} шт.</span>
                                            <span v-else class="text-red-500 ml-2">Нет в наличии</span>
                                        </p>
                                        <p class="text-2xl font-extrabold text-gray-900 mt-1">
                                            {{ (price !== '0' && price !== undefined && !isNaN(parseFloat(price))) ? `${parseFloat(price).toFixed(2)} BYN` : 'цену уточнять' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Динамический блок корзины -->
                                <div v-if="!isEmpty && detail.ostc && price !== '0'">
                                    <!-- Вариант 1: Товар уже в корзине (интерактивный ввод) -->
                                    <div v-if="isInCart" class="flex flex-wrap items-center gap-4 mt-4">
                                        <div class="flex items-center border border-gray-300 rounded-lg shadow-sm">
                                            <button
                                                @click="decCount"
                                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 font-bold text-lg rounded-l-lg transition-colors"
                                            >
                                                -
                                            </button>
                                            <input
                                                type="number"
                                                v-model.number="currentQty"
                                                @input="changeQuantity"
                                                @change="enforceMinimum"
                                                min="1"
                                                class="w-16 text-center border-none py-2 focus:outline-none font-semibold text-lg bg-transparent"
                                            />
                                            <button
                                                @click="incCount"
                                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 font-bold text-lg rounded-r-lg transition-colors"
                                            >
                                                +
                                            </button>
                                        </div>

                                        <inertia-link
                                            href="/cart"
                                            class="inline-flex items-center justify-center bg-green-600 hover:bg-green-500 text-white font-semibold rounded-lg px-6 py-2.5 transition-colors gap-2"
                                        >
                                            <span>В корзине</span>
                                            <span class="text-sm font-normal opacity-90">(Перейти)</span>
                                        </inertia-link>
                                    </div>

                                    <!-- Вариант 2: Товара еще нет в корзине (кнопка добавления) -->
                                    <cart-button
                                        v-else
                                        @addInCart="addInCart"
                                        class="mt-4 bg-green-600 hover:bg-green-500 text-white font-medium rounded-md px-5 py-2.5 w-full sm:w-auto transition-colors"
                                    >
                                        Добавить в корзину
                                    </cart-button>
                                </div>
                            </div>
                        </div>

                        <!-- Блок деталировки (для всех пользователей) -->
                        <div class="mt-8 border-t pt-6" v-if="sameDetails && sameDetails.length">
                            <DetailLayout :details="sameDetails" @itemAddedToCart="isShow = true" />
                        </div>
                    </div>

                    <!-- Правый блок: Найденные аналоги -->
                    <div class="bg-white lg:w-1/3 min-w-[450px] max-h-[580px] p-6 rounded-lg border shadow-sm flex flex-col">
                        <h2 class="text-lg font-bold mb-5 text-center shrink-0">Найденные аналоги</h2>
                        <div class="overflow-y-auto flex-grow pr-1">
                            <Analogs :details="analogs" />
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Кастомное модальное окно подтверждения удаления -->
        <teleport to="body">
            <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4 shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">
                        Вы действительно хотите удалить этот товар из корзины?
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
    </layout>
</template>

<script setup>
import axios from "axios";
import { editDetailTitle } from "@/Services/TitleService";
import Analogs from "@/Pages/Card/Analogs.vue";
import { ref, computed, watch } from "vue";
import { useCartStore } from "@/Store/cartStore.js";
import DetailLayout from "./DetailLayout.vue";
import CartButton from '@/Components/CartButton.vue';
import Layout from "@/Shared/UserLayout.vue";

const props = defineProps({
    sameDetails: {
        type: Array,
        default: () => []
    },
    detail: {
        type: Object,
        default: () => ({})
    },
    analogs: {
        type: Array,
        default: () => []
    },
    cargoIds: {
        type: Array,
        default: () => []
    },
    isEmpty: {
        type: Boolean,
        default: false
    },
    price: {
        type: String,
        default: '0'
    },
    imageUrl: {
        type: String,
        default: ''
    }
});

const store = useCartStore();
const isShow = ref(false);
const currentQty = ref(1);
const showDeleteModal = ref(false);

// Вычисляем, добавлен ли этот товар в корзину
const cartItem = computed(() => {
    return store.cartData ? Object.values(store.cartData).find(item => item.dt_id === props.detail.dt_id) : null;
});

const isInCart = computed(() => !!cartItem.value);

// Отслеживаем изменение количества (реактивно по значению примитива)
watch(() => cartItem.value?.quantity, (newQty) => {
    if (newQty !== undefined) {
        currentQty.value = newQty;
    }
}, { immediate: true });

const addInCart = () => {
    axios
        .post("/cart", {
            id: props.detail.dt_id,
            quantity: 1,
        })
        .then((res) => {
            isShow.value = true;
            if (res.data && res.data.newCartCount !== undefined) {
                store.setCartCount(res.data.newCartCount);
            } else {
                store.incCartCount();
            }
            if (res.data && res.data.items) {
                store.setDetails(res.data.items);
            }
        })
        .catch((err) => console.log(err));
};

const incCount = () => {
    currentQty.value++;
    store.changeDetailQuantity(props.detail.dt_id, currentQty.value);
};

const decCount = () => {
    if (currentQty.value > 1) {
        currentQty.value--;
        store.changeDetailQuantity(props.detail.dt_id, currentQty.value);
    } else {
        confirmDelete();
    }
};

const changeQuantity = () => {
    if (currentQty.value === '' || currentQty.value === null || currentQty.value === undefined) {
        return;
    }
    if (currentQty.value < 1) {
        confirmDelete();
        return;
    }
    store.changeDetailQuantity(props.detail.dt_id, currentQty.value);
};

const enforceMinimum = () => {
    if (currentQty.value === '' || currentQty.value === null || currentQty.value === undefined || currentQty.value < 1) {
        currentQty.value = 1;
        store.changeDetailQuantity(props.detail.dt_id, 1);
    }
};

const confirmDelete = () => {
    showDeleteModal.value = true;
};

const proceedDelete = () => {
    showDeleteModal.value = false;
    store.deleteDetailFromCart(props.detail.dt_id);
};

const cancelDelete = () => {
    showDeleteModal.value = false;
    currentQty.value = 1;
    store.changeDetailQuantity(props.detail.dt_id, 1);
};

const editTitle = (res) => editDetailTitle(res);

function hideModal(param) {
    isShow.value = param;
}
</script>