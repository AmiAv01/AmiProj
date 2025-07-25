<template>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 min-w-[160px]">Артикул</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 min-w-[120px]">Бренд</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 min-w-[140px]">Наличие</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="detail in details" :key="detail.dt_id" class="hover:bg-gray-50">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <a 
                            :href="`../../catalog/product/${detail.dt_invoice}`"
                            class="text-blue-600 hover:underline text-sm sm:text-base break-all"
                        >
                            {{ detail.dt_invoice }}
                        </a>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm sm:text-base">
                        {{ detail.fr_code }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span v-if="detail.ostc" class="text-green-500 text-sm sm:text-base">Есть в наличии</span>
                        <span v-else class="text-red-500 text-sm sm:text-base">Нет в наличии</span>
                    </td>
                </tr>
                <tr v-if="details === undefined || !details.length">
                    <td colspan="3" class="px-4 py-4 text-center text-gray-400 text-sm">
                        Запчастей не найдено
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { editDetailTitle } from "@/Services/TitleService";

const props = defineProps({
    details: Array,
});

const editTitle = (res) => editDetailTitle(res);
</script>