<template>
    <header class="bg-slate-900 sticky top-0 z-40">
        <nav class="bg-slate-900 border-gray-200 dark:bg-gray-900">
            <div
                class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-6"
            >
                <a
                    href="http://localhost:8000"
                    class="flex items-center space-x-3 rtl:space-x-reverse"
                >
                    <img
                        src="../../../../../public/logo2.png"
                        class="h-8"
                        alt="AmiAvto Logo"
                    />
                </a>
                <div
                    class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse"
                >
                    <Link
                        type="button"
                        href="login"
                        class="flex items-center text-white bg-green-700 mr-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                    >
                        <svg
                            class="w-4 h-4 mr-2 text-white dark:text-white"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 14 18"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 8a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm-2 3h4a4 4 0 0 1 4 4v2H1v-2a4 4 0 0 1 4-4Z"
                            />
                        </svg>
                        Вход
                    </Link>

                    <Link
                        type="button"
                        class="flex items-center text-white bg-green-700 mr-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                    >
                        <svg
                            class="w-4 h-4 mr-2 text-white dark:text-white"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 20 18"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 8h6m-3 3V5m-6-.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0ZM5 11h3a4 4 0 0 1 4 4v2H1v-2a4 4 0 0 1 4-4Z"
                            />
                        </svg>
                        Регистрация
                    </Link>
                    <button
                        data-collapse-toggle="navbar-cta"
                        type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                        aria-controls="navbar-cta"
                        aria-expanded="false"
                    >
                        <span class="sr-only">Open main menu</span>
                        <svg
                            class="w-5 h-5"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 17 14"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M1 1h15M1 7h15M1 13h15"
                            />
                        </svg>
                    </button>
                </div>
                <div
                    class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1"
                    id="navbar-cta"
                >
                    <ul
                        class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-slate-900 md:space-x-10 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-slate-900 dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700"
                    >
                        <li class="group/main inline-block relative">
                            <a
                                href="#"
                                class="flex items-center py-2 px-3 md:p-0 text-white rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:dark:hover:text-blue-500 text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                                >Категории<svg
                                    class="w-2.5 h-2.5 ms-1"
                                    aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 10 6"
                                >
                                    <path
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m1 1 4 4 4-4"
                                    /></svg
                            ></a>
                            <ul
                                class="flex items-center absolute hidden z-50 pt-2 border-2 bg-white rounded-[15px] border-2 w-[250px] group-hover/main:block"
                            >
                                <li
                                    class="text-black bg-white border-b-2 hover:bg-black hover:text-white"
                                >
                                    1
                                </li>
                                <li
                                    class="text-black bg-white border-b-2 hover:bg-black hover:text-white"
                                >
                                    2
                                </li>
                                <li
                                    class="text-black bg-white border-b-2 hover:bg-black hover:text-white"
                                >
                                    3
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a
                                href="#"
                                class="block py-2 px-3 md:p-0 text-white rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                                aria-current="page"
                                >Новости</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="block py-2 px-3 md:p-0 text-white rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 d:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                                >О нас</a
                            >
                        </li>
                        <li class="group/main inline-block relative">
                            <a
                                href="#"
                                class="flex items-center py-2 px-3 md:p-0 text-white rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:dark:hover:text-blue-500 text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                                >Контакты<svg
                                    class="w-2.5 h-2.5 ms-1"
                                    aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 10 6"
                                >
                                    <path
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m1 1 4 4 4-4"
                                    /></svg
                            ></a>
                            <div
                                class="flex absolute flex-col justify-between bg-white w-[250px] rounded-[15px] border-b p-6 hidden z-50 group-hover/main:block"
                            >
                                <div
                                    class="flex justify-between mb-4 border-b-2"
                                >
                                    <img
                                        src="../../../../../public/A1.jpg"
                                        alt=""
                                    />
                                    <span class="text-sm"
                                        >+375 29 630-85-35</span
                                    >
                                </div>
                                <div
                                    class="flex justify-between mb-4 border-b-2"
                                >
                                    <img
                                        src="../../../../../public/gmail.png"
                                        class="w-[16px] h-[16px]"
                                        alt=""
                                    />
                                    <span class="text-xs">
                                        AMIAUTO.MINSK@GMAIL.COM</span
                                    >
                                </div>
                                <div
                                    class="flex justify-between mb-4 border-b-2"
                                >
                                    <img
                                        src="../../../../../public/skype.png"
                                        class="w-[16px] h-[16px]"
                                        alt=""
                                    />
                                    <span class="text-sm">AMI.AUTO</span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a
                                href="#"
                                class="block py-2 px-3 md:p-0 text-white rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                                >Как найти</a
                            >
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="max-w-screen-xl mx-auto p-6">
            <form>
                <div class="flex flex-wrap">
                    <div class="relative w-[60%]">
                        <input
                            type="search"
                            id="search-dropdown"
                            class="block p-2.5 w-full z-20 text-md text-gray-900 bg-gray-50 rounded-e-lg rounded-s-gray-100 rounded-s-2 rounded-tl-[15px] rounded-bl-[15px] border focus:ring-0 border-gray-300"
                            placeholder="Поиск"
                            required
                        />
                        <button
                            type="submit"
                            class="absolute top-0 end-0 p-2.5 h-full text-sm font-medium text-white bg-green-700 rounded-e-lg border border-green-700 hover:bg-green-800 focus:outline-none"
                        >
                            <svg
                                class="w-4 h-4"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
                                />
                            </svg>
                        </button>
                    </div>
                    <div class="flex space-x-2 ml-[80px]">
                        <svg
                            class="w-6 h-6 text-white dark:text-white"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 20 20"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 6v4l3.276 3.276M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                            />
                        </svg>
                        <p class="text-white">
                            Мы работаем: пн-пт - 9:00-18:00
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </header>
</template>
<script setup>
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";
import { Link } from "@inertiajs/inertia-vue3";
onMounted(() => {
    initFlowbite();
});
</script>
