<template>
    <div class="w-full overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <button
            type="button"
            class="flex w-full items-center justify-between gap-4 px-4 py-4 text-left transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-blue-500"
            @click="toggleDetails"
            :aria-expanded="showDetails"
        >
            <span class="flex min-w-0 items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.75h16M4 12h16M4 17.25h10" />
                    </svg>
                </span>
                <span>
                    <strong class="block text-xl font-bold text-slate-900">Деталировка</strong>
                    <span class="mt-0.5 block text-sm font-medium text-slate-500">{{ details.length }} {{ detailsWord }}</span>
                </span>
            </span>
            <span class="flex shrink-0 items-center gap-2 text-base font-semibold text-slate-500">
                <span class="hidden sm:inline">{{ showDetails ? 'Свернуть' : 'Развернуть' }}</span>
                <svg class="h-6 w-6 transition-transform duration-200" :class="{ 'rotate-180': showDetails }" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6" />
                </svg>
            </span>
        </button>

        <div v-if="showDetails" class="overflow-x-auto border-t border-slate-200">
            <div class="grid min-w-[920px] grid-cols-[80px_minmax(120px,1fr)_minmax(180px,1.35fr)_minmax(80px,0.7fr)_minmax(110px,0.8fr)_260px] gap-4 border-b border-slate-200 bg-slate-50 px-3 py-3 text-center text-lg font-bold text-slate-600">
                <div class="flex items-center justify-center text-left">Фото</div>
                <div class="flex items-center justify-center text-left">Артикул</div>
                <div class="flex items-center justify-center text-left">Название</div>
                <div class="flex items-center justify-center text-left">Бренд</div>
                <div class="flex items-center justify-center text-left">Остаток</div>
                <div class="flex items-center justify-center text-left"></div>
            </div>

            <div
                v-for="(item, index) in details"
                :key="index"
                class="grid min-w-[920px] grid-cols-[80px_minmax(120px,1fr)_minmax(180px,1.35fr)_minmax(80px,0.7fr)_minmax(110px,0.8fr)_260px] gap-4 border-b border-slate-100 px-3 py-3 text-center transition-colors last:border-b-0 hover:bg-blue-50/40"
                :class="{ 'cursor-pointer': $page.props.auth.user }"
                :role="$page.props.auth.user ? 'link' : undefined"
                :tabindex="$page.props.auth.user ? 0 : undefined"
                @click="$page.props.auth.user && openProduct(item.dt_invoice)"
                @keydown.enter="$page.props.auth.user && openProduct(item.dt_invoice)"
                @keydown.space.prevent="$page.props.auth.user && openProduct(item.dt_invoice)"
            >
                <div class="flex items-center justify-center">
                    <img
                        :src="item.imageUrl"
                        alt="Part"
                        class="h-12 w-12 rounded-lg border border-slate-200 bg-slate-50 object-contain p-1"
                    />
                </div>

                <div class="flex items-center justify-center">
                    <a
                        v-if="$page.props.auth.user"
                        :href="`../../catalog/product/${item.dt_invoice}`"
                        class="text-blue-600 hover:underline text-base lg:text-lg font-semibold"
                    >
                        {{ item.dt_invoice }}
                    </a>
                    <span v-else class="text-gray-600 text-base lg:text-lg font-mono">
                        {{ item.dt_invoice }}
                    </span>
                </div>

                <div class="flex items-center justify-center text-base lg:text-lg">
                    {{ item.dt_typec }}
                </div>

                <div class="flex items-center justify-center text-base lg:text-lg">
                    {{ item.fr_code }}
                </div>

                <div class="flex items-center justify-center text-base lg:text-lg">
                    <span v-if="item.stock_quantity" class="text-green-500 font-semibold">{{ item.stock_quantity }} шт.</span>
                    <span v-else class="text-red-500">Нет в наличии</span>
                </div>

                <div
                    class="flex items-center justify-center"
                    @click.stop
                    @keydown.stop
                >
                    <!-- Если товар уже в корзине -->
                    <div v-if="getCartItem(item.dt_id)" class="flex w-full items-center justify-start gap-2 whitespace-nowrap">
                        <div class="flex h-10 shrink-0 items-stretch overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm">
                            <button
                                @click="decDetailCount(item.dt_id)"
                                :disabled="getCartItem(item.dt_id).quantity <= CART_QUANTITY_MIN"
                                class="flex w-9 items-center justify-center bg-gray-100 text-lg font-bold transition-colors hover:bg-gray-200 disabled:cursor-not-allowed disabled:opacity-40"
                                :title="minimumQuantityTitle"
                                aria-label="Уменьшить количество"
                            >
                                -
                            </button>
                            <input
                                type="number"
                                :value="getCartItem(item.dt_id).quantity"
                                @input="changeDetailQty(item.dt_id, $event.target.value)"
                                @change="enforceDetailQty(item.dt_id, $event.target.value)"
                                :min="CART_QUANTITY_MIN"
                                :max="CART_QUANTITY_MAX"
                                class="h-full w-14 border-x border-y-0 border-gray-300 bg-transparent p-0 text-center text-base font-semibold focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                aria-label="Количество"
                            />
                            <button
                                @click="incDetailCount(item.dt_id)"
                                :disabled="getCartItem(item.dt_id).quantity >= CART_QUANTITY_MAX"
                                class="flex w-9 items-center justify-center bg-gray-100 text-lg font-bold transition-colors hover:bg-gray-200 disabled:cursor-not-allowed disabled:opacity-40"
                                aria-label="Увеличить количество"
                            >
                                +
                            </button>
                        </div>
                        <button
                            @click="confirmDetailDelete(item.dt_id)"
                            class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-red-200 text-red-600 transition-colors hover:bg-red-50"
                            title="Удалить товар из корзины"
                            aria-label="Удалить товар из корзины"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6" />
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                            </svg>
                        </button>
                        <spa-link
                            href="/cart"
                            class="inline-flex min-h-10 shrink-0 items-center justify-center rounded-lg bg-green-600 px-3 py-2 text-sm font-bold text-white transition-colors hover:bg-green-500"
                            title="Перейти в корзину"
                        >
                            В корзине
                        </spa-link>
                    </div>

                    <!-- Если товара нет в корзине -->
                    <button
                        v-else-if="item.stock_quantity && $page.props.auth.user"
                        @click="addDetailItemToCart(item.dt_id)"
                        class="bg-green-700 hover:bg-green-600 text-white p-2.5 rounded-lg transition-colors flex items-center justify-center shadow-sm"
                        title="Добавить в корзину"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                            ></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="bg-white w-full p-4 flex text-lg text-gray-400 items-center justify-center" v-if="!details.length">
                <p>Деталировка отсутствует</p>
            </div>
        </div>

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
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from "axios";
import { useCartStore } from "@/Store/cartStore";
import { CART_QUANTITY_MAX, CART_QUANTITY_MIN } from "@/Config/AppConfig";

