<template>
    <div class="rounded-lg border-2 w-full">
        <p
            class="text-lg font-bold px-4 py-4 text-center border-b-2 cursor-pointer flex justify-between items-center"
            @click="toggleDetails"
        >
            <strong>Деталировка</strong>
            <span class="text-gray-500 text-sm ml-2">{{ showDetails ? 'Свернуть ▲' : 'Развернуть ▼' }}</span>
        </p>

        <div v-if="showDetails">
            <div class="grid grid-cols-6 gap-4 p-3 border-b hover:bg-gray-50 text-center font-bold">
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
                class="grid grid-cols-6 gap-4 p-3 border-b hover:bg-gray-50 text-center"
            >
                <div class="flex items-center justify-center">
                    <img
                        :src="item.imageUrl"
                        alt="Part"
                        class="w-12 h-12 object-contain rounded border bg-gray-50"
                    />
                </div>

                <div class="flex items-center justify-center">
                    <a
                        v-if="$page.props.auth.user"
                        :href="`../../catalog/product/${item.dt_invoice}`"
                        class="text-blue-600 hover:underline text-sm sm:text-base font-semibold"
                    >
                        {{ item.dt_invoice }}
                    </a>
                    <span v-else class="text-gray-600 text-sm sm:text-base font-mono">
                        {{ item.dt_invoice }}
                    </span>
                </div>

                <div class="flex items-center justify-center text-sm sm:text-base">
                    {{ item.dt_typec }}
                </div>

                <div class="flex items-center justify-center text-sm sm:text-base">
                    {{ item.fr_code }}
                </div>

                <div class="flex items-center justify-center text-sm sm:text-base">
                    <span v-if="item.stock_quantity" class="text-green-500 font-semibold">{{ item.stock_quantity }} шт.</span>
                    <span v-else class="text-red-500">Нет в наличии</span>
                </div>

                <div class="flex items-center justify-center">
                    <!-- Если товар уже в корзине -->
                    <div v-if="getCartItem(item.dt_id)" class="flex items-center gap-2">
                        <div class="flex items-center border border-gray-300 rounded-lg shadow-sm">
                            <button
                                @click="decDetailCount(item.dt_id)"
                                class="px-2 py-0.5 bg-gray-100 hover:bg-gray-200 font-bold transition-colors"
                            >
                                -
                            </button>
                            <input
                                type="number"
                                :value="getCartItem(item.dt_id).quantity"
                                @input="changeDetailQty(item.dt_id, $event.target.value)"
                                class="w-12 text-center border-none py-0.5 focus:outline-none font-semibold bg-transparent"
                            />
                            <button
                                @click="incDetailCount(item.dt_id)"
                                class="px-2 py-0.5 bg-gray-100 hover:bg-gray-200 font-bold transition-colors"
                            >
                                +
                            </button>
                        </div>
                        <spa-link
                            href="/cart"
                            class="bg-green-600 hover:bg-green-500 text-white text-xs font-bold px-2 py-1 rounded transition-colors"
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

const props = defineProps({
    details: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['itemAddedToCart']);

const store = useCartStore();
const showDetails = ref(false);
const showDeleteModal = ref(false);
const activeProductIdToDelete = ref(null);

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
        if (cartItem.quantity > 1) {
            store.changeDetailQuantity(productId, cartItem.quantity - 1);
        } else {
            confirmDetailDelete(productId);
        }
    }
};

const changeDetailQty = (productId, val) => {
    const parsed = parseInt(val);
    if (isNaN(parsed) || parsed === null) {
        return;
    }
    if (parsed < 1) {
        confirmDetailDelete(productId);
        return;
    }
    store.changeDetailQuantity(productId, parsed);
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
        store.changeDetailQuantity(activeProductIdToDelete.value, 1);
    }
    showDeleteModal.value = false;
    activeProductIdToDelete.value = null;
};

const addDetailItemToCart = (productId) => {
    axios
        .post("/api/v1/cart", {
            id: productId,
            quantity: 1,
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
            emit('itemAddedToCart');
        })
        .catch((err) => console.log(err));
};
</script>
