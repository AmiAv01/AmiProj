<template>
    <admin-layout>
        <div class="flex flex-col p-10 w-[60%] mx-auto justify-around">
            <div
                class="flex rounded-[15px] pb-10 w-[500px] max-h-[300px] flex-col"
            >
                <h3 class="text-7xl py-10 font-bold text-gray-900">
                    Заказ № {{ order[0].id }}
                </h3>
                <div class="flex">
                    <p class="text-gray-700 text-2xl mr-4">Статус заказа:</p>
                    <p class="text-2xl">{{ order[0].status }}</p>
                </div>
                <div class="flex">
                    <p class="text-gray-700 text-2xl mr-4">Имя:</p>
                    <p class="text-2xl">{{ order[0].name }}</p>
                </div>
                <div class="flex">
                    <p class="text-gray-700 text-2xl mr-4">Email:</p>
                    <p class="text-2xl">{{ order[0].email }}</p>
                </div>
                <div class="flex">
                    <p class="text-gray text-2xl mr-4">Дата:</p>
                    <p class="text-2xl">{{ order[0].created_at }}</p>
                </div>
                <div class="flex">
                    <p class="text-gray text-2xl mr-4">Итоговая стоимость:</p>
                    <p class="text-2xl">{{ order[0].total_price }} BYN</p>
                </div>
            </div>
            <div class="border-2 mt-10 rounded-lg">
                <p class="text-center py-4 border-b-2 text-4xl mb-6 font-bold">
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
    </admin-layout>
</template>

<script>
import OrderItem from "@/Pages/Order/OrderItem.vue";
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
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";

defineProps({
    order: Object,
    details: Array,
});
</script>
