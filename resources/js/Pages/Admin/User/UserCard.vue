<template>
    <admin-layout>
        <div class="flex flex-col p-10 w-[75%] mx-auto justify-around">
            <div
                class="flex rounded-[15px] pb-10 w-[500px] max-h-[300px] flex-col"
            >
                <h3 class="text-7xl py-10 font-bold text-gray-900">
                    {{ user.name }}
                </h3>
                <div class="flex">
                    <p class="text-gray-700 text-2xl mr-4">Email:</p>
                    <p class="text-2xl">{{ user.email }}</p>
                </div>
                <div class="flex">
                    <p class="text-gray-700 text-2xl mr-4">Админ:</p>
                    <p class="text-2xl">{{ (user.isAdmin === 1) ? "Да" : "Нет" }}</p>
                </div>
            </div>
            <div class="border-2 mt-10 rounded-lg">
                <p class="text-center py-4 border-b-2 text-4xl  font-bold">
                    Заказы
                </p>
                <UserOrderItems :orders="orders"/>
            </div>
            <div class="border-2 mt-10 rounded-lg">
                <p class="text-center py-4 border-b-2 text-4xl  font-bold">
                    Корзина
                </p>
                <detail-list :details="cart"/>
            </div>
        </div>
    </admin-layout>
</template>

<script setup>
import UserOrderItems from "@/Pages/Admin/User/UserOrderItems.vue";
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";
import DetailList from "@/Shared/DetailList.vue";

const props = defineProps({
    orders: Array,
    cart: Array,
    user: Object,
});
</script>

