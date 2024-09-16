<template>
    <push v-if="isShow" :isShow="isShow" @hide="hideModal" :title="`Успешно добавлено в корзину`">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"  stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    </push>
    <layout :title="title">
        <div class="bg-white flex ml-10 py-24 sm:py-32">
            <brand-selector
                :categories="categories.brands"
                :clientBrands="clientBrands"
            />
            <div class="ml-[150px] max-w-7xl">
                <div class="mx-auto max-w-2xl lg:mx-0">
                    <p class="text-5xl font-bold tracking-tight text-gray-900">
                        {{ title }}
                    </p>
                    <div
                        class="mt-12 grid grid-cols-1 gap-x-6 gap-y-10 border-t w-[1300px] border-gray-200"
                    >
                        <catalog-card
                            @showPush="showModal"
                            class="grid grid-cols-7 w-full pb-6 border-b border-gray-100 group mt-12"
                            v-for="detail in details.data"
                            :key="detail.dt_id"
                            :detail="detail"
                        />
                    </div>
                </div>
                <pagination :links="details.links"  />
            </div>
        </div>
    </layout>
</template>

<script>
import CatalogCard from "@/Pages/CatalogCard/CatalogCard.vue";
import BrandSelector from "@/Shared/BrandSelector/Index.vue";
export default {
    components: {
        "catalog-card": CatalogCard,
        "brand-selector": BrandSelector,
    },
    data() {
        return {
            checked: [],
            isShow: false,
            selectedDetails: this.details,
        };
    },
    methods: {
        hideModal(param){
            console.log(param);
            this.isShow = param;
        },
        showModal(param){
            console.log(param);
            this.isShow = param;
        },
    },
};
</script>

<script setup>
defineProps({
    details: Object,
    title: String,
    categories: Object,
    clientBrands: Object,
});
</script>
