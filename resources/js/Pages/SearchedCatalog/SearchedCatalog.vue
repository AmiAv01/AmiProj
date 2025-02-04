<template>
    <push v-if="isShow" :isShow="isShow" @hide="hideModal" :title="`Успешно добавлено в корзину`">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"  stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    </push>
    <layout :title="title">
        <div class="bg-white flex justify-around py-12 sm:py-12">
            <div>
                <div class="mx-auto max-w-xl ">
                    <p class="text-5xl font-bold tracking-tight text-gray-900 mb-10">
                        {{ title }}
                    </p>
                    <div v-if="details.data"
                         class=" grid grid-cols-1 gap-x-4 gap-y-2 w-[1200px]  border-gray-200"
                    >
                        <SearchedCatalogItem
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
            <BrandSelector
                :categories="categories.brands"
                :clientBrands="clientBrands"
            />
        </div>
    </layout>
</template>

<script setup>
import BrandSelector from "@/Shared/Filters/BrandFilter.vue";
import SearchedCatalogItem from "@/Pages/SearchedCatalog/SearchedCatalogItem.vue";
import {ref} from "vue";

const props = defineProps({
    details: Object,
    title: String,
    categories: Object,
    clientBrands: Object,
});

const checked = ref([]);
let isShow = ref(false);
const selectedDetails = ref(props.details());

const hideModal = (param) => isShow = param;

const showModal = (param) => isShow = param;

</script>


