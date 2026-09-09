<template>
    <push v-if="isShow" :isShow="isShow" @hide="hideModal" :title="`Успешно добавлено в корзину`">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"  stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    </push>
    <layout :title="title">
        <div class="bg-white w-full">
            <div class="mx-auto grid max-w-[1600px] grid-cols-1 gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[280px_minmax(0,1fr)] lg:px-8">

                <!-- Колонка фильтра брендов (слева) -->
                <aside class="lg:sticky lg:top-6 lg:self-start">
                    <BrandFilter
                        @closeModal="closeBrandFilter"
                        :is-show="showBrandSelector"
                        :is-mobile="showMobileFilter"
                        :categories="categories.brands"
                        :clientBrands="clientBrands"
                    />
                </aside>

                <!-- Колонка со списком товаров (справа) -->
                <div class="min-w-0">
                    <div class="relative">
                        <h1 class="mb-6 text-3xl font-bold tracking-tight text-gray-900 md:text-4xl">
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

                        <div v-if="!$page.props.auth.user" class="mb-6 flex flex-col items-start justify-between gap-4 rounded-xl border border-green-200 bg-green-50 p-4 sm:flex-row sm:items-center">
                            <div>
                                <p class="font-semibold text-green-900">Уважаемый клиент!</p>
                                <p class="mt-1 text-sm text-green-800">Цены и возможность заказа доступны только авторизованным пользователям.</p>
                            </div>
                            <div class="flex gap-3">
                                <a href="/login" class="rounded-lg border border-green-700 bg-white px-4 py-2 text-sm font-medium text-green-700 transition hover:bg-green-100">Войти</a>
                                <a href="/register" class="rounded-lg bg-green-700 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-800">Регистрация</a>
                            </div>
                        </div>

                        <!-- Сетка или список товаров -->
                        <div v-if="details.data?.length" class="w-full space-y-3">
                            <CatalogItem
                                @showPush="showModal"
                                v-for="detail in details.data"
                                :key="detail.dt_id"
                                :detail="detail"
                            />
                        </div>

                        <!-- Адаптивное сообщение, если товары не найдены -->
                        <div v-else class="mt-8 w-full rounded-xl border border-dashed border-gray-300 bg-gray-50 px-6 py-12">
                            <p class="text-center text-xl font-medium text-gray-500 md:text-2xl">
                                По данному запросу запчастей не найдено
                            </p>
                        </div>
                    </div>

                    <!-- Пагинация -->
                    <pagination :links="details.links" class="mt-10" />
                </div>
            </div>
        </div>
    </layout>
</template>

<script setup>
import CatalogItem from "@/Pages/Catalog/CatalogItem.vue";
import { onMounted, onUnmounted, ref } from "vue";
import BrandFilter from "@/Shared/Filters/BrandFilter.vue";

const props = defineProps({
    details: Object,
    title: String,
    categories: Object,
    clientBrands: Object,
});

const showBrandSelector = ref(false);
const showFilterButton = ref(false);
const isShow = ref(false);
const showMobileFilter = ref(false);

onMounted(() => {
    window.addEventListener('resize', handleWindowResize);
    handleWindowResize();
});

onUnmounted(() => window.removeEventListener('resize', handleWindowResize));

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
    showBrandSelector.value = false;
}
</script>
