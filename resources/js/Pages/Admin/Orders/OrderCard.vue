<template>
    <admin-layout>
        <div class="mx-auto flex w-full max-w-6xl flex-col gap-8 p-4 sm:p-10">
            <div
                class="flex w-full min-w-0 flex-col rounded-[15px] pb-2 sm:pb-10"
            >
                <h3 class="py-6 text-3xl font-bold text-gray-900 [overflow-wrap:anywhere] sm:py-10 sm:text-5xl">
                    Заказ № {{ order.order_number }}
                </h3>
                <div class="flex flex-col">
                    <p class="text-gray-700 text-2xl mr-4">Статус заказа:</p>
                    <status-form :status="order.status" :order-id="order.id"/>
                </div>
                <div class="flex">
                    <p class="text-gray-700 text-2xl mr-4">Имя:</p>
                    <p class="text-2xl">{{ order.name }}</p>
                </div>
                <div class="flex">
                    <p class="text-gray-700 text-2xl mr-4">Email:</p>
                    <p class="text-2xl">{{ order.email }}</p>
                </div>
                <div class="flex">
                    <p class="text-gray text-2xl mr-4">Дата:</p>
                    <p class="text-2xl">{{ new Date(order.created_at).toLocaleDateString() }}</p>
                </div>
                <div class="flex">
                    <p class="text-gray text-2xl mr-4">Итоговая стоимость:</p>
                    <p class="text-2xl">{{ formatMoney(order.total_price) }}</p>
                </div>
                <div v-if="order.comment" class="flex flex-col mt-4">
                    <p class="text-gray text-2xl mr-4">Комментарий:</p>
                    <p class="text-xl whitespace-pre-line">{{ order.comment }}</p>
                </div>
            </div>
            <div class="min-w-0 rounded-lg border-2">
                <p class="text-center py-4 text-xl border-b-2 sm:text-4xl mb-6 font-bold">
                    Приобретённые детали
                </p>
                <div class="h-[500px] overflow-y-auto px-4 sm:px-12">
                    <order-item
                        v-for="(detail, index) in details"
                        :item="detail"
                        :key="index"
                    />
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script>
import OrderItem from "@/Pages/Order/OrderItem.vue";
import SelectOrderStatusForm from "@/Components/SelectOrderStatusForm.vue";
export default {
    components: {
        "order-item": OrderItem,
        "status-form": SelectOrderStatusForm
    },
    created() {
        console.log(this.order);
        console.log(this.details);
    },
};
</script>

<script setup>
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";
import { formatMoney } from "@/Services/PriceFormatter";

defineProps({
    order: Object,
    details: Array,
});
</script>
