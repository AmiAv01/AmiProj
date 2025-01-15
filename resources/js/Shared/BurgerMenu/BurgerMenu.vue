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
    const toggleDownCategory = () => {
        isStarterShowCategory.value = !isStarterShowCategory.value;
        isGeneratorShowCategory.value = !isGeneratorShowCategory.value;
        isShowCategory.value = !isShowCategory.value
    }

</script>

<template>
    <div class="absolute left-[30%] top-[32px] h-[100vh] bg-slate-900 w-[70%] md:w-[50%] md:left-[50%] b-0 z-40" v-show="isOpen">
        <ul class="pt-[50px]">
            <li @click="toggleCategory" class="w-full flex flex-col border-b-2 border-white  items-start cursor-pointer justify-center p-6 text-white text-xl">
                <span>Каталог</span>
                <ul :class=" { 'hidden' : !isShowCategory, 'block': isShowCategory }">
                    <BurgerMenuItem :title="key" :link="value" v-for="[key, value] of subCategories"/>
                    <li @click="toggleDownCategory" class="w-full flex flex-col border-b-2 border-white items-start cursor-pointer justify-center p-6 text-white text-xl">
                        <span>Запчасти стартера</span>
                        <ul :class=" { 'hidden' : !isStarterShowCategory, 'block': isStarterShowCategory }">
                            <BurgerMenuItem :title="key" :link="value"  v-for="[key, value] of starterParts"/>
                        </ul>
                    </li>
                    <li @click="toggleDownCategory" class="w-full border-b-2 border-white flex items-center cursor-pointer justify-start p-6 text-white text-xl">
                        <span>Запчасти генератора</span>
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
