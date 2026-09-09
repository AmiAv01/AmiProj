<template>
    <layout>
        <!-- Всплывающее уведомление при добавлении в корзину -->
        <push v-if="notification.show" :isShow="notification.show" :type="notification.type" :title="notification.message" @hide="hideNotification" />

        <section class="bg-slate-50/70 py-6 antialiased md:py-10">
            <div class="mx-auto w-full max-w-[1600px] px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-stretch gap-6 lg:flex-row">

                    <!-- Левый блок: Основная карточка товара -->
                    <div class="flex flex-grow flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm lg:w-2/3">
                        <div class="flex flex-col items-start gap-7 p-5 sm:p-7 md:flex-row lg:p-8">
                            <!-- Блок с изображением товара (слева) -->
                            <div class="w-full shrink-0 md:w-1/3 lg:w-1/4">
                                <div class="aspect-square overflow-hidden rounded-xl border border-slate-100 bg-slate-50 p-4 sm:p-6">
                                    <img
                                        class="h-full w-full object-contain mix-blend-multiply"
                                        :src="imageUrl"
                                        alt="Изображение товара"
                                    />
                                </div>
                            </div>

                            <!-- Блок с текстовым описанием (справа) -->
                            <div class="w-full min-w-0 md:w-2/3 lg:w-3/4">
                                <div class="mb-6">
                                    <span class="mb-2 inline-flex rounded-full bg-blue-50 px-3 py-1 text-sm font-semibold uppercase tracking-wide text-blue-700">
                                        Карточка товара
                                    </span>
                                    <h1 class="max-w-3xl text-2xl font-bold leading-snug text-slate-900 sm:text-3xl">
                                        {{ editTitle(detail.dt_typec) }}
                                        {{ isEmpty ? detail.dt_code : detail.dt_invoice }}
                                        {{ isEmpty ? detail.dt_firm : '' }}
                                    </h1>
                                </div>

                                <div v-if="isEmpty" class="inline-flex flex-wrap items-center gap-x-3 gap-y-1 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                                    <span class="text-sm font-semibold uppercase tracking-wide text-slate-500">Номер CARGO</span>
                                    <strong class="font-mono text-xl text-slate-900">{{ Array.from(cargoIds).join(', ') || '—' }}</strong>
                                </div>

                                <div class="mb-4 grid grid-cols-1 gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-lg sm:grid-cols-2" v-if="!isEmpty">
                                    <div>
                                        <p class="text-gray-600"><span class="text-gray-500">OEM:</span> <strong>{{ detail.dt_oem }}</strong></p>
                                        <p class="text-gray-600"><span class="text-gray-500">CARGO:</span> <strong>{{ detail.dt_cargo }}</strong></p>
                                        <p class="text-gray-600"><span class="text-gray-500">Бренд:</span> <strong>{{ detail.fr_code }}</strong></p>
                                        <p class="text-gray-600"><span class="text-gray-500">Комментарий:</span> <strong>{{ detail.dt_comment }}</strong></p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between border-t border-slate-200 pt-4 text-lg" v-if="!isEmpty">
                                    <div>
                                        <p class="text-gray-600">
                                            <span class="text-gray-500">Наличие:</span>
                                            <span v-if="detail.ostc" class="text-green-500 ml-2 font-semibold">{{ detail.ostc }} шт.</span>
                                            <span v-else class="text-red-500 ml-2">Нет в наличии</span>
                                        </p>
                                        <p class="text-2xl font-extrabold text-gray-900 mt-1">
                                            {{ (price !== '0' && price !== undefined && !isNaN(parseFloat(price))) ? formatMoney(price) : 'цену уточнять' }}
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
                                                :disabled="currentQty <= CART_QUANTITY_MIN"
                                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 font-bold text-lg rounded-l-lg transition-colors disabled:cursor-not-allowed disabled:opacity-40"
                                                :title="minimumQuantityTitle"
                                            >
                                                -
                                            </button>
                                            <input
                                                type="number"
                                                v-model.number="currentQty"
                                                @input="changeQuantity"
                                                @change="enforceMinimum"
                                                :min="CART_QUANTITY_MIN"
                                                :max="CART_QUANTITY_MAX"
                                                class="w-16 text-center border-none py-2 focus:outline-none font-semibold text-lg bg-transparent"
                                            />
                                            <button
                                                @click="incCount"
                                                :disabled="currentQty >= CART_QUANTITY_MAX"
                                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 font-bold text-lg rounded-r-lg transition-colors disabled:cursor-not-allowed disabled:opacity-40"
                                            >
                                                +
                                            </button>
                                        </div>

                                        <button
                                            @click="confirmDelete"
                                            class="inline-flex h-11 w-11 items-center justify-center rounded-lg border border-red-200 text-red-600 transition-colors hover:bg-red-50"
                                            title="Удалить товар из корзины"
                                            aria-label="Удалить товар из корзины"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                            </svg>
                                        </button>

                                        <spa-link
                                            href="/cart"
                                            class="inline-flex items-center justify-center bg-green-600 hover:bg-green-500 text-white font-semibold rounded-lg px-6 py-2.5 transition-colors gap-2"
                                        >
                                            <span>В корзине</span>
                                            <span class="text-sm font-normal opacity-90">(Перейти)</span>
                                        </spa-link>
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
                        <div class="border-t border-slate-200 bg-slate-50/60 p-4 sm:p-5" v-if="sameDetails && sameDetails.length">
                            <DetailLayout :details="sameDetails" @cart-notification="showCartNotification" />
                        </div>
                    </div>

                    <!-- Правый блок: Найденные аналоги -->
                    <div class="flex max-h-[580px] flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm lg:w-1/3 lg:min-w-[390px]">
                        <div class="flex shrink-0 items-center justify-between border-b border-slate-200 bg-slate-50 px-5 py-4">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Альтернативные варианты</p>
                                <h2 class="mt-1 text-xl font-bold text-slate-900">Найденные аналоги</h2>
                            </div>
                            <span class="inline-flex min-w-9 items-center justify-center rounded-full bg-blue-100 px-3 py-1 text-base font-bold text-blue-700">
                                {{ analogs.length }}
                            </span>
                        </div>
                        <div class="flex-grow overflow-y-auto p-4">
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
import { useCartStore } from "@/Store/cartStore";
import DetailLayout from "./DetailLayout.vue";
import CartButton from '@/Components/CartButton.vue';
import Layout from "@/Shared/UserLayout.vue";
import { formatMoney } from "@/Services/PriceFormatter";
import { CART_QUANTITY_MAX, CART_QUANTITY_MIN } from "@/Config/AppConfig";

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
const notification = ref({ show: false, type: 'success', message: '' });
const currentQty = ref(CART_QUANTITY_MIN);
const showDeleteModal = ref(false);
const minimumQuantityTitle = `Минимальное количество — ${CART_QUANTITY_MIN}`;

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
        .post("/api/v1/cart", {
            id: props.detail.dt_id,
            quantity: CART_QUANTITY_MIN,
        })
        .then((res) => {
            showCartNotification({ type: 'success', message: 'Добавлено в корзину' });
            if (res.data?.data?.cartCount !== undefined) {
                store.setCartCount(res.data.data.cartCount);
            } else {
                store.incCartCount();
            }
            if (res.data?.data?.items) {
                store.setDetails(res.data.data.items);
            }
        })
        .catch((err) => {
            showCartNotification({
                type: 'error',
                message: err.response?.data?.message || 'Не удалось добавить товар в корзину.',
            });
        });
};

