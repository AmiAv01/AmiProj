<template>
    <layout>
        <div class="bg-white flex ml-10 py-24 sm:py-32">
            <brand-selector :categories="categories.brands" />
            <div class="ml-[150px] max-w-7xl">
                <div class="mx-auto max-w-2xl lg:mx-0">
                    <p class="text-5xl font-bold tracking-tight text-gray-900">
                        {{ title }}
                    </p>
                    <div
                        class="mt-12 grid grid-cols-1 gap-x-6 gap-y-10 border-t w-[1300px] border-gray-200"
                    >
                        <catalog-card
                            class="grid grid-cols-7 w-full pb-6 border-b border-gray-100 group mt-12"
                            v-for="detail in details.data"
                            :key="detail.dt_id"
                            :detail="detail"
                        />
                    </div>
                </div>
                <Pagination :links="details.links" />
            </div>
        </div>
    </layout>
</template>

<script>
import UserLayout from "@/Shared/UserLayout.vue";
import Pagination from "@/Shared/Pagination.vue";
import CatalogCard from "@/Pages/CatalogCard/CatalogCard.vue";
import BrandSelector from "@/Shared/BrandSelector/Index.vue";
export default {
    components: {
        layout: UserLayout,
        "catalog-card": CatalogCard,
        "brand-selector": BrandSelector,
    },
    data() {
        return {
            checked: [],
            selectedDetails: this.details,
        };
    },
    computed: {
        selectedDetails: function () {
            console.log(this.selectedDetails.data);
            console.log(this.checked);
            if (this.checked.length === 0) {
                return this.details;
            } else {
                return this.selectedDetails.data.filter((item) => {
                    console.log(item.fr_code);
                    this.checked.includes(item.fr_code);
                });
            }
        },
    },
};
</script>

<script setup>
defineProps({ details: Object, title: String, categories: Array });
</script>
