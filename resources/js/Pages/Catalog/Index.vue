<template>
    <push v-if="isShow" :isShow="isShow" @hide="hideModal" :title="`Успешно добавлено в корзину`">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"  stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    </push>
    <layout :title="title">
        <div class="bg-white w-full">
            <!-- Двухколоночная сетка: 1 колонка на мобильных, 4 на десктопе -->
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 lg:grid-cols-4 gap-8">

                <!-- Колонка фильтра брендов (слева) -->
                <div class="lg:col-span-1">
                    <BrandFilter
                        @closeModal="closeBrandFilter"
                        :is-show="showBrandSelector"
                        :is-mobile="showMobileFilter"
                        :categories="categories.brands"
                        :clientBrands="clientBrands"
                    />
                </div>

                <!-- Колонка со списком товаров (справа) -->
                <div class="lg:col-span-3">
                    <div class="relative">
                        <h1 class="text-3xl md:text-5xl font-bold tracking-tight text-gray-900 mb-6">
                            {{ title }}
                        </h1>

                        <!-- Кнопка «Фильтр» для мобильных устройств -->
                        <button
                            @click="toggleMobile"
                            v-show="showFilterButton"
                            class="z-30 rounded-xl bg-green-600 hover:bg-green-700 text-white border-2 border-gray-300 w-[200px] h-[50px] mb-8"
                        >
                            Фильтр
                        </button>

                        <div v-if="!$page.props.auth.user" class="mb-8 p-6 bg-green-50 border border-green-200 rounded-xl flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div>
                                <p class="text-lg font-semibold text-green-900">Уважаемый клиент!</p>
                                <p class="text-green-800">Цены и возможность заказа доступны только авторизованным пользователям.</p>
                            </div>
                            <div class="flex gap-3">
                                <a href="/login" class="px-5 py-2 bg-white border border-green-700 text-green-700 rounded-lg hover:bg-green-100 font-medium">Войти</a>
                                <a href="/register" class="px-5 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 font-medium">Регистрация</a>
                            </div>
                        </div>

                        <!-- Сетка или список товаров -->
                        <div v-if="details.data" class="w-full border-t border-gray-200 pt-4">
                            <CatalogItem
                                @showPush="showModal"
                                v-for="detail in details.data"
                                :key="detail.dt_id"
                                :detail="detail"
                            />
                        </div>

                        <!-- Адаптивное сообщение, если товары не найдены -->
                        <div v-else class="mt-8 border-t border-gray-200 pt-8 w-full">
                            <p class="text-center text-xl md:text-3xl text-gray-500 font-medium">
                                По данному запросу запчастей не найдено
                            </p>
                        </div>
                    </div>

                    <!-- Пагинация -->
                    <pagination :links="details.links" class="mt-8" />
                </div>
            </div>
        </div>
    </layout>
</template>

<script setup>
import CatalogItem from "@/Pages/Catalog/CatalogItem.vue";
import {onMounted, ref} from "vue";
import BrandFilter from "@/Shared/Filters/BrandFilter.vue";

const props = defineProps({
    details: Object,
    title: String,
    categories: Object,
    clientBrands: Object,
});

const checked = ref([]);
let showBrandSelector = ref(false);
let showFilterButton = ref(false);
const isShow = ref(false);
const showMobileFilter = ref(false);
const selectedDetails = ref(props.details);

onMounted(() => {
    window.addEventListener('resize', handleWindowResize);
    handleWindowResize();
});

const handleWindowResize = () => {
    showBrandSelector.value = (window.innerWidth >= 1124);
    showFilterButton.value = (window.innerWidth <= 1124);
    showMobileFilter.value = (window.innerWidth <=1124);
}

const hideModal = (param) => isShow.value = param;

const showModal = (param) => isShow.value = param;
const toggleMobile = () => {
    showBrandSelector.value = !showBrandSelector.value;
}

const closeBrandFilter = () => {
    console.log(isShow.value);
    showBrandSelector.value = false;
}
</script>
