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
            <!-- Изменено на grid-cols-6 -->
            <div class="grid grid-cols-6 gap-4 p-3 border-b hover:bg-gray-50 text-center font-bold">
                <div class="flex items-center justify-center text-left">Фото</div> <!-- Новая колонка -->
                <div class="flex items-center justify-center text-left">Артикул</div>
                <div class="flex items-center justify-center text-left">Название</div>
                <div class="flex items-center justify-center text-left">Бренд</div>
                <div class="flex items-center justify-center text-left">Остаток</div>
                <div class="flex items-center justify-center text-left"></div>
            </div>

            <!-- Изменено на grid-cols-6 -->
            <div
                v-for="(item, index) in details"
                :key="index"
                class="grid grid-cols-6 gap-4 p-3 border-b hover:bg-gray-50 text-center"
            >
                <!-- Отображение миниатюры -->
                <div class="flex items-center justify-center">
                    <img
                        :src="item.imageUrl"
                        alt="Part"
                        class="w-12 h-12 object-contain rounded border bg-gray-50"
                    />
                </div>

                <div class="flex items-center justify-center">
                    <!-- Если пользователь не авторизован, убираем ссылку на карточку товара, так как артикул скрыт звездками -->
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
                    <button
                        v-if="item.stock_quantity && $page.props.auth.user"
                        @click="addDetailItemToCart(item.dt_id)"
                        class="bg-green-700 hover:bg-green-600 text-white p-2.5 rounded-lg transition-colors flex items-center justify-center shadow-sm"
                        title="Добавить в корзину"
                    >
                        <!-- Чистая SVG иконка тележки без лишнего текста -->
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
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from "axios";
import { useCartStore } from "@/Store/cartStore.js";
import CartButton from '@/Components/CartButton.vue';

const props = defineProps({
    details: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['itemAddedToCart']);

const store = useCartStore();
const showDetails = ref(false);

const toggleDetails = () => {
    showDetails.value = !showDetails.value;
};

const addDetailItemToCart = (productId) => {
    axios
        .post("/cart", {
            id: productId,
        })
        .then((res) => {
            console.log(res);
            store.incCartCount();
            emit('itemAddedToCart');
        })
        .catch((err) => console.log(err));
};
</script>
