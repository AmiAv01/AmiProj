<template>
    <push v-if="isShow" :isShow="isShow" @hide="hideModal" :title="`Успешно добавлено в корзину`">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"  stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    </push>
    <layout :title="title">
        <div class="bg-white w-full justify-around max-w-7xl flex py-12 px-12 lg:flex-row flex-row-reverse flex-wrap">
            <BrandFilter
                @closeModal="closeBrandFilter"
                :is-show="showBrandSelector"
                :is-mobile="showMobileFilter"
                :categories="categories.brands"
                :clientBrands="clientBrands"
            />
            <div class="ml-12">
                <div class="max-w-7xl relative">
                    <p class="text-5xl font-bold tracking-tight text-gray-900 mb-10">
                        {{ title }}
                    </p>
                    <button @click="toggleMobile" v-show="showFilterButton" class=" align-self-start z-30 sm:top-0 rounded-xl bg-green-600 hover:bg-green-700  text-white border-2 border-gray-300 w-[200px] h-[50px] ">Фильтр</button>
                    <div v-if="details.data"
                        class="w-full   px-4 lg:p-0 border-gray-200"
                    >
                        <CatalogItem
                            @showPush="showModal"
                            v-for="detail in details.data"
                            :key="detail.dt_id"
                            :detail="detail"
                        />
                    </div>
                    <div v-else
                         class="mt-12 grid grid-cols-1 gap-x-6 gap-y-10 border-t w-[1300px] border-gray-200"
                    >
                        <p class="text-center mt-12 text-5xl text-gray-500">По данному запросу запчастей не найдено</p>
                    </div>
                </div>
                <pagination :links="details.links"  />
            </div>
        </div>
    </layout>
</template>

<script setup>
import CatalogItem from "@/Pages/Catalog/CatalogItem.vue";
import {onMounted, ref} from "vue";
import BrandFilter from "@/Shared/Filters/BrandFilter.vue";

const props = defineProps({
    details: Object,
    title: String,
    categories: Object,
    clientBrands: Object,
});

const checked = ref([]);
let showBrandSelector = ref(false);
let showFilterButton = ref(false);
const isShow = ref(false);
const showMobileFilter = ref(false);
const selectedDetails = ref(props.details);

onMounted(() => {
    window.addEventListener('resize', handleWindowResize);
    handleWindowResize();
});

const handleWindowResize = () => {
    showBrandSelector.value = (window.innerWidth >= 1124);
    showFilterButton.value = (window.innerWidth <= 1124);
    showMobileFilter.value = (window.innerWidth <=1124);
}

const hideModal = (param) => isShow.value = param;

const showModal = (param) => isShow.value = param;
const toggleMobile = () => {
    showBrandSelector.value = !showBrandSelector.value;
}

const closeBrandFilter = () => {
    console.log(isShow.value);
    showBrandSelector.value = false;
}
</script>

