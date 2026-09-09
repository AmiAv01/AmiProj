<template>
    <article class="grid grid-cols-1 gap-5 border-b border-gray-200 py-6 last:border-b-0 sm:grid-cols-[112px_minmax(0,1fr)] lg:grid-cols-[112px_minmax(0,1fr)_140px_160px] lg:items-center">
        <div class="flex h-28 w-28 items-center justify-center overflow-hidden rounded-xl bg-gray-50">
            <img
                src="/no-photo--lg.png"
                :alt="`Изображение товара ${item.dt_typec} ${item.dt_invoice}`"
                class="h-full w-full object-contain"
            />
        </div>

        <div class="min-w-0">
            <h3 class="text-lg font-semibold leading-7 text-gray-900">
                {{ item.dt_typec }} {{ item.dt_invoice }}
            </h3>
            <dl class="mt-3 space-y-1.5 text-base leading-7 text-gray-500">
                <div class="flex flex-wrap gap-x-1.5">
                    <dt>Артикул:</dt>
                    <dd class="font-medium text-gray-700">{{ item.cargo || '—' }}</dd>
                </div>
                <div class="flex flex-wrap gap-x-1.5">
                    <dt>Бренд:</dt>
                    <dd class="font-medium text-gray-700">{{ item.fr_code || '—' }}</dd>
                </div>
                <div class="flex flex-wrap gap-x-1.5">
                    <dt>Цена за шт.:</dt>
                    <dd class="font-medium text-gray-700">{{ formatMoney(item.unit_price) }}</dd>
                </div>
            </dl>
        </div>

        <div class="sm:col-start-2 lg:col-start-auto">
            <p class="text-sm font-semibold uppercase tracking-wide text-gray-400">Количество</p>
            <p class="mt-1 text-lg font-semibold text-gray-800">{{ item.quantity }} шт.</p>
        </div>

        <div class="sm:col-start-2 lg:col-start-auto lg:text-right">
            <p class="text-sm font-semibold uppercase tracking-wide text-gray-400">Сумма</p>
            <p class="mt-1 text-xl font-bold text-gray-900">
                {{ formatMoney(item.unit_price * item.quantity) }}
            </p>
        </div>
    </article>
</template>

<script setup>
import { formatMoney } from "@/Services/PriceFormatter";

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
})

</script>
