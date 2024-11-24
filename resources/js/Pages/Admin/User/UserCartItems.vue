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
                        <order-item
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

<script>
import Search from "@/Pages/Admin/Search.vue";
import OrderItem from "@/Pages/Admin/Orders/OrderItem.vue";
export default {
    data() {
        return {
            searchOrders: this.orders,
        };
    },
    components: {
        "order-item": OrderItem,
        search: Search,
    },
    props: {
        orders: {
            type: Array,
            default: [],
        },
    },
    created() {
        console.log(this.orders);
    },
};
</script>
