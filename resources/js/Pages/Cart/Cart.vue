<template>
    <layout>
        <section class="relative z-10 py-12 lg:py-24">
            <div class="w-full max-w-7xl px-4 md:px-5 lg:px-6 mx-auto">

                <!-- Заголовок -->
                <div class="flex items-center justify-between pb-8 border-b border-gray-300 mb-8">
                    <h2 class="font-manrope font-bold text-3xl leading-10 text-black">Корзина</h2>
                    <h2 v-if="store.cartData.length > 0" class="font-manrope font-bold text-xl leading-8 text-gray-600">
                        Товаров: {{ count }} шт.
                    </h2>
                </div>

                <!-- Список товаров -->
                <div v-if="store.cartData.length > 0" class="grid grid-cols-12 gap-8">
                    <div class="col-span-12 xl:col-span-8 w-full max-xl:max-w-3xl max-xl:mx-auto">
                        <div class="h-[400px] xl:h-[500px] overflow-y-auto pr-4">
                            <CartItem v-for="(detail, index) in store.cartData" :key="detail.dt_id || index" :item="detail" />
                        </div>
                    </div>
                    <div class="col-span-12 xl:col-span-4 w-full max-xl:max-w-3xl max-xl:mx-auto">
                        <CartOrder :count="count" :price="price" />
                    </div>
                </div>

                <!-- Состояние: Корзина пуста (Замена на компонент) -->
                <EmptyState
                    v-else
                    title="Ваша корзина пуста"
                    description="Похоже, вы еще не добавили запчасти в корзину. Перейдите в каталог, чтобы найти нужные детали."
                    buttonText="На главную"
                    link="/"
                >
                    <template #icon>
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </template>
                </EmptyState>
            </div>
        </section>
    </layout>
</template>

<script setup>
import { computed } from "vue";
import { useCartStore } from "@/Store/cartStore.js";
import CartItem from "./CartItem.vue";
import CartOrder from "./CartOrder.vue";
import Layout from "@/Shared/UserLayout.vue";
import EmptyState from "@/Components/EmptyState.vue"; // Убедитесь, что путь верный

const store = useCartStore();
const props = defineProps({
    items: Array,
});

// Инициализация данных магазина
store.setDetails(props.items);

// Вычисляемые свойства для итогов
const count = computed(() => {
    return Object.values(store.cartData).reduce((sum, obj) => sum + (obj.quantity || 0), 0);
});

const price = computed(() => {
    return Object.values(store.cartData).reduce((sum, obj) => {
        const p = parseFloat(obj.price) || 0;
        return sum + (p * obj.quantity);
    }, 0).toFixed(2);
});
</script>
