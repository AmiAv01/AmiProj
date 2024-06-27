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
                    <inertia-link
                        :href="route('login')"
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
                    </inertia-link>

                    <inertia-link
                        :href="route('register')"
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
                    </inertia-link>
                </div>
                <div
                    v-else
                    class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse"
                >
                    <button
                        type="button"
                        class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
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
            <search />
        </div>
        <NavMenu />
    </header>
</template>

<script setup>
onMounted(() => {
    initFlowbite();
});
defineProps({});
</script>

<script>
import Search from "./Search.vue";
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";
import NavMenu from "./Nav/NavMenu.vue";

export default {
    components: {
        search: Search,
    },
};
</script>
