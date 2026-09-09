<template>
        <section class="w-full overflow-x-auto">
                        <table class="admin-table min-w-[900px]" v-if="orders?.length">
                            <thead>
                            <tr>
                                <th scope="col" class="px-4 py-3">#</th>
                                <th scope="col" class="px-4 py-3">Стоимость</th>
                                <th scope="col" class="px-4 py-3">
                                    Статус
                                </th>
                                <th scope="col" class="px-4 py-3">
                                    Имя
                                </th>
                                <th scope="col" class="px-4 py-3">Email</th>
                                <th scope="col" class="px-4 py-3">Дата</th>
                            </tr>
                            </thead>
                            <tbody>
                            <OrderItem
                                v-for="(order) in orders"
                                :key="`${order.id}`"
                                :order="order"/>
                            </tbody>
                        </table>
                        <div v-else class="admin-empty-state">Заказов пока нет</div>
        </section>
</template>

<script setup>
import OrderItem from "@/Pages/Admin/Orders/OrderItem.vue";

const props = defineProps({
    orders: {
        type: Array,
        default: () => [],
    }
})

</script>
