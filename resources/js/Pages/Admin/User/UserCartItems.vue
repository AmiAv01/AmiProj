<template>
    <section class=" w-full overflow-y-auto">
        <div class="w-full">
            <!-- Start coding here -->
            <div
                class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden"
            >
                <div class="overflow-x-auto">
                    <table
                        class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                    >
                        <thead
                            class="text-xs text-gray-700  bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                        >
                        <tr>
                            <th scope="col" class="px-4 py-3" v-for="columnName in columnNames">{{columnName}}</th>
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

const props = defineProps({orders: {
        type: Array,
        default: [],
    }})

let columnNames = ['#', 'Стоимость', 'Статус', 'Имя', 'Email', 'Дата'];

</script>
