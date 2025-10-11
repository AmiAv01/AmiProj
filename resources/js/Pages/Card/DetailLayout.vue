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
            <div class="grid grid-cols-5 gap-4 p-3 border-b hover:bg-gray-50 text-center font-bold">
                <div class="flex items-center justify-center text-left">Артикул</div>
                <div class="flex items-center justify-center text-left">Название</div>
                <div class="flex items-center justify-center text-left">Бренд</div>
                <div class="flex items-center justify-center text-left">Остаток</div>
                <div class="flex items-center justify-center text-left"></div>
            </div>

            <div
                v-for="(item, index) in details"
                :key="index"
                class="grid grid-cols-5 gap-4 p-3 border-b hover:bg-gray-50 text-center"
            >
                <div class="flex items-center justify-center">
                    <a
                        :href="`../../catalog/product/${item.dt_invoice}`"
                        class="text-blue-600 hover:underline text-sm sm:text-base"
                    >
                        {{ item.dt_invoice }}
                    </a>
                </div>

                <div class="flex items-center justify-center text-sm sm:text-base">
                    {{ item.dt_typec }}
                </div>

                <div class="flex items-center justify-center text-sm sm:text-base">
                    {{ item.fr_code }}
                </div>

                <div class="flex items-center justify-center text-sm sm:text-base">
                    <span v-if="item.stock_quantity" class="text-green-500">{{ item.stock_quantity }} шт.</span>
                    <span v-else class="text-red-500">Нет в наличии</span>
                </div>

                <div class="flex items-center justify-center">
                    <cart-button
                        v-if="item.stock_quantity > 0"
                        @addInCart="addDetailItemToCart(item.dt_id)"
                        class="bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-md px-3 py-1.5 text-sm"
                    >
                        В корзину
                    </cart-button>
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