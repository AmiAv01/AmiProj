<template>
    <admin-layout>
        <section class="p-3 sm:p-5">
            <!-- dialog for adding product or editing product -->


            <!-- end -->
            <div class="mx-auto px-4 justify-between lg:px-12 flex">
                <!-- Start coding here -->
                <div
                    class="bg-white w-[80%] dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden"
                >
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4"
                    >
                        <search
                            :placeholder="`Найти запчасть`"
                            :link="`../admin/api/search?category=details&searchQ`"
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
                                    <th scope="col" class="px-4 py-3">Фирма</th>
                                    <th scope="col" class="px-4 py-3">Тип</th>
                                    <th scope="col" class="px-4 py-3">Invoice</th>
                                    <th scope="col" class="px-4 py-3">Cargo</th>
                                    <th scope="col" class="px-4 py-3">Ost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(detail, index) in searchedData.data"
                                    :key="`${detail.dt_id}`"
                                    class="border-b dark:border-gray-700"
                                >
                                    <th
                                        scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                    >
                                        {{ detail.dt_id }}
                                    </th>
                                    <td class="px-4 py-3">
                                        {{ detail.fr_code }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ detail.dt_type }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ detail.dt_invoice}}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ detail.dt_cargo }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ detail.ostc }}
                                    </td>
                                    <td
                                        class="px-4 py-3 flex items-center justify-end"
                                    >
                                        <button
                                            :id="`${detail.dt_id}-button`"
                                            :data-dropdown-toggle="`${detail.dt_id}`"
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
                                            :id="`${detail.dt_id}`"
                                            class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
                                        >
                                            <ul
                                                class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                                :aria-labelledby="`${detail.dt_id}-button`"
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
                                                    @click="
                                                        deleteProduct(
                                                            detail,
                                                            index
                                                        )
                                                    "
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
                    <pagination :links="searchedData.links" />
                </div>
                <brand-selector :categories="brands" />
            </div>
        </section>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";
import Search from "@/Pages/Admin/Search.vue";
import BrandSelector from "@/Shared/BrandSelector/Index.vue";
export default {
    data() {
        return {
            searchedData: this.details,
        };
    },
    components: {
        "admin-layout": AdminLayout,
        "brand-selector": BrandSelector,
        search: Search,
    },
    props: {
        details: {
            type: Array,
            default: [],
        },
        brands: {
            type: Array,
            default: [],
        },
    },
    methods: {
        searchData(data) {
            console.log(data);
            this.searchedData = data.details;
        },
        getSearchedData() {
            return this.searchedData;
        },
    },
    created() {
        //console.log(this.searchedData);
    },
};
</script>
