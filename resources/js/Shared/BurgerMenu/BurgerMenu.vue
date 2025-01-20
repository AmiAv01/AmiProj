<script setup>
    import {ref} from "vue";
    import {generatorParts, starterParts} from "@/Store/index.js";
    import {subCategories, menuItems} from "@/Store/index.js";
    import BurgerMenuItem from "@/Shared/BurgerMenu/BurgerMenuItem.vue";

    const props = defineProps({
        isOpen: Boolean
    })

    const isShowCategory = ref(false);
    const isStarterShowCategory = ref(false);
    const isGeneratorShowCategory = ref(false);

    const toggleCategory = () => {
        isShowCategory.value = !isShowCategory.value;
    }
    const toggleDownStarterCategory = () => {
        isStarterShowCategory.value = !isStarterShowCategory.value;
        isShowCategory.value = !isShowCategory.value
    }

    const toggleDownGeneratorCategory = () => {
        isGeneratorShowCategory.value = !isGeneratorShowCategory.value;
        isShowCategory.value = !isShowCategory.value
    }

</script>

<template>
    <div class="absolute overflow-y-scroll left-[30%] top-[30px] h-[100vh] bg-slate-900 w-[70%] md:w-[50%] md:left-[50%] b-0 z-40" v-show="isOpen">
        <ul class="pt-[40px] ">
            <li @click="toggleCategory" class="w-full flex flex-col border-b-2 border-white  items-start cursor-pointer justify-center p-6 text-white text-xl">
                <div class="flex justify-between w-full items-center">
                    <span>Каталог</span>
                    <svg :class="isShowCategory ? 'text-white h-6 w-6' : 'text-white h-6 w-6 rotate-180'" viewBox="0 0 24 24" fill="FFF" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 6V18M12 6L7 11M12 6L17 11" stroke="#FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <ul :class=" { 'hidden' : !isShowCategory, 'block': isShowCategory }">
                    <BurgerMenuItem :title="key" :link="value" v-for="[key, value] of subCategories"/>
                    <li @click="toggleDownStarterCategory" class="w-full flex flex-col border-b-2 border-white items-start cursor-pointer justify-center p-6 text-white text-xl">
                        <div class="flex justify-between  items-center">
                            <span>Запчасти стартера</span>
                            <svg :class="isStarterShowCategory ? 'text-white h-6 w-6' : 'text-white h-6 w-6 rotate-180'" viewBox="0 0 24 24" fill="FFF" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 6V18M12 6L7 11M12 6L17 11" stroke="#FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <ul :class=" { 'hidden' : !isStarterShowCategory, 'block': isStarterShowCategory }">
                            <BurgerMenuItem :title="key" :link="value"  v-for="[key, value] of starterParts"/>
                        </ul>
                    </li>
                    <li @click="toggleDownGeneratorCategory" class="w-full flex flex-col border-b-2 border-white items-start cursor-pointer justify-center p-6 text-white text-xl">
                        <div class="flex justify-between  items-center">
                            <span>Запчасти генератора</span>
                            <svg :class="isGeneratorShowCategory ? 'text-white h-6 w-6' : 'text-white h-6 w-6 rotate-180'" viewBox="0 0 24 24" fill="FFF" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 6V18M12 6L7 11M12 6L17 11" stroke="#FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <ul :class=" { 'hidden' : !isGeneratorShowCategory, 'block': isGeneratorShowCategory }">
                            <BurgerMenuItem :title="key" :link="value"  v-for="[key, value] of generatorParts"/>
                        </ul>
                    </li>
                </ul>
            </li>
            <BurgerMenuItem v-for="[key, value] of menuItems" :title="key" :link="value"/>
        </ul>
    </div>
</template>

<style scoped>

</style>
