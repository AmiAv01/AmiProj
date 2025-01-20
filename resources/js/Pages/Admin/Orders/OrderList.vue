<template>
    <AdminLayout>
        <section class="p-3 sm:p-5">
            <div class="flex justify-around px-4 lg:px-12">
                <div
                    class="bg-white w-[75%]  relative shadow-md sm:rounded-lg overflow-hidden"
                >
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4"
                    >
                        <Search
                            :placeholder="`Найти заказ`"
                            :link="`../admin/api/search?category=orders&searchQ`"
                            @setData="searchData"
                        />
                    </div>
                    <div class="overflow-x-auto">
                        <table
                            class="w-full text-sm text-left text-gray-500 "
                        >
                            <thead
                                class="text-xs text-gray-700  bg-gray-50 "
                            >
                                <tr>
                                    <th scope="col" class="px-4 py-3" v-for="columnName in columnNames">{{columnName}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <OrderItem
                                    v-for="(order) in searchOrders.data"
                                    :key="`${order.id}`"
                                    :order="order"/>
                            </tbody>
                        </table>
                    </div>

                    <pagination :links="orders.links" />
                </div>
                <OrderStatusFilter />
            </div>
        </section>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";
import OrderStatusFilter from "@/Shared/Filters/OrderStatusFilter.vue";
import Search from "@/Pages/Admin/Search.vue";
import OrderItem from "@/Pages/Admin/Orders/OrderItem.vue";
import {ref} from "vue";

const props = defineProps({
    orders: {
        type: Array,
        default: [],
    }})

let searchOrders = ref(props.orders);
let columnNames = ['#', 'Стоимость', 'Статус', 'Имя', 'Email', 'Дата'];

function searchData(data) {
    searchOrders.value = data.orders;
}
</script>
