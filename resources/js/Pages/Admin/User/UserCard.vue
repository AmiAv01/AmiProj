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
                <div  class="flex flex-wrap my-4">
                    <p class="ext-gray-700 text-2xl mr-4">Формула</p>
                    <form class="flex flex-wrap">
                        <input
                            class="shadow appearance-none border rounded-md w-[300px] h-[30px] px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            type="text"
                            v-model="formula"

                        />
                        <button
                            @click.prevent="changeFormula"
                            class="bg-green-700 ml-2 rounded-lg mt-2 sm:mt-0 text-white w-[150px] max-w-[150px] h-[30px]  text-lg "
                        >
                            Изменить
                        </button>
                    </form>
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
import {ref} from "vue";

const props = defineProps({
    orders: Array,
    cart: Array,
    user: Object,
});

const formula = ref('O');
console.log(formula);

const changeFormula = () => {
    axios
        .put(`/admin/users/${props.user.id}`, {formula: formula.value})
        .then(res => console.log(res))
        .catch(err => console.log(err))
}
</script>

