<template>
    <push v-if="isShow" :isShow="isShow" @hide="hideModal" :title="`Заказ успешно оформлен`">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"  stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    </push>
    <div
        class="col-span-12 xl:col-span-4 bg-gray-50 w-full max-xl:px-6 max-w-3xl xl:max-w-lg mx-auto lg:pl-8 py-24"
    >
        <h2
            class="font-manrope font-bold text-3xl leading-10 text-black pb-8 border-b border-gray-300"
        >
            Ваш заказ
        </h2>
        <div class="mt-8">
            <form>
                <div class="flex pb-4 w-full"></div>

                <div class="flex items-center justify-between py-8">
                    <p class="font-medium text-xl leading-8 text-black">
                        Товары, {{ count }} шт.
                    </p>
                    <p class="font-semibold text-xl leading-8 text-green-500">
                        {{ price }} BYN
                    </p>
                </div>
                <button
                    @click.prevent="makeOrder"
                    class="w-full text-center bg-green-700 rounded-xl py-3 px-6 font-semibold text-lg text-white transition-all duration-500 hover:bg-green-600"
                >
                    Оформить заказ
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import axios from "axios";
import {ref} from "vue";
import {useCartStore} from "@/Store/cartStore.js";

const store = useCartStore();
const isShow = ref(false);
const props = defineProps({
    count: {
        type: Number,
        default: 0,
    },
    price: {
        type: Number,
        default: 0,
    }
})

async function makeOrder() {
    try {
        const orderResponse = await axios.post("/order", { totalPrice: props.price });
        console.log(orderResponse);
        isShow.value = true;

        const clearResponse = await axios.put('/clear');
        console.log(clearResponse);
        store.setDetails([]);
        store.setCartCount = 0;
    } catch (err) {
        console.error(err);
    }
}

function hideModal(param){
    isShow.value = param;
}

</script>
