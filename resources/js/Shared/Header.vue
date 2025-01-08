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
                        src="../../../public/logo2.png"
                        class="h-8"
                        alt="AmiAvto Logo"
                    />
                </a>
                <div
                    v-if="!$page.props.auth.user"
                    class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse"
                >
                    <menu-button
                        :attributes="`px-4 py-2 text-sm mr-2`"
                        :href="route('login')"
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
                    </menu-button>

                    <menu-button
                        :attributes="`px-4 py-2 text-sm`"
                        :href="route('register')"
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
                    </menu-button>
                </div>
                <div
                    v-else
                    class="flex w-[260px] justify-between items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse"
                >
                    <button
                        type="button"
                        class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                        id="user-menu-button"
                        aria-expanded="false"
                        data-dropdown-toggle="user-dropdown"
                        data-dropdown-placement="bottom"
                    >
                        <span class="sr-only">Open user menu</span>
                        <i class="fa-solid fa-user text-white text-xl"></i>
                    </button>
                    <!-- Dropdown menu -->
                    <div
                        class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                        id="user-dropdown"
                    >
                        <div class="px-4 py-3">
                            <span
                                class="block text-sm text-gray-900 dark:text-white"
                                >{{ $page.props.auth.user.name }}</span
                            >
                            <span
                                class="block text-sm text-gray-500 truncate dark:text-gray-400"
                                >{{ $page.props.auth.user.email }}</span
                            >
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <inertia-link
                                    :href="route('profile.edit')"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                                    >Настройки</inertia-link
                                >
                            </li>
                            <li>
                                <inertia-link
                                    :href="route('logout')"
                                    method="post"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                                    >Выход</inertia-link
                                >
                            </li>
                        </ul>
                    </div>
                    <menu-button
                        :attributes="`px-4 py-2 text-sm mr-4`"
                        :href="route('cart.index')"
                    >
                        <svg
                            class="w-4 h-4 mr-2 fill-white"
                            viewBox="0 0 576 512"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"
                            ></path>
                        </svg>
                        Корзина
                    </menu-button>
                    <menu-button
                        :attributes="`px-4 py-2 text-sm ml-4`"
                        :href="route('order.index')"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 mr-2 fill-white"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#000000"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M6 2L3 6v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V6l-3-4H6zM3.8 6h16.4M16 10a4 4 0 1 1-8 0"
                            />
                        </svg>
                        Заказы
                    </menu-button>
                </div>
                <div
                    class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1"
                    id="navbar-cta"
                >
                    <ul
                        class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-slate-900 md:space-x-10 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-slate-900 dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700"
                    >
                        <li class="pr-6">
                            <inertia-link
                                :href="route('news.index')"
                                class="block py-2 px-3 md:p-0 text-white rounded text-lg hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                                aria-current="page"
                                >Новости</inertia-link
                            >
                        </li>
                        <li class="pr-6">
                            <inertia-link
                                :href="route('info.index')"
                                class="block py-2 px-3 md:p-0 text-white rounded text-lg hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 d:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                                >О нас</inertia-link
                            >
                        </li>
                        <li class="pr-6">
                            <inertia-link
                                :href="route('desktop.index')"
                                class="block py-2 px-3 md:p-0 text-white rounded text-lg hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 d:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                            >Скачать</inertia-link
                            >
                        </li>
                        <li class="pr-6 group/main group inline-block relative">
                            <a
                                href="#"
                                class="flex items-center py-2 px-3 md:p-0 rounded text-lg hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:dark:hover:text-blue-500 text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                                >Контакты<svg
                                    class="w-2.5 h-2.5 ms-1 group-hover:rotate-180"
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
                                class="absolute flex-col justify-between bg-white w-[280px] rounded-[15px] border-b p-5 hidden z-50 group-hover/main:block"
                            >
                                <div
                                    class="flex justify-between mb-4 py-2 border-b-2"
                                >
                                    <img
                                        src="../../../public/A1.jpg"
                                        alt=""
                                        class="w-[24px] h-[24px]"
                                    />
                                    <span class="font-semibold"
                                        >+375 29 630-85-35</span
                                    >
                                </div>
                                <div
                                    class="flex justify-between mb-4 py-2 border-b-2"
                                >
                                    <img
                                        src="../../../public/gmail.png"
                                        class="w-[24px] h-[24px]"
                                        alt=""
                                    />
                                    <span class="font-semibold">
                                        amiauto.minsk@gmail.com</span
                                    >
                                </div>
                                <div
                                    class="flex justify-between py-2 border-b-2"
                                >
                                    <img
                                        src="../../../public/skype.png"
                                        class="w-[24px] h-[24px]"
                                        alt=""
                                    />
                                    <span class="font-semibold">ami.auto</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="max-w-screen-xl mx-auto px-6 py-5">
            <Search :link="`../api/search?searchQ`" />
        </div>
        <NavMenu />
    </header>
</template>

<script setup>
import NavMenu from "@/Shared/Nav/NavMenu.vue";
import Search from "./Search.vue";
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";

onMounted(() => {
    initFlowbite();
});
defineProps({});
</script>

