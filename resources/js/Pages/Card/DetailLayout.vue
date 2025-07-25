<template>
    <div class="rounded-lg border-2 w-full">
        <p class="text-2xl px-4 py-4 text-center border-b-2">
            <strong>Деталировка</strong>
        </p>
        <div class="overflow-x-auto max-h-[400px]">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Название</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Артикул</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Бренд</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(item, index) in details" :key="index" class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ item.dt_typec }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                            {{ item.dt_invoice }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                            {{ item.fr_code }}
                        </td>
                    </tr>
                    <tr v-if="!details.length">
                        <td colspan="3" class="px-4 py-4 text-center text-gray-400 text-sm">
                            Детализация отсутствует
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
defineProps({
    details: {
        type: Array,
        required: true
    }
});
</script>