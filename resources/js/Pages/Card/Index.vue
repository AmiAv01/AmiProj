<template>
    <layout>
        <push v-if="isShow" :isShow="isShow" @hide="hideModal" :title="`Добавлено в корзину`">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </push>
        <section class="py-8 bg-white md:py-12 antialiased">
            <div class="w-full px-4 2xl:px-0 max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Левая часть - картинка и деталировка -->
                    <div class="lg:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-6 items-start">
                            <!-- Картинка -->
                            <div class="flex justify-center md:justify-end">
                                <img
                                    class="w-[200px] h-[200px] object-contain"
                                    :src="imageUrl"
                                    alt="Product image"
                                />
                            </div>
                            
                            <!-- Описание -->
                            <div>
                                <h1 class="text-2xl font-semibold text-gray-900 mb-2">
                                    {{ editTitle(detail.dt_typec) }}
                                </h1>
                                <p class="text-lg text-gray-700 mb-1">
                                    {{ isEmpty ? detail.dt_code : detail.dt_invoice }}
                                    {{ isEmpty ? detail.dt_firm : '' }}
                                </p>
                                <p class="text-gray-500 mb-2">
                                    <strong>{{ detail.dt_oem }}</strong>
                                </p>
                                <p class="text-gray-500 mb-2">
                                    Бренд: <strong>{{ detail.fr_code }}</strong>
                                </p>
                                <p class="text-blue-700 mb-4">
                                    <strong>{{ detail.dt_comment }}</strong>
                                </p>
                                
                                <div class="mb-4">
                                    <p class="text-2xl font-bold text-gray-900">
                                        {{price !== '0' ? `${price} BYN` : 'цену уточнять'}}
                                    </p>
                                </div>
                                
                                <p v-if="detail.ostc" class="text-green-500">Есть в наличии</p>
                                <p v-else class="text-red-500">Нет в наличии</p>
                            </div>
                        </div>
                        
                        <!-- Деталировка -->
                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-xl font-semibold mb-4">Деталировка</h3>
                            <div class="space-y-4">
                                <div v-for="(item, index) in sameDetails" :key="index" class="border-b pb-3">
                                    <p class="font-semibold">{{ item.dt_typec }}</p>
                                    <p class="text-gray-600">Артикул: {{ item.dt_invoice }}</p>
                                    <p class="text-gray-600">Бренд: {{ item.fr_code }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Правая часть - аналоги -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-xl font-semibold mb-4">Найденные аналоги</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Артикул</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Бренд</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Наличие</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="item in analogs" :key="item.dt_id" class="hover:bg-gray-50">
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-blue-600">
                                            <a :href="`../../catalog/product/${item.dt_invoice}`">{{ item.dt_invoice }}</a>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600">{{ item.fr_code }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            <span v-if="item.ostc" class="text-green-500">Есть в наличии</span>
                                            <span v-else class="text-red-500">Нет в наличии</span>
                                        </td>
                                    </tr>
                                    <tr v-if="!analogs.length">
                                        <td colspan="3" class="px-4 py-4 text-center text-gray-400">Аналогов не найдено</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </layout>
</template>

<script setup>
import axios from "axios";
import { editDetailTitle } from "@/Services/TitleService";
import Analogs from "@/Pages/Card/Analogs.vue";
import {ref} from "vue";
import {useCartStore} from "@/Store/cartStore.js";

const props = defineProps({
    sameDetails: {
        type: Array,
    },
    detail: {
        type: Object,
    },
    analogs: {
        type: Array,
    },
    cargoIds: {
        type: Array,
    },
    isEmpty:{
        type: Boolean,
    },
    price: {
        type: String
    },
    imageUrl: {
        type: String
    }
});

const store = useCartStore();
const isShow = ref(false);

const addInCart = () =>  {
    axios
        .post("/cart", {
            id: props.detail.dt_id,
            quantity: 1,
            price: props.price
        })
        .then((res) => {
            console.log(res);
            isShow.value = true;
            store.incCartCount();
        })
        .catch((err) => console.log(err));
}

const editTitle = (res) => editDetailTitle(res)
const isHasDetails = () => {
    return (
        props.detail.dt_typec === "ГЕНЕРАТОР" ||
        props.detail.dt_typec === "СТАРТЕР"
    );
}

function hideModal(param){
    isShow.value = param;
}
</script>