const props = defineProps({
    details: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['cart-notification']);

const store = useCartStore();
const showDetails = ref(false);
const showDeleteModal = ref(false);
const activeProductIdToDelete = ref(null);
const minimumQuantityTitle = `Минимальное количество — ${CART_QUANTITY_MIN}`;
const detailsWord = computed(() => {
    const count = props.details.length;
    const mod100 = count % 100;
    const mod10 = count % 10;

    if (mod100 >= 11 && mod100 <= 14) return 'позиций';
    if (mod10 === 1) return 'позиция';
    if (mod10 >= 2 && mod10 <= 4) return 'позиции';
    return 'позиций';
});

const openProduct = (invoice) => {
    window.location.href = `../../catalog/product/${invoice}`;
};

const toggleDetails = () => {
    showDetails.value = !showDetails.value;
};

// Вычисляемая ассоциативная карта товаров для надежной реактивности
const cartMap = computed(() => {
    const map = {};
    if (store.cartData) {
        Object.values(store.cartData).forEach(item => {
            map[item.dt_id] = item;
        });
    }
    return map;
});

const getCartItem = (productId) => {
    return cartMap.value[productId] || null;
};

const incDetailCount = (productId) => {
    const cartItem = getCartItem(productId);
    if (cartItem) {
        store.changeDetailQuantity(productId, cartItem.quantity + 1);
    }
};

const decDetailCount = (productId) => {
    const cartItem = getCartItem(productId);
    if (cartItem) {
        if (cartItem.quantity > CART_QUANTITY_MIN) {
            store.changeDetailQuantity(productId, cartItem.quantity - 1);
        }
    }
};

const changeDetailQty = (productId, val) => {
    const parsed = parseInt(val);
    if (isNaN(parsed)) {
        return;
    }
    if (parsed < CART_QUANTITY_MIN) {
        store.changeDetailQuantity(productId, CART_QUANTITY_MIN);
        return;
    }
    store.changeDetailQuantity(productId, parsed);
};

const enforceDetailQty = (productId, val) => {
    const parsed = parseInt(val);
    store.changeDetailQuantity(productId, isNaN(parsed) ? CART_QUANTITY_MIN : parsed);
};

const confirmDetailDelete = (productId) => {
    activeProductIdToDelete.value = productId;
    showDeleteModal.value = true;
};

const proceedDelete = () => {
    if (activeProductIdToDelete.value) {
        store.deleteDetailFromCart(activeProductIdToDelete.value);
    }
    showDeleteModal.value = false;
    activeProductIdToDelete.value = null;
};

const cancelDelete = () => {
    if (activeProductIdToDelete.value) {
        store.changeDetailQuantity(activeProductIdToDelete.value, CART_QUANTITY_MIN);
    }
    showDeleteModal.value = false;
    activeProductIdToDelete.value = null;
};

const addDetailItemToCart = (productId) => {
    axios
        .post("/api/v1/cart", {
            id: productId,
            quantity: CART_QUANTITY_MIN,
        })
        .then((res) => {
            if (res.data?.data?.cartCount !== undefined) {
                store.setCartCount(res.data.data.cartCount);
            } else {
                store.incCartCount();
            }
            if (res.data?.data?.items) {
                store.setDetails(res.data.data.items);
            }
            emit('cart-notification', { type: 'success', message: 'Добавлено в корзину' });
        })
        .catch((err) => {
            emit('cart-notification', {
                type: 'error',
                message: err.response?.data?.message || 'Не удалось добавить товар в корзину.',
            });
        });
};
</script>
