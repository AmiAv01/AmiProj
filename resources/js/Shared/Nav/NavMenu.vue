<template>
    <div class="bg-green-700 flex w-full relative">
        <div v-if="!isMenuOpen" class="w-full flex justify-end">
            <button @click="toggleBurgerMenu" value="hamburger" class="group relative h-[30px] w-[30px] rounded mr-4 hover:bg-gray-400 ">
                <span :class="!isBurgerMenuOpen ? 'block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-white translate-y-[-8px]'
                      : 'block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-white  translate-y-0 -rotate-45'"></span>
                <span :class="!isBurgerMenuOpen ? 'block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-white'
                    : 'rotate-360 -translate-x-20px opacity-0 block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-white' "></span>
                <span :class="!isBurgerMenuOpen ? 'block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-white translate-y-[8px]'
                      : 'block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-white  translate-y-0 rotate-45'"></span>
            </button>
        </div>
        <div v-else class="flex w-full justify-center">
            <ul class="flex w-full max-w-7xl items-stretch justify-center text-center">
                <li
                    class="flex flex-1 items-center justify-center transition-colors hover:bg-green-800"
                >
                    <spa-link
                        href="/catalog/generators"
                        class="w-full px-5 py-3 text-base font-medium text-white/90 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                    >
                        Генераторы
                    </spa-link>
                </li>
                <li
                    class="flex flex-1 items-center justify-center transition-colors hover:bg-green-800"
                >
                    <spa-link
                        href="/catalog/starters"
                        class="w-full px-5 py-3 text-base font-medium text-white/90 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                    >
                        Стартеры
                    </spa-link>
                </li>
                <li
                    class="group/main flex flex-1 items-center justify-center transition-colors hover:bg-green-800 focus-within:bg-green-800"
                >
                    <button
                        type="button"
                        class="flex w-full items-center justify-center px-5 py-3 text-base font-medium text-white/90 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-haspopup="true"
                    >
                        <span>Запчасти стартера</span>
                        <svg
                            class="ms-1.5 h-2.5 w-2.5 transition-transform duration-200 group-hover/main:rotate-180 group-focus-within/main:rotate-180"
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
                    </button>
                    <ul
                        class="absolute left-1/2 top-full z-50 hidden w-[calc(100%-2rem)] max-w-6xl -translate-x-1/2 grid-cols-4 gap-2 rounded-b-2xl border border-gray-200 bg-white p-4 text-left shadow-2xl group-hover/main:grid group-focus-within/main:grid"
                    >
                        <li class="col-span-4 mb-1 flex items-center justify-between border-b border-gray-200 px-2 pb-3">
                            <div>
                                <p class="font-semibold text-gray-900">Запчасти стартера</p>
                                <p class="mt-0.5 text-xs text-gray-500">Выберите нужную категорию</p>
                            </div>
                            <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-800">
                                {{ starterParts.size }} категорий
                            </span>
                        </li>
                        <MenuItem
                            v-for="[title, link] in starterParts"
                            :key="link"
                            :value="`${title}`"
                            :link="`${link}`"
                        />
                    </ul>
                </li>
                <li
                    class="group/main flex flex-1 items-center justify-center transition-colors hover:bg-green-800 focus-within:bg-green-800"
                >
                    <button
                        type="button"
                        class="flex w-full items-center justify-center px-5 py-3 text-base font-medium text-white/90 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-haspopup="true"
                    >
                        <span>Запчасти генератора</span>
                        <svg
                            class="ms-1.5 h-2.5 w-2.5 transition-transform duration-200 group-hover/main:rotate-180 group-focus-within/main:rotate-180"
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
                    </button>
                    <ul
                        class="absolute left-1/2 top-full z-50 hidden w-[calc(100%-2rem)] max-w-6xl -translate-x-1/2 grid-cols-4 gap-2 rounded-b-2xl border border-gray-200 bg-white p-4 text-left shadow-2xl group-hover/main:grid group-focus-within/main:grid"
                    >
                        <li class="col-span-4 mb-1 flex items-center justify-between border-b border-gray-200 px-2 pb-3">
                            <div>
                                <p class="font-semibold text-gray-900">Запчасти генератора</p>
                                <p class="mt-0.5 text-xs text-gray-500">Выберите нужную категорию</p>
                            </div>
                            <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-800">
                                {{ generatorParts.size }} категорий
                            </span>
                        </li>
                        <MenuItem
                            v-for="[title, link] in generatorParts"
                            :key="link"
                            :value="`${title}`"
                            :link="`${link}`"
                        />
                    </ul>
                </li>
                <li
                    class="flex flex-1 items-center justify-center transition-colors hover:bg-green-800"
                >
                    <spa-link
                        href="/catalog/bearings"
                        class="w-full px-5 py-3 text-base font-medium text-white/90 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                    >Подшипники</spa-link
                    >
                </li>
                <li
                    class="flex flex-1 items-center justify-center transition-colors hover:bg-green-800"
                >
                    <spa-link
                        href="/catalog/other"
                        class="w-full px-5 py-3 text-base font-medium text-white/90 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                    >Прочие запчасти</spa-link
                    >
                </li>
            </ul>
        </div>
        <BurgerMenu :is-open="isBurgerMenuOpen"/>
    </div>
</template>

<script setup>
import MenuItem from "@/Shared/Nav/NavMenuItem.vue";
import {starterParts, generatorParts} from "@/Store/index";
import {onMounted, ref} from 'vue';
import BurgerMenu from "@/Shared/BurgerMenu/BurgerMenu.vue";

const isMenuOpen = ref(true);
const isBurgerMenuOpen = ref(false);
const innerWidth = ref(window.innerWidth);
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};
const toggleBurgerMenu = () => {
    isBurgerMenuOpen.value = !isBurgerMenuOpen.value
}
onMounted(() => {
    window.addEventListener('resize', handleWindowResize);
    handleWindowResize();
});

const handleWindowResize = () => {
    innerWidth.value = window.innerWidth;
    console.log(window.innerWidth)
    isMenuOpen.value = (innerWidth.value > 1260)
}

</script>

