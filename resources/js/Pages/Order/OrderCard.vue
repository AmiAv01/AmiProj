<template>
    <layout>
        <div class="flex p-10 w-[80%] mx-auto justify-around">
            <div
                class="flex pl-10 bg-gray-200 rounded-lg pb-10 w-[500px] max-h-[300px] flex-col"
            >
                <h3 class="text-5xl self-center p-10 font-bold text-gray-900">
                    Заказ № {{ order.id }}
                </h3>
                <div class="flex">
                    <p class="text-gray text-lg mr-6">Статус заказа:</p>
                    <p class="text-blue-500 text-lg">{{ order.status }}</p>
                </div>
                <div class="flex">
                    <p class="text-gray text-lg mr-6">Дата:</p>
                    <p class="text-blue-500 text-lg">{{ order.created_at }}</p>
                </div>
                <div class="flex">
                    <p class="text-gray text-lg mr-6">Итоговая цена:</p>
                    <p class="text-blue-500 text-lg">
                        {{ order.total_price }} BYN
                    </p>
                </div>
            </div>
            <div class="border-2 rounded-lg">
                <p class="text-center py-4 border-b-2 text-3xl mb-6 font-bold">
                    Приобретённые детали
                </p>
                <div class="px-12 h-[500px] overflow-y-auto">
                    <order-item
                        v-for="(detail, index) in details"
                        :item="detail"
                        :key="index"
                    />
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import OrderItem from "./OrderItem.vue";
export default {
    components: {
        "order-item": OrderItem,
    },
    created() {
        console.log(this.order);
        console.log(this.details);
    },
};
</script>

<script setup>
defineProps({
    order: Object,
    details: Array,
});
</script>
