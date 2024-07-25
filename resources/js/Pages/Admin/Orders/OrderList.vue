<template>
    <admin-layout>
        <section class="p-3 sm:p-5">
            <!-- dialog for adding product or editing product -->
            <el-dialog
                v-model="dialogVisible"
                :title="editMode ? 'Edit product' : 'Add Product'"
                width="30%"
                :before-close="handleClose"
            >
                <!-- form start -->

                <!-- end -->
            </el-dialog>

            <!-- end -->
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
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                            >
                                <tr>
                                    <th scope="col" class="px-4 py-3">#</th>
                                    <th scope="col" class="px-4 py-3">Price</th>
                                    <th scope="col" class="px-4 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Username
                                    </th>
                                    <th scope="col" class="px-4 py-3">Email</th>
                                    <th scope="col" class="px-4 py-3">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(order, index) in searchOrders.data"
                                    :key="`${order.id}`"
                                    class="border-b dark:border-gray-700"
                                >
                                    <th
                                        scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                    >
                                        {{ order.id }}
                                    </th>
                                    <td class="px-4 py-3">
                                        {{ order.total_price }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ order.status }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ order.name }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ order.email }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ order.created_at }}
                                    </td>

                                    <td
                                        class="px-4 py-3 flex items-center justify-end"
                                    >
                                        <button
                                            :id="`${order.id}-button`"
                                            :data-dropdown-toggle="`${order.id}`"
                                            class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                            type="button"
                                        >
                                            <svg
                                                class="w-5 h-5"
                                                aria-hidden="true"
                                                fill="currentColor"
                                                viewbox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"
                                                />
                                            </svg>
                                        </button>
                                        <div
                                            :id="`${order.id}`"
                                            class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
                                        >
                                            <ul
                                                class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                                :aria-labelledby="`${order.id}-button`"
                                            >
                                                <li>
                                                    <a
                                                        href="#"
                                                        class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                                        >Изменить</a
                                                    >
                                                </li>
                                            </ul>
                                            <div class="py-1">
                                                <a
                                                    href="#"
                                                    class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                                                    >Удалить</a
                                                >
                                            </div>
                                        </div>
                                    </td>
                                </tr>
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
export default {
    data() {
        return {
            searchOrders: this.orders,
        };
    },
    components: {
        "admin-layout": AdminLayout,
        "order-filter": OrderStatusFilter,
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
