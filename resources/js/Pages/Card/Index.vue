<template>
    <layout>
        <push v-if="isShow" :isShow="isShow" @hide="hideModal" :title="`Добавлено в корзину`">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </push>
        <section class="py-6 bg-white md:py-10 antialiased">
            <div class="w-full max-w-6xl px-6 mx-auto">
                <div class="grid px-4 lg:grid-cols-3 gap-6">
                    <!-- Основной блок с информацией о товаре -->
                    <div class="lg:col-span-2 border rounded-lg p-5 bg-white shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                            <div class="shrink-0 max-w-sm mx-auto md:mx-0">
                                <img
                                    class="w-full max-h-56 object-contain"
                                    :src="imageUrl"
                                    alt="Product image"
                                />
                            </div>
                            <div class="mt-3 sm:mt-5">
                                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">
                                    {{ editTitle(detail.dt_typec) }}
                                    {{ isEmpty ? detail.dt_code : detail.dt_invoice }}
                                    {{ isEmpty ? detail.dt_firm : '' }}
                                </h1>
                                
                                <div class="mt-3 space-y-2">
                                    <p v-if="isEmpty" class="text-lg sm:text-xl font-semibold text-gray-900">
                                        (CARGO # <span>{{Array.from(cargoIds).join()}}</span>)
                                    </p>
                                    
                                    <div v-if="!isEmpty" class="space-y-2">
                                        <p class="font-normal text-sm sm:text-base text-gray-600">
                                            <span class="text-gray-500">OEM:</span> <strong>{{ detail.dt_oem }}</strong>
                                        </p>
                                        <p class="font-normal text-sm sm:text-base text-gray-600">
                                            <span class="text-gray-500">CARGO:</span> <strong>{{ detail.dt_cargo }}</strong>
                                        </p>
                                        <p class="font-normal text-sm sm:text-base text-gray-600">
                                            <span class="text-gray-500">Бренд:</span> <strong>{{ detail.fr_code }}</strong>
                                        </p>
                                        <p class="font-normal text-sm sm:text-base text-blue-700">
                                            <strong>{{ detail.dt_comment }}</strong>
                                        </p>
                                        <p class="font-normal text-sm sm:text-base text-gray-600">
                                            <span class="text-gray-500">Наличие:</span> 
                                            <strong>{{detail.ostc ? detail.ostc : 0}}</strong>
                                        </p>

                                        <div class="flex items-center mt-3 gap-3">
                                            <p class="text-xl font-extrabold text-gray-900">
                                                {{price !== '0' ? `${price} BYN` : 'цену уточнять'}}
                                            </p>
                                            <p v-if="detail.ostc" class="text-base text-green-500 ml-2">Есть</p>
                                            <p v-else class="text-base text-red-500 ml-2">Нет</p>
                                        </div>

                                        <cart-button
                                            v-if="detail.ostc && price !== '0'"
                                            @click="addInCart"
                                            title=""
                                            class="bg-green-600 hover:bg-green-500 text-sm text-white mt-2 font-medium rounded-md px-4 py-2 inline-flex items-center"
                                            role="button"
                                        >
                                            Добавить в корзину
                                        </cart-button>
                                    </div>
                                    <div v-else>
                                        <p class="text-base text-red-500">Нет в наличии</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Блок деталировки -->
                        <div class="mt-6 border-t pt-4">
                            <h2 class="text-lg font-bold mb-3">Деталировка</h2>
                            <DetailLayout :details="sameDetails"/>
                        </div>
                    </div>

                    <!-- Блок аналогов -->
                    <div class="bg-white p-5 rounded-lg border shadow-sm">
                        <h2 class="text-lg font-bold mb-4 text-center">Найденные аналоги</h2>
                        <Analogs :details="analogs"/>
                    </div>
                </div>
            </div>
        </section>
    </layout>
</template>

<script setup>
import axios from "axios";
import { editDetailTitle } from "@/Services/TitleService";
import Analogs from "@/Pages/Card/Analogs.vue";
import {ref} from "vue";
import {useCartStore} from "@/Store/cartStore.js";
import DetailLayout from "./DetailLayout.vue";

const props = defineProps({
    sameDetails: {
        type: Array,
    },
    detail: {
        type: Object,
    },
    analogs: {
        type: Array,
    },
    cargoIds: {
        type: Array,
    },
    isEmpty:{
        type: Boolean,
    },
    price: {
        type: String
    },
    imageUrl: {
        type: String
    }
});

const store = useCartStore();
const isShow = ref(false);

const addInCart = () =>  {
    axios
        .post("/cart", {
            id: props.detail.dt_id,
            quantity: 1,
            price: props.price
        })
        .then((res) => {
            console.log(res);
            isShow.value = true;
            store.incCartCount();
        })
        .catch((err) => console.log(err));
}

const editTitle = (res) => editDetailTitle(res)
const isHasDetails = () => {
    return (
        props.detail.dt_typec === "ГЕНЕРАТОР" ||
        props.detail.dt_typec === "СТАРТЕР"
    );
}

function hideModal(param){
    isShow.value = param;
}
</script>

