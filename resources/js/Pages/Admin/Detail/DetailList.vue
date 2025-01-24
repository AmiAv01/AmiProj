<template>
    <AdminLayout>
        <section class="p-3 sm:p-5">
            <div class="mx-auto px-4 justify-between lg:px-12 flex">
                <div
                    class="bg-white w-full x2l:w-[80%] relative shadow-md sm:rounded-lg overflow-hidden"
                >
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4"
                    >
                        <Search
                            :placeholder="`Найти запчасть`"
                            :link="`../admin/api/search?category=details&searchQ`"
                            @setData="searchData"
                        />
                    </div>
                    <div class="overflow-x-auto">
                        <table
                            class="w-full text-sm text-left text-gray-500 "
                        >
                            <thead
                                class="text-xs text-gray-700  bg-gray-50"
                            >
                                <tr>
                                    <th scope="col" class="px-4 py-3" v-for="columnName in columnNames">{{columnName}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <DetailItem
                                    v-for="(detail, index) in searchedData.data"
                                    :detail="detail"
                                />
                            </tbody>
                        </table>
                    </div>
                    <pagination :links="searchedData.links" />
                </div>
                <BrandSelector :categories="brands" />
            </div>
        </section>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";
import Search from "@/Pages/Admin/Search.vue";
import BrandSelector from "@/Shared/Filters/BrandFilter.vue";
import DetailItem from "@/Pages/Admin/Detail/DetailItem.vue";
import {ref} from "vue";

const props = defineProps({details: {
        type: Array,
        default: [],
    },
    brands: {
        type: Array,
        default: [],
    }})

let searchedData = ref(props.details);
let columnNames = ['#', 'Фирма', 'Тип', 'Invoice', 'Cargo', 'Ost'];

function searchData(data) {
    console.log(data);
    searchedData.value = data.details;
}
</script>
