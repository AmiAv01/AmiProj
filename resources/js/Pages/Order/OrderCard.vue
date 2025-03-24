<template>
    <layout>
        <div class="flex flex-col p-10 w-full py-12 sm:w-[60%] sm:py-0 mx-auto justify-around sm:justify-between">
            <div
                class="flex rounded-[15px] pb-10 w-[500px] max-h-[300px] flex-col"
            >
                <h3 class="text-5xl text-wrap sm:text-7xl py-10 font-bold text-gray-900 ">
                    Заказ № {{ order.id }}
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
                    <p class="text-2xl">{{ order.total_price }} BYN</p>
                </div>
            </div>
            <div class="border-2 rounded-lg mt-16 sm:mt-0">
                <p class="text-center py-4 border-b-2 text-2xl sm:text-4xl mb-6 font-bold">
                    Приобретённые детали
                </p>
                <div class="px-12 h-[500px] overflow-y-auto">
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

const props = defineProps({
    order: Object,
    details: Array,
});

</script>


