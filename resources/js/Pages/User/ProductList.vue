<template>
    <div class="bg-white">
        <div class="mx-auto w-[1300px] py-16 sm:py-16">
            <p class="text-5xl mb-10 font-bold tracking-tight text-gray-900">
                Наши запчасти
            </p>

            <div
                class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8"
            >
                <ProductItem v-for="detail in details.data" :detail="detail"/>
            </div>
            <pagination :links="details.links" />
        </div>
    </div>
</template>

<script setup>
import { editDetailTitle } from "@/Services/TitleService";
import ProductItem from "@/Pages/User/ProductItem.vue";

const props = defineProps({
    details: Object,
});


</script>

