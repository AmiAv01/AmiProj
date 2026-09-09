<template>
    <layout>
        <div class="w-full max-w-7xl mx-auto py-12 lg:py-16 px-4 md:px-5 lg:px-6">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Мои заказы</h1>

            <div v-if="orders.length > 0" class="overflow-x-auto bg-white border border-gray-200 shadow-sm rounded-xl">
                <table class="w-full min-w-[720px] text-left text-gray-600">
                    <thead class="bg-gray-50 uppercase text-sm font-semibold tracking-wide text-gray-600">
                    <tr>
                        <th class="px-6 py-4">№ заказа</th>
                        <th class="px-6 py-4">Дата</th>
                        <th class="px-6 py-4">Сумма</th>
                        <th class="px-6 py-4">Статус</th>
                        <th class="w-14 px-4 py-4"><span class="sr-only">Открыть</span></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    <tr v-for="order in orders" :key="order.id"
                        @click="setLink(order.order_number)"
                        @keydown.enter="setLink(order.order_number)"
                        tabindex="0"
                        role="link"
                        class="hover:bg-gray-50 focus:bg-gray-50 focus:outline-none cursor-pointer transition-colors group">
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ order.order_number }}</td>
                        <td class="px-6 py-4">{{ new Date(order.created_at).toLocaleDateString() }}</td>
                        <td class="px-6 py-4">{{ formatMoney(order.total_price) }}</td>
                        <td class="px-6 py-4">
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium"
                                      :class="order.status === 'Завершён' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'">
                                    {{ order.status }}
                                </span>
                        </td>
                        <td class="px-4 py-4 text-gray-400 transition-colors group-hover:text-gray-700">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" aria-hidden="true">
                                <path d="m7.5 5 5 5-5 5" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <EmptyState
                v-else
                title="У вас пока нет заказов"
                description="Вы еще не совершили ни одной покупки. Оформите первый заказ в нашем каталоге!"
                buttonText="На главную"
                link="/"
            />
        </div>
    </layout>
</template>

<script setup>
import Layout from "@/Shared/UserLayout.vue";
import EmptyState from "@/Components/EmptyState.vue"; // Импортируем компонент
import { formatMoney } from "@/Services/PriceFormatter";

const props = defineProps({ orders: Array });

const setLink = (id) => {
    window.location = `/order/${id}`;
}
</script>
