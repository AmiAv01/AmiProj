<template>
    <section class="bg-white py-16 sm:py-20">
        <div class="mx-auto w-full max-w-7xl px-4 md:px-5 lg:px-6">
            <div class="flex items-end justify-between gap-6">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.18em] text-green-700">Каталог</p>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        Наши запчасти
                    </h2>
                </div>
                <a href="/catalog/starters" class="hidden text-base font-semibold text-green-700 hover:text-green-800 sm:inline-flex sm:items-center">
                    Смотреть каталог
                    <span class="ml-2" aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <ProductItem v-for="detail in details.data" :key="detail.dt_id" :detail="detail" />
            </div>

            <div class="mt-10">
                <pagination :links="details.links" />
            </div>
        </div>
    </section>
</template>

<script setup>
import ProductItem from "@/Pages/User/ProductItem.vue";

defineProps({
    details: Object,
});


</script>
