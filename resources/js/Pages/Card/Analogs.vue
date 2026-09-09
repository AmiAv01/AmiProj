<template>
    <div class="w-full overflow-x-auto">
        <table class="w-full min-w-full border-separate border-spacing-0">
            <thead class="sticky top-0 z-10 bg-white">
            <tr>
                <th class="min-w-[130px] border-b border-slate-200 px-3 py-3 text-left text-base font-semibold text-slate-600">Артикул</th>
                <th class="min-w-[110px] border-b border-slate-200 px-3 py-3 text-left text-base font-semibold text-slate-600">Бренд</th>
                <th class="min-w-[100px] border-b border-slate-200 px-3 py-3 text-left text-base font-semibold text-slate-600">Наличие</th>
                <th class="w-8 border-b border-slate-200 px-2 py-3"><span class="sr-only">Открыть</span></th>
            </tr>
            </thead>
            <tbody class="bg-white">
            <tr
                v-for="detail in details"
                :key="detail.dt_id"
                class="group cursor-pointer transition-colors hover:bg-blue-50/60 focus-within:bg-blue-50/60"
                role="link"
                tabindex="0"
                @click="openProduct(detail.dt_invoice)"
                @keydown.enter="openProduct(detail.dt_invoice)"
                @keydown.space.prevent="openProduct(detail.dt_invoice)"
            >
                <td class="whitespace-nowrap border-b border-slate-100 px-3 py-3.5">
                    <a
                        :href="`../../catalog/product/${detail.dt_invoice}`"
                        class="text-base font-bold text-blue-600 hover:text-blue-700 hover:underline focus:outline-none"
                    >
                        {{ detail.dt_invoice }}
                    </a>
                </td>
                <td class="whitespace-nowrap border-b border-slate-100 px-3 py-3.5 text-base font-medium text-slate-700">
                    {{ detail.fr_code }}
                </td>
                <td class="whitespace-nowrap border-b border-slate-100 px-3 py-3.5 text-base">
                    <span v-if="detail.ostc" class="inline-flex items-center gap-1.5 font-semibold text-emerald-600">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>Есть
                    </span>
                    <span v-else class="inline-flex items-center gap-1.5 font-medium text-rose-500">
                        <span class="h-2 w-2 rounded-full bg-rose-400"></span>Нет
                    </span>
                </td>
                <td class="border-b border-slate-100 px-2 py-3.5 text-slate-300 transition-colors group-hover:text-blue-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" />
                    </svg>
                </td>
            </tr>
            <tr v-if="details === undefined || !details.length">
                <td colspan="4" class="px-4 py-10 text-center text-base text-slate-400">
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
const openProduct = (invoice) => {
    window.location.href = `../../catalog/product/${invoice}`;
};
</script>
