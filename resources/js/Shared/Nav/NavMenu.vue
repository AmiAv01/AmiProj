<template>
    <div class="bg-green-700 w-full">
        <ul class="flex w-[68%] relative mx-auto my-0">
            <li
                class="flex justify-center items-center hover:bg-green-900 cursor-pointer"
            >
                <inertia-link
                    href="/catalog/generators"
                    class="text-white px-12 py-2"
                >
                    Генераторы
                </inertia-link>
            </li>
            <li
                class="flex justify-center items-center hover:bg-green-900 cursor-pointer"
            >
                <inertia-link
                    href="/catalog/starters"
                    class="text-white px-12 py-2"
                >
                    Стартеры
                </inertia-link>
            </li>
            <li
                class="flex group/main group items-center px-12 py-2 hover:bg-green-900 cursor-pointer"
            >
                <p class="text-white">Запчасти стартера</p>
                <svg
                    class="w-2.5 h-2.5 ms-1 text-white group-hover:rotate-180"
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
                    />
                </svg>
                <ul
                    class="absolute top-[100%] left-0 flex-col w-full justify-between bg-slate-900 rounded-[15px] border-b p-3 hidden z-50 group-hover/main:grid grid-cols-4 gap-4"
                >
                    <MenuItem v-for="[title, link] in starterParts"
                              :value="`${title}`"
                              :link="`${link}`"
                    />
                </ul>
            </li>
            <li
                class="flex group/main group items-center px-12 py-2 hover:bg-green-900 cursor-pointer"
            >
                <p class="text-white">Запчасти генератора</p>
                <svg
                    class="w-2.5 h-2.5 ms-1 text-white group-hover:rotate-180"
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
                    />
                </svg>
                <ul
                    class="absolute top-[100%] left-0 flex-col w-full justify-between bg-slate-900 rounded-[15px] border-b p-3 hidden z-50 group-hover/main:grid grid-cols-4 gap-4"
                >
                    <MenuItem v-for="[title, link] in generatorParts"
                        :value="`${title}`"
                        :link="`${link}`"
                    />
                </ul>
            </li>
            <li
                class="flex justify-center items-center group hover:bg-green-900 cursor-pointer"
            >
                <inertia-link
                    href="/catalog/bearings"
                    class="text-white px-12 py-2"
                    >Подшипники</inertia-link
                >
            </li>
            <li
                class="flex justify-center items-center hover:bg-green-900 cursor-pointer"
            >
                <inertia-link
                    href="/catalog/other"
                    class="text-white px-12 py-2"
                    >Прочие запчасти</inertia-link
                >
            </li>
        </ul>
    </div>
</template>

<script setup>
import MenuItem from "@/Shared/Nav/NavMenuItem.vue";
import {starterParts, generatorParts} from "@/Store/index.js";
</script>
