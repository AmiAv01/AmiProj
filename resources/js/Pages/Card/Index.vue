<template>
    <layout>
        <section class="py-8 bg-white md:py-16 dark:bg-gray-900 antialiased">
            <div class="w-full px-4 2xl:px-0">
                <div class="lg:grid px-4 lg:grid-cols-3 lg:gap-8 xl:gap-16">
                    <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
                        <!--img
                            v-if="detail.dt_foto.length === 0"
                            class="w-full hidden dark:block"
                            src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front-dark.svg"
                            alt=""
                        /!-->
                        <img

                            class="w-full dark:hidden"
                            src="../../../../public/build/no-photo--lg.png"
                            alt="#"
                        />
                    </div>

                    <div  class="mt-6 sm:mt-8 lg:mt-0">
                        <h1
                            class="text-4xl font-semibold text-gray-900 dark:text-white "
                        >
                            {{ editTitle(detail.dt_typec) }}
                            {{ isEmpty ? detail.dt_code : detail.dt_invoice }}
                            {{ isEmpty ? detail.dt_firm : '' }}
                        </h1>
                        <p v-if="isEmpty" class="text-4xl font-semibold text-gray-900 dark:text-white mb-8">(CARGO # <span> {{Array.from(this.cargoIds).join()}} </span>)</p>
                        <div v-if="!isEmpty">
                            <p class="font-normal text-2xl leading-8 text-gray-500" v-if="$page.props.auth.user">
                                OEM: <strong>{{ detail.dt_oem }}</strong>
                            </p>
                            <p class="font-normal text-2xl leading-8 text-gray-500">
                                CARGO: <strong>{{ detail.dt_cargo }}</strong>
                            </p>
                            <p class="font-normal text-2xl leading-8 text-gray-500" >
                                Бренд: <strong>{{ detail.fr_code }}</strong>
                            </p>
                            <p class="font-normal text-2xl leading-8 text-blue-700" v-if="$page.props.auth.user">
                                <strong>{{ detail.dt_comment }}</strong>
                            </p>
                            <p class="font-normal text-2xl leading-8 text-gray-500" >
                                Наличие: <strong>{{detail.ostc  ? detail.ostc : 0}}</strong>
                            </p>

                            <div class="mt-4 sm:items-center sm:gap-4 sm:flex" v-if="$page.props.auth.user">
                                <p
                                    class="text-2xl font-extrabold text-gray-900 sm:text-3xl dark:text-white"
                                >
                                    BYN 0
                                </p>
                            </div>

                            <div
                                class="flex mt-6 gap-8 sm:items-center sm:flex sm:mt-8" v-if="$page.props.auth.user"
                            >
                                <cart-button
                                    @click="addInCart"
                                    title=""
                                    class="bg-green-700 hover:bg-green-500 text-lg text-white mt-4 sm:mt-0 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 flex items-center justify-center"
                                    role="button"
                                >
                                </cart-button>
                                <p v-if="detail.dt_ost !== 0 " class="text-2xl text-green-400">Есть в наличии</p>
                                <p v-else class="text-2xl text-red-400">Нет в наличии</p>
                            </div>
                            <p class="text-3xl mt-6 text-green-400" v-if="!$page.props.auth.user">Наличие уточнять</p>
                        </div>
                        <div v-else>
                            <p class="text-2xl text-red-400">Нет в наличии</p>
                        </div>

                        <hr
                            class="my-6 md:my-8 border-gray-200 dark:border-gray-800"
                        />
                    </div>
                    <div
                        v-if="!!isHasDetails()"
                        class="rounded-lg border-2 w-[80%]"
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
            <analogs :details="analogs"/>
        </section>
    </layout>
</template>

<script>
import axios from "axios";
import { editDetailTitle } from "@/Services/TitleService";

export default {
    created() {
        console.log(this.sameDetails);
    },
    methods: {
        addInCart() {
            axios
                .post("/cart", {
                    id: this.detail[0].dt_id,
                    typec: this.detail[0].dt_typec,
                    invoice: this.detail[0].dt_invoice,
                    cargo: this.detail[0].dt_cargo,
                    fr_code: this.detail[0]._code,
                })
                .then((res) => console.log(res))
                .catch((err) => console.log(err));
        },
        editTitle(str) {
            return editDetailTitle(str);
        },
        isHasDetails() {
            if (
                this.detail.dt_typec === "ГЕНЕРАТОР" ||
                this.detail.dt_typec === "СТАРТЕР"
            ) {
                return true;
            }
            return false;
        },
        getTitleOfDetail(){

        }
    },
};
</script>

<script setup>
import DetailList from "@/Shared/DetailList.vue";
import Analogs from "@/Pages/Card/Analogs.vue";

defineProps({
    sameDetails: {
        type: Array,
    },
    detail: {
        type: Array,
    },
    analogs: {
        type: Array,
    },
    cargoIds: {
       type: Array,
    },
    isEmpty:{
        type: Boolean,
    }
});
</script>
