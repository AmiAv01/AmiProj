<template>
    <div>
        <div class="grid grid-cols-3 bg-gray-100 p-3 font-semibold border-b">
            <div class="text-left">Артикул</div>
            <div class="text-left">Бренд</div>
            <div class="text-left">Наличие</div>
        </div>
        
        <div 
            v-for="detail in details" 
            :key="detail.dt_id"
            class="grid grid-cols-3 p-3 border-b hover:bg-gray-50"
        >
            <div class="flex items-center">
                <a 
                    :href="`../../catalog/product/${detail.dt_invoice}`"
                    class="text-blue-600 hover:underline text-sm sm:text-base"
                >
                    {{ detail.dt_invoice }}
                </a>
            </div>
            
            <div class="flex items-center text-sm sm:text-base">
                {{ detail.fr_code }}
            </div>
            
            <div class="flex items-center text-sm sm:text-base">
                <span v-if="detail.ostc" class="text-green-500">Есть в наличии</span>
                <span v-else class="text-red-500">Нет в наличии</span>
            </div>
        </div>
        <div class="bg-white w-full p-4 flex text-lg text-gray-400 items-center justify-center" v-if="details === undefined || !details.length">
            <p>Запчастей не найдено</p>
        </div>
    </div>
</template>

<script setup>

import { editDetailTitle } from "@/Services/TitleService";

const props = defineProps({
    details: Array,
})
console.log([props.details]);
const editTitle = (res) => editDetailTitle(res)

</script>