const incCount = () => {
    currentQty.value++;
    store.changeDetailQuantity(props.detail.dt_id, currentQty.value);
};

const decCount = () => {
    if (currentQty.value > CART_QUANTITY_MIN) {
        currentQty.value--;
        store.changeDetailQuantity(props.detail.dt_id, currentQty.value);
    }
};

const changeQuantity = () => {
    if (currentQty.value === '' || currentQty.value === null || currentQty.value === undefined) {
        return;
    }
    if (currentQty.value < CART_QUANTITY_MIN) {
        currentQty.value = CART_QUANTITY_MIN;
        return;
    }
    store.changeDetailQuantity(props.detail.dt_id, currentQty.value);
};

const enforceMinimum = () => {
    if (currentQty.value === '' || currentQty.value === null || currentQty.value === undefined || currentQty.value < CART_QUANTITY_MIN) {
        currentQty.value = CART_QUANTITY_MIN;
        store.changeDetailQuantity(props.detail.dt_id, CART_QUANTITY_MIN);
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
    currentQty.value = CART_QUANTITY_MIN;
    store.changeDetailQuantity(props.detail.dt_id, CART_QUANTITY_MIN);
};

const editTitle = (res) => editDetailTitle(res);

function showCartNotification(payload) {
    notification.value = {
        show: true,
        type: payload?.type === 'error' ? 'error' : 'success',
        message: payload?.message || 'Добавлено в корзину',
    };
}

function hideNotification(show) {
    notification.value.show = show;
}
</script>
