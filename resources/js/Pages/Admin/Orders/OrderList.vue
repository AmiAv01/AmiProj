<template>
    <AdminLayout>
        <section class="admin-content">
            <header class="admin-page-header">
                <p class="text-sm font-semibold uppercase tracking-[0.14em] text-green-700">Продажи</p>
                <h1 class="admin-page-title">Заказы</h1>
                <p class="admin-page-description">Просматривайте заказы, меняйте их статус и находите нужную заявку по номеру или данным клиента.</p>
            </header>
            <div class="grid items-start gap-6 xl:grid-cols-[minmax(0,1fr)_280px]">
                <div class="admin-panel">
                    <div class="admin-panel-header">
                        <Search
                            :placeholder="`Найти заказ`"
                            category="order"
                            @setData="searchData"
                        />
                    </div>
                    <div class="overflow-x-auto">
                        <table class="admin-table min-w-[900px]">
                            <thead>
                                <tr>
                                    <th scope="col" v-for="columnName in columnNames" :key="columnName">{{columnName}}</th>
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
                    <div class="border-t border-slate-100 px-5 pb-6">
                        <pagination :links="searchOrders.links" />
                    </div>
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
        type: Object,
        default: () => ({ data: [], links: [] }),
    }})

let searchOrders = ref(props.orders);
let columnNames = ['#', 'Стоимость', 'Статус', 'Имя', 'Email', 'Дата'];

function searchData(data) {
    searchOrders.value = data.order;
}
</script>
