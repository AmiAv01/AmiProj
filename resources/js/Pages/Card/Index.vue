<template>
    <layout>
        <section class="py-8 bg-white md:py-16 dark:bg-gray-900 antialiased">
            <div class="w-full px-4 2xl:px-0">
                <div class="lg:grid px-4 lg:grid-cols-3 lg:gap-8 xl:gap-16">
                    <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
                        <img
                            class="w-full dark:hidden"
                            src="../../../../public/build/no-photo--lg.png"
                            alt=""
                        />
                        <img
                            class="w-full hidden dark:block"
                            src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front-dark.svg"
                            alt=""
                        />
                    </div>

                    <div class="mt-6 sm:mt-8 lg:mt-0">
                        <h1
                            class="text-4xl font-semibold text-gray-900 dark:text-white mb-8"
                        >
                            {{ detail[0].dt_typec }} {{ detail[0].dt_invoice }}
                        </h1>
                        <p class="font-normal text-2xl leading-8 text-gray-500">
                            OEM: <strong>{{ detail[0].dt_oem }}</strong>
                        </p>
                        <p class="font-normal text-2xl leading-8 text-gray-500">
                            CARGO: <strong>{{ detail[0].dt_cargo }}</strong>
                        </p>
                        <p class="font-normal text-2xl leading-8 text-gray-500">
                            Бренд: <strong>{{ detail[0].fr_code }}</strong>
                        </p>

                        <div class="mt-4 sm:items-center sm:gap-4 sm:flex">
                            <p
                                class="text-2xl font-extrabold text-gray-900 sm:text-3xl dark:text-white"
                            >
                                BYN 0
                            </p>
                        </div>

                        <div
                            class="mt-6 sm:gap-4 sm:items-center sm:flex sm:mt-8"
                        >
                            <cart-button
                                @click="addInCart"
                                title=""
                                class="bg-green-700 hover:bg-green-500 text-lg text-white mt-4 sm:mt-0 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 flex items-center justify-center"
                                role="button"
                            >
                            </cart-button>
                            <a
                                href="#"
                                title=""
                                class="flex items-center justify-center py-2.5 px-5 text-lg font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                role="button"
                            >
                                <svg
                                    class="w-9 h-9 -ms-2 me-2"
                                    aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z"
                                    />
                                </svg>
                                <span class="pl-4">Добавить в избранное</span>
                            </a>
                        </div>

                        <hr
                            class="my-6 md:my-8 border-gray-200 dark:border-gray-800"
                        />
                    </div>
                    <div class="rounded-lg border-2">
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
    </layout>
</template>

<script>
import axios from "axios";

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
    },
};
</script>

<script setup>
import axios from "axios";

defineProps({
    sameDetails: {
        type: Array,
    },
    detail: {
        type: Array,
    },
});
</script>
