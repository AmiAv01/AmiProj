<template>
    <layout>
        <div class="max-w-7xl mx-auto py-12 px-4">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Мои заказы</h1>

            <!-- Если заказы есть -->
            <div v-if="orders.length > 0" class="bg-white shadow rounded-lg overflow-hidden">
                <table class="w-full text-left text-gray-600">
                    <thead class="bg-gray-50 uppercase text-sm">
                    <tr>
                        <th class="px-6 py-4">№ заказа</th>
                        <th class="px-6 py-4">Дата</th>
                        <th class="px-6 py-4">Сумма</th>
                        <th class="px-6 py-4">Статус</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    <tr v-for="order in orders" :key="order.id"
                        @click="setLink(order.id)"
                        class="hover:bg-gray-50 cursor-pointer transition">
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ order.id }}</td>
                        <td class="px-6 py-4">{{ new Date(order.created_at).toLocaleDateString() }}</td>
                        <td class="px-6 py-4">{{ order.total_price }} BYN</td>
                        <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm"
                                      :class="order.status === 'Завершён' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'">
                                    {{ order.status }}
                                </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Если заказов нет, используем наш новый компонент -->
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

const props = defineProps({ orders: Array });

const setLink = (id) => {
    window.location = `/order/${id}`;
}
</script>
