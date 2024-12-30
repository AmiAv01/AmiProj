<template>
    <nav
        class="bg-black border-b border-gray-200 px-4 py-2.5 dark:bg-gray-800 dark:border-gray-700 fixed left-0 right-0 top-0 z-50"
    >
        <div class="flex flex-wrap justify-between items-center">
            <div class="flex justify-start items-center">
                <Link
                    :href="route('admin.dashboard')"
                    class="flex items-center justify-between mr-4"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-6 h-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                        />
                    </svg>
                    <img
                        src="../../../../../public/logo2.png"
                        class="h-8"
                        alt="AmiAvto Logo"
                    />
                </Link>
            </div>
            <div
                class="flex w-[260px] justify-between items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse"
            >
                <button
                    type="button"
                    class="flex text-sm bg-gray-800 rounded-full"
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
        </div>
    </nav>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import {initFlowbite} from "flowbite";
import {onMounted} from "vue";

onMounted(() => {
    initFlowbite();
});
</script>
