<template>
    <layout>
        <section class="py-8 bg-white md:py-16  antialiased">
            <div class="w-full px-4 2xl:px-0">
                <div class="2xl:grid px-4 lg:grid-cols-3 gap-8 2xl:gap-16 ">
                    <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
                        <img
                            class="w-full"
                            :src="imageUrl"
                            alt=""
                        />
                    </div>

                    <div  class="mt-6 sm:mt-8 2xl:mt-0">
                        <h1
                            class="text-4xl font-semibold text-gray-900  "
                        >
                            {{ editTitle(detail.dt_typec) }}
                            {{ isEmpty ? detail.dt_code : detail.dt_invoice }}
                            {{ isEmpty ? detail.dt_firm : '' }}
                        </h1>
                        <p v-if="isEmpty" class="text-4xl font-semibold text-gray-900  mb-8">(CARGO # <span> {{Array.from(cargoIds).join()}} </span>)</p>
                        <div v-if="!isEmpty">
                            <p class="font-normal text-2xl leading-8 text-gray-500" >
                                OEM: <strong>{{ detail.dt_oem }}</strong>
                            </p>
                            <p class="font-normal text-2xl leading-8 text-gray-500">
                                CARGO: <strong>{{ detail.dt_cargo }}</strong>
                            </p>
                            <p class="font-normal text-2xl leading-8 text-gray-500" >
                                Бренд: <strong>{{ detail.fr_code }}</strong>
                            </p>
                            <p class="font-normal text-2xl leading-8 text-blue-700" >
                                <strong>{{ detail.dt_comment }}</strong>
                            </p>
                            <p class="font-normal text-2xl leading-8 text-gray-500" >
                                Наличие: <strong>{{detail.ostc  ? detail.ostc : 0}}</strong>
                            </p>

                            <div class="mt-4 sm:items-center sm:gap-4 sm:flex" >
                                <p
                                    class="text-2xl font-extrabold text-gray-900 sm:text-3xl "
                                >
                                    {{price !== '0' ? `${price} BYN` : 'цену уточнять'}}
                                </p>
                            </div>

                            <div
                                class="flex mt-6 gap-8 sm:items-center sm:flex sm:mt-8"
                            >
                                <cart-button
                                    @click="addInCart"
                                    title=""
                                    class="bg-green-700 hover:bg-green-500 text-lg text-white mt-4 sm:mt-0 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 flex items-center justify-center"
                                    role="button"
                                >
                                </cart-button>
                                <p v-if="detail.ostc" class="text-2xl text-green-400">Есть в наличии</p>
                                <p v-else class="text-2xl text-red-400">Нет в наличии</p>
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-2xl text-red-400">Нет в наличии</p>
                        </div>

                        <hr
                            class="my-6 md:my-8 border-gray-200 "
                        />
                    </div>
                    <div
                        v-if="!!isHasDetails()"
                        class="rounded-lg border-2 w-[80%] mx-auto 2xl:mx-0"
                    >
                        <p class="text-2xl px-4 py-4 text-center border-b-2">
                            <strong>Деталировка</strong>
                        </p>
                        <div class="h-[400px] overflow-y-auto">
                            <detail-list :details="sameDetails" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="p-6">
            <p class="text-center font-bold text-5xl mb-8">Похожие запчасти</p>
            <Analogs :details="analogs"/>
        </section>
    </layout>
</template>

<script setup>
import axios from "axios";
import { editDetailTitle } from "@/Services/TitleService";
import Analogs from "@/Pages/Card/Analogs.vue";
import {onMounted} from "vue";

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


const addInCart = () =>  {
    axios
        .post("/cart", {
            id: props.detail.dt_id,
            quantity: 1,
            price: props.price
        })
        .then((res) => console.log(res))
        .catch((err) => console.log(err));
}

const editTitle = (res) => editDetailTitle(res)
const isHasDetails = () => {
    return (
        props.detail.dt_typec === "ГЕНЕРАТОР" ||
        props.detail.dt_typec === "СТАРТЕР"
    );
}
console.log(props.detail.ostc)

</script>

