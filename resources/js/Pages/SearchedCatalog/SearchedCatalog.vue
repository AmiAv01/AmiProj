<template>
    <layout :title="title">
        <div class="w-full bg-white">
            <div class="mx-auto grid max-w-[1600px] grid-cols-1 gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[280px_minmax(0,1fr)] lg:px-8">
                <aside class="lg:sticky lg:top-6 lg:self-start">
                    <BrandFilter
                        :is-show="showBrandSelector"
                        :is-mobile="showMobileFilter"
                        :categories="categories.brands"
                        :client-brands="clientBrands"
                        @closeModal="closeBrandFilter"
                    />
                </aside>

                <main class="min-w-0">
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900 md:text-4xl">
                            {{ title }}
                        </h1>
                        <span
                            v-if="details.total"
                            class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-sm font-semibold text-green-800"
                        >
                            Найдено: {{ details.total }}
                        </span>
                    </div>

                    <button
                        v-show="showFilterButton"
                        type="button"
                        class="mb-6 inline-flex h-11 items-center justify-center rounded-xl border border-green-700 bg-green-700 px-6 font-semibold text-white transition hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-300"
                        @click="toggleMobile"
                    >
                        Фильтр
                    </button>

                    <div v-if="details.data?.length" class="w-full space-y-3">
                        <SearchedCatalogItem
                            v-for="detail in details.data"
                            :key="`${detail.dt_code}-${detail.dt_firm}`"
                            :detail="detail"
                        />
                    </div>

                    <div v-else class="mt-8 w-full rounded-xl border border-dashed border-gray-300 bg-gray-50 px-6 py-12">
                        <p class="text-center text-xl font-medium text-gray-500 md:text-2xl">
                            По данному запросу запчастей не найдено
                        </p>
                    </div>

                    <pagination :links="details.links" class="mt-10" />
                </main>
            </div>
        </div>
    </layout>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from "vue";
import BrandFilter from "@/Shared/Filters/BrandFilter.vue";
import SearchedCatalogItem from "@/Pages/SearchedCatalog/SearchedCatalogItem.vue";

defineProps({
    details: {
        type: Object,
        default: () => ({ data: [], links: [], total: 0 }),
    },
    title: String,
    categories: {
        type: Object,
        default: () => ({ brands: [] }),
    },
    clientBrands: {
        type: Object,
        default: null,
    },
});

const showBrandSelector = ref(false);
const showFilterButton = ref(false);
const showMobileFilter = ref(false);

const handleWindowResize = () => {
    showBrandSelector.value = window.innerWidth >= 1124;
    showFilterButton.value = window.innerWidth < 1124;
    showMobileFilter.value = window.innerWidth < 1124;
};

const toggleMobile = () => {
    showBrandSelector.value = !showBrandSelector.value;
};

const closeBrandFilter = () => {
    showBrandSelector.value = false;
};

onMounted(() => {
    window.addEventListener('resize', handleWindowResize);
    handleWindowResize();
});

onUnmounted(() => window.removeEventListener('resize', handleWindowResize));
</script>
