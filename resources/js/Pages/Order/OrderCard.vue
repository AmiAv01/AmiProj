<template>
    <layout>
        <div class="mx-auto flex w-full max-w-6xl flex-col gap-8 p-4 py-8 sm:p-10 sm:py-0">
            <div
                class="flex w-full min-w-0 flex-col rounded-[15px] pb-2 sm:pb-10"
            >
                <h3 class="py-6 text-3xl font-bold text-gray-900 [overflow-wrap:anywhere] sm:py-10 sm:text-5xl">
                    Заказ № {{ order.order_number }}
                </h3>
                <div class="flex flex-col sm:flex-row">
                    <p class="text-gray-700 text-2xl mr-4">Статус заказа:</p>
                    <p class="text-2xl">{{ order.status }}</p>
                </div>
                <div class="flex flex-col mt-4 sm:flex-row sm:mt-0">
                    <p class="text-gray text-2xl mr-4">Дата:</p>
                    <p class="text-2xl">{{ new Date(order.created_at).toLocaleDateString() }}</p>
                </div>
                <div class="flex flex-col mt-4 sm:flex-row sm:mt-0">
                    <p class="text-gray text-2xl mr-4">Итоговая стоимость:</p>
                    <p class="text-2xl">{{ formatMoney(order.total_price) }}</p>
                </div>
                <div v-if="order.comment" class="flex flex-col mt-4">
                    <p class="text-gray text-2xl mr-4">Комментарий:</p>
                    <p class="text-xl whitespace-pre-line">{{ order.comment }}</p>
                </div>
            </div>
            <div class="min-w-0 rounded-lg border-2">
                <p class="text-center py-4 border-b-2 text-2xl sm:text-4xl mb-6 font-bold">
                    Приобретённые детали
                </p>
                <div class="h-[500px] overflow-y-auto px-4 sm:px-12">
                    <OrderItem
                        v-for="(detail, index) in details"
                        :item="detail"
                        :key="index"
                    />
                </div>
            </div>
        </div>
    </layout>
</template>

<script setup>
import OrderItem from "./OrderItem.vue";
import { formatMoney } from "@/Services/PriceFormatter";

const props = defineProps({
    order: Object,
    details: Array,
});

</script>
