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
        <div v-else  class="flex w-full justify-center">
            <ul class="flex flex-wrap w-full text-center justify-center items-center relative">
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
        <BurgerMenu :is-open="isBurgerMenuOpen"/>
    </div>
</template>

<script setup>
import MenuItem from "@/Shared/Nav/NavMenuItem.vue";
import {starterParts, generatorParts} from "@/Store/index.js";
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



