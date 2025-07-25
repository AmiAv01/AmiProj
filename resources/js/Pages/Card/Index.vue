<template>
    <layout>
        <push v-if="isShow" :isShow="isShow" @hide="hideModal" :title="`Добавлено в корзину`">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </push>
        <section class="py-6 bg-white antialiased">
            <div class="w-full px-4 max-w-7xl mx-auto">
                <!-- Основная строка с картинкой, описанием и аналогами -->
                <div class="flex flex-col lg:flex-row gap-6 items-start">
                    <!-- Блок с картинкой и описанием -->
                    <div class="flex-1 flex flex-col md:flex-row gap-6">
                        <!-- Картинка -->
                        <div class="w-full md:w-1/3 flex justify-center">
                            <img
                                class="max-h-64 object-contain"
                                :src="imageUrl"
                                alt="Product image"
                            />
                        </div>
                        
                        <!-- Описание товара -->
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 mb-1">
                                {{ editTitle(detail.dt_typec) }}
                            </h1>
                            <p class="text-lg text-gray-700 mb-1">
                                {{ isEmpty ? detail.dt_code : detail.dt_invoice }}
                                {{ isEmpty ? detail.dt_firm : '' }}
                            </p>
                            <p class="text-gray-600 mb-1">
                                <strong>{{ detail.dt_oem }}</strong>
                            </p>
                            <p class="text-gray-600 mb-1">
                                Бренд: <strong>{{ detail.fr_code }}</strong>
                            </p>
                            <p class="text-blue-700 mb-3 font-medium">
                                {{ detail.dt_comment }}
                            </p>
                            
                            <p class="text-2xl font-bold text-gray-900 mb-2">
                                {{price !== '0' ? `${price} BYN` : 'цену уточнять'}}
                            </p>
                            
                            <p v-if="detail.ostc" class="text-green-500 font-medium">Есть в наличии</p>
                            <p v-else class="text-red-500 font-medium">Нет в наличии</p>
                        </div>
                    </div>
                    
                    <!-- Блок с аналогами -->
                    <div class="w-full lg:w-1/3 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-bold mb-3 border-b pb-2">Найденные аналоги</h3>
                        <div class="space-y-2">
                            <div v-for="item in analogs" :key="item.dt_id" class="flex justify-between text-sm">
                                <a :href="`../../catalog/product/${item.dt_invoice}`" 
                                class="text-blue-600 hover:underline">
                                    {{ item.dt_invoice }}
                                </a>
                                <span>{{ item.fr_code }}</span>
                                <span v-if="item.ostc" class="text-green-500">Есть</span>
                                <span v-else class="text-red-500">Нет</span>
                            </div>
                            <p v-if="!analogs.length" class="text-gray-400 text-sm">Аналогов не найдено</p>
                        </div>
                    </div>
                </div>
                
                <!-- Блок детализации -->
                <div class="mt-8 pt-6 border-t">
                    <h3 class="text-xl font-bold mb-4">Деталировка</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-for="(item, index) in sameDetails" :key="index" 
                            class="border p-3 rounded-lg">
                            <p class="font-semibold">{{ item.dt_typec }}</p>
                            <p class="text-sm text-gray-600">Артикул: {{ item.dt_invoice }}</p>
                            <p class="text-sm text-gray-600">Бренд: {{ item.fr_code }}</p>
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

