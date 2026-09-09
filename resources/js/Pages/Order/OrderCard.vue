<template>
    <layout>
        <main class="mx-auto w-full max-w-7xl px-4 py-12 md:px-5 lg:px-6 lg:py-16">
            <header class="mb-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <h1 class="text-3xl font-bold leading-tight text-gray-900 [overflow-wrap:anywhere] sm:text-4xl">
                    Заказ № {{ order.order_number }}
                    </h1>
                    <span
                        class="inline-flex w-fit items-center rounded-full px-3.5 py-1.5 text-base font-semibold"
                        :class="order.status === 'Завершён' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'"
                    >
                        {{ order.status }}
                    </span>
                </div>

                <div class="mt-8 grid overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm sm:grid-cols-2 sm:divide-x sm:divide-gray-200">
                    <div class="p-5 sm:p-6">
                        <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Дата оформления</p>
                        <p class="mt-2 text-xl font-semibold text-gray-900">
                            {{ new Date(order.created_at).toLocaleDateString('ru-RU') }}
                        </p>
                    </div>
                    <div class="border-t border-gray-200 p-5 sm:border-t-0 sm:p-6">
                        <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Итоговая стоимость</p>
                        <p class="mt-2 text-xl font-bold text-gray-900">{{ formatMoney(order.total_price) }}</p>
                    </div>
                </div>

                <div v-if="order.comment" class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-5 sm:p-6">
                    <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Комментарий к заказу</p>
                    <p class="mt-2 whitespace-pre-line break-words text-lg leading-7 text-gray-800">{{ order.comment }}</p>
                </div>
            </header>

            <section class="min-w-0">
                <div class="flex items-baseline justify-between gap-4">
                    <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                    Приобретённые детали
                    </h2>
                    <p class="shrink-0 text-base font-medium text-gray-500">{{ detailsCountLabel }}</p>
                </div>

                <div class="mt-5 max-h-[560px] overflow-y-auto rounded-xl border border-gray-200 bg-white px-4 shadow-sm sm:px-6">
                    <OrderItem
                        v-for="(detail, index) in details"
                        :item="detail"
                        :key="index"
                    />
                </div>
            </section>
        </main>
    </layout>
</template>

<script setup>
import { computed } from "vue";
import OrderItem from "./OrderItem.vue";
import { formatMoney } from "@/Services/PriceFormatter";

const props = defineProps({
    order: Object,
    details: Array,
});

const detailsCount = computed(() => {
    return (props.details || []).reduce((sum, detail) => sum + (Number(detail.quantity) || 0), 0);
});

const detailsCountLabel = computed(() => {
    const lastTwoDigits = detailsCount.value % 100;
    const lastDigit = detailsCount.value % 10;

    if (lastTwoDigits >= 11 && lastTwoDigits <= 14) {
        return `${detailsCount.value} товаров`;
    }

    if (lastDigit === 1) {
        return `${detailsCount.value} товар`;
    }

    if (lastDigit >= 2 && lastDigit <= 4) {
        return `${detailsCount.value} товара`;
    }

    return `${detailsCount.value} товаров`;
});
</script>
