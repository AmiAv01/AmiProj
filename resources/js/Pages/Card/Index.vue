<template>
    <layout>
        <push v-if="isShow" :isShow="isShow" @hide="hideModal" :title="`Добавлено в корзину`">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </push>
        <section class="py-4 bg-white md:py-8 antialiased">
            <div class="w-full max-w-5xl px-4 mx-auto">
                <div class="grid px-2 lg:grid-cols-3 gap-4">
                    <div class="lg:col-span-2 border rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                            <div class="shrink-0 max-w-xs mx-auto md:mx-0">
                                <img
                                    class="w-full max-h-48 object-contain"
                                    :src="imageUrl"
                                    alt="Product image"
                                />
                            </div>
                            <div class="mt-2 sm:mt-4">
                                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">
                                    {{ editTitle(detail.dt_typec) }}
                                    {{ isEmpty ? detail.dt_code : detail.dt_invoice }}
                                    {{ isEmpty ? detail.dt_firm : '' }}
                                </h1>
                                <p v-if="isEmpty" class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">
                                    (CARGO # <span>{{Array.from(cargoIds).join()}}</span>)
                                </p>
                                <div v-if="!isEmpty" class="space-y-1">
                                    <p class="font-normal text-sm sm:text-base leading-5 text-gray-500">
                                        OEM: <strong>{{ detail.dt_oem }}</strong>
                                    </p>
                                    <p class="font-normal text-sm sm:text-base leading-5 text-gray-500">
                                        CARGO: <strong>{{ detail.dt_cargo }}</strong>
                                    </p>
                                    <p class="font-normal text-sm sm:text-base leading-5 text-gray-500">
                                        Бренд: <strong>{{ detail.fr_code }}</strong>
                                    </p>
                                    <p class="font-normal text-sm sm:text-base leading-5 text-blue-700">
                                        <strong>{{ detail.dt_comment }}</strong>
                                    </p>
                                    <p class="font-normal text-sm sm:text-base leading-5 text-gray-500">
                                        Наличие: <strong>{{detail.ostc ? detail.ostc : 0}}</strong>
                                    </p>

                                    <div class="mt-2 sm:items-center sm:gap-2 sm:flex">
                                        <p class="text-lg font-extrabold text-gray-900 sm:text-xl">
                                            {{price !== '0' ? `${price} BYN` : 'цену уточнять'}}
                                        </p>
                                    </div>

                                    <div class="flex mt-2 gap-2 sm:items-center sm:flex sm:mt-4">
                                        <cart-button
                                            v-if="detail.ostc && price !== '0'"
                                            @click="addInCart"
                                            title=""
                                            class="bg-green-700 hover:bg-green-500 text-xs sm:text-sm text-white mt-1 sm:mt-0 font-medium rounded-lg px-3 py-1 flex items-center justify-center"
                                            role="button"
                                        >
                                        </cart-button>
                                        <p v-if="detail.ostc" class="text-base text-green-400">Есть</p>
                                        <p v-else class="text-base text-red-400">Нет</p>
                                    </div>
                                </div>
                                <div v-else>
                                    <p class="text-base text-red-400">Нет в наличии</p>
                                </div>

                                <hr class="my-2 md:my-3 border-gray-200"/>
                            </div>
                        </div>

                        <DetailLayout
                            v-if="!!isHasDetails()"
                            :details="sameDetails"
                            class="mt-4 lg:ml-0"
                        />
                    </div>

                    <div class="bg-white p-3 rounded-lg border self-start">
                        <p class="text-lg font-bold mb-3 text-center">Найденные аналоги</p>
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

