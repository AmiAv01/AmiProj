<template>
        <section class=" w-full overflow-y-auto">
            <div class="w-full">
                <!-- Start coding here -->
                <div
                    class="bg-white relative shadow-md sm:rounded-lg overflow-hidden"
                >
                    <div class="overflow-x-auto">
                        <table
                            class="w-full text-sm text-left text-gray-500 "
                        >
                            <thead
                                class="text-xs text-gray-700  bg-gray-50 "
                            >
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
                    </div>
                </div>
            </div>
        </section>
</template>

<script setup>
import OrderItem from "@/Pages/Admin/Orders/OrderItem.vue";

const props = defineProps({
    orders: {
        type: Array,
        default: [],
    }
})

</script>
