<template>
    <admin-layout>
        <section class="p-3 sm:p-5">
            <div class="flex justify-around px-4 lg:px-12">
                <!-- Start coding here -->
                <div
                    class="bg-white w-[75%] dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden"
                >
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4"
                    >
                        <search
                            :placeholder="`Найти заказ`"
                            :link="`../admin/api/search?category=orders&searchQ`"
                            @setData="searchData"
                        />
                    </div>
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
                                    v-for="(order) in searchOrders.data"
                                    :key="`${order.id}`"
                                    :order="order"/>
                            </tbody>
                        </table>
                    </div>

                    <pagination :links="orders.links" />
                </div>
                <order-filter />
            </div>
        </section>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";
import OrderStatusFilter from "@/Shared/Filters/OrderStatusFilter.vue";
import Search from "@/Pages/Admin/Search.vue";
import OrderItem from "@/Pages/Admin/Orders/OrderItem.vue";
export default {
    data() {
        return {
            searchOrders: this.orders,
        };
    },
    components: {
        "admin-layout": AdminLayout,
        "order-filter": OrderStatusFilter,
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
    methods: {
        searchData(data) {
            this.searchOrders = data.orders;
        },
    },
};
</script>
