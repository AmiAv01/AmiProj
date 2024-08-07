<template>
    <layout>
        <section>
            <div class="w-full" scroll-region>
                <section class="bg-white dark:bg-gray-900">
                    <div
                        class="grid max-w-screen-xl py-8 mx-auto lg:gap-8 xl:gap-6 lg:py-16 lg:grid-cols-12"
                    >
                        <div class="mr-auto place-self-center lg:col-span-7">
                            <h1
                                class="max-w-3xl mb-12 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white"
                            >
                                Большой выбор стартеров, генераторов и других
                                автозапчастей
                            </h1>
                            <p
                                class="max-w-2xl mb-6 font-normal text-gray-500 lg:mb-8 md:text-lg lg:text-2xl dark:text-gray-400"
                            >
                                Узнайте больше о наших запчастях
                            </p>
                            <a
                                href="#"
                                class="inline-flex items-center justify-center px-16 py-6 mr-3 text-xl font-medium text-center text-white rounded-lg bg-green-700 hover:bg-green-900 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900"
                            >
                                Стартеры
                                <svg
                                    class="w-5 h-5 ml-2 -mr-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                            </a>
                            <a
                                href="#"
                                class="inline-flex items-center justify-center px-16 py-6 text-xl font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
                            >
                                Генераторы
                                <svg
                                    class="w-5 h-5 ml-2 -mr-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                            </a>
                        </div>
                        <div class="hidden ml-12 lg:col-span-5 lg:flex">
                            <img
                                src="../../../../public/starters.jpg"
                                alt="mockup"
                            />
                        </div>
                    </div>
                </section>

                <!--div class="w-[1300px] flex justify-between mx-auto my-0">
                    <div
                        class="flex justify-around border border-gray-200 w-[60%] h-[600px] rounded-[15px] shadow"
                    >
                        <img
                            src="../../../../public/generators.jpg"
                            class="h-[200px] w-[200px] self-center ml-4 bg-transparent"
                            alt="#"
                        />
                        <div class="flex flex-col w-[40%] justify-center">
                            <p class="text-7xl mb-8">Стартеры</p>
                            <button
                                type="button"
                                class="flex justify-center items-center text-white bg-green-700 mr-2 w-[200px] hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-xl px-4 py-4 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                            >
                                Перейти
                                <svg
                                    class="w-5 h-5 ml-6 text-white dark:text-white"
                                    aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 14 10"
                                >
                                    <path
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M1 5h12m0 0L9 1m4 4L9 9"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="w-[38%]">
                        <div
                            class="flex justify-around border border-gray-200 mb-[20px] h-[290px] rounded-[15px] shadow"
                        >
                            <img
                                src="../../../../public/generators.jpg"
                                class="h-[100px] w-[100px] self-center ml-4"
                                alt="#"
                            />
                            <div class="flex flex-col justify-center w-[40%]">
                                <p class="text-4xl mb-4">Генераторы</p>
                                <Link
                                    :href="route('generator.index')"
                                    type="button"
                                    class="flex justify-center items-center cursor-pointer text-white bg-green-700 mr-2 w-[160px] hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-md px-4 py-3 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                >
                                    Перейти
                                    <svg
                                        class="w-5 h-5 ml-6 text-white dark:text-white"
                                        aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 14 10"
                                    >
                                        <path
                                            stroke="currentColor"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M1 5h12m0 0L9 1m4 4L9 9"
                                        />
                                    </svg>
                                </Link>
                            </div>
                        </div>
                        <div
                            class="flex justify-around border border-gray-200 h-[290px] rounded-[15px] shadow"
                        >
                            <img
                                src="../../../../public/generators.jpg"
                                class="h-[100px] w-[100px] self-center ml-4"
                                alt="#"
                            />
                            <div class="flex flex-col justify-center w-[40%]">
                                <p class="text-4xl mb-4">Другие запчасти</p>
                                <button
                                    type="button"
                                    class="flex justify-center items-center text-white bg-green-700 mr-2 w-[160px] hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-md px-4 py-3 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                >
                                    Перейти
                                    <svg
                                        class="w-5 h-5 ml-6 text-white dark:text-white"
                                        aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 14 10"
                                    >
                                        <path
                                            stroke="currentColor"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M1 5h12m0 0L9 1m4 4L9 9"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div!-->
            </div>
        </section>
        <section>
            <ProductList v-bind:details="details" />
            <LogoClouds />
            <News />
        </section>
    </layout>
</template>

<script>
import LogoClouds from "./LogoClouds.vue";
import ProductList from "./ProductList.vue";
import News from "@/Shared/News.vue";
import UserLayout from "@/Shared/UserLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";

export default {
    components: {
        layout: UserLayout,
    },
};
</script>

<script setup>
defineProps({
    details: Object,
});
</script>
