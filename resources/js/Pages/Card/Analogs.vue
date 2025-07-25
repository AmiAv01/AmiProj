<template>
    <div class="overflow-x-auto w-full">  <!-- Добавлен w-full -->
        <table class="min-w-full divide-y divide-gray-200 w-full">  <!-- Добавлен w-full -->
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 min-w-[200px]">Артикул</th>  <!-- Увеличен min-width -->
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 min-w-[150px]">Бренд</th>    <!-- Увеличен min-width -->
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 min-w-[120px]">Наличие</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="detail in details" :key="detail.dt_id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">  <!-- Увеличен padding -->
                        <a 
                            :href="`../../catalog/product/${detail.dt_invoice}`"
                            class="text-blue-600 hover:underline text-base"  <!-- Увеличен размер текста -->
                        >
                            {{ detail.dt_invoice }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-base">  <!-- Увеличен padding и размер текста -->
                        {{ detail.fr_code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span v-if="detail.ostc" class="text-green-500 text-base">В наличии</span>  <!-- Увеличен размер текста -->
                        <span v-else class="text-red-500 text-base">Нет в наличии</span>  <!-- Увеличен размер текста -->
                    </td>
                </tr>
                <tr v-if="details === undefined || !details.length">
                    <td colspan="3" class="px-6 py-4 text-center text-gray-400 text-base">  <!-- Увеличен padding и размер текста -->
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