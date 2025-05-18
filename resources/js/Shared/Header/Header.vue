<template>
    <header class="bg-slate-900 top-0 z-40 ">
        <nav class="bg-slate-900 border-gray-200 dark:bg-gray-900">
            <div
                class="flex flex-wrap items-center justify-around mx-auto my-0 p-6"
            >
                <a
                    href="/"
                    class="flex items-center space-x-3 rtl:space-x-reverse"
                >
                    <img
                        src="/logo2.png"
                        class="h-8"
                        alt="AmiAvto Logo"
                    />
                </a>
                <div
                    v-if="!$page.props.auth.user"
                    class="flex md:order-2 mt-2 sm:mt-0 space-x-3 md:space-x-0 rtl:space-x-reverse"
                >
                    <GuestMenu/>
                </div>
                <div
                    v-else
                    class="flex w-[260px] justify-between items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse"
                >
                    <AuthMenu/>
                </div>
                <div
                    class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1"
                    id="navbar-cta"
                >
                    <ul
                        class="lg:flex flex-col font-medium p-4 md:p-0   border border-gray-100 rounded-lg bg-slate-900 md:space-x-10 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 hidden dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700"
                    >
                        <li class="pr-6" v-for="[key, value] of menuItems">
                            <inertia-link
                                :href="`${value}`"
                                class="block py-2 px-3 md:p-0 text-white rounded text-lg hover:bg-gray-100 md:hover:bg-transparent md:hover:text-green-700 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
                            >{{key}}</inertia-link
                            >
                        </li>
                        <li class="pr-6 group/main group inline-block relative">
                            <Contacts/>
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
import Search from "../Search.vue";
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";
import Contacts from "@/Shared/Header/Contacts.vue";
import GuestMenu from "@/Shared/Header/GuestMenu.vue";
import AuthMenu from "@/Shared/Header/AuthMenu.vue";
import {menuItems} from "@/Store/index.js";

onMounted(() => {
    initFlowbite();
});



</script>
