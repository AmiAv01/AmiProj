<template>
    <AdminLayout>
        <section class="admin-content">
            <header class="admin-page-header">
                <p class="text-sm font-semibold uppercase tracking-[0.14em] text-green-700">Каталог</p>
                <h1 class="admin-page-title">Запчасти</h1>
                <p class="admin-page-description">Поиск и проверка складских позиций из каталога AmiAvto.</p>
            </header>
            <div class="grid items-start gap-6 xl:grid-cols-[minmax(0,1fr)_300px]">
                <div class="admin-panel">
                    <div class="admin-panel-header">
                        <Search
                            :placeholder="`Найти запчасть`"
                            category="detail"
                            @setData="searchData"
                        />
                    </div>
                    <div class="overflow-x-auto">
                        <table class="admin-table min-w-[1000px]">
                            <thead>
                                <tr>
                                    <th scope="col" v-for="columnName in columnNames" :key="columnName">{{columnName}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <DetailItem
                                    v-for="(detail, index) in searchedData.data"
                                    :detail="detail"
                                    :key="detail.dt_id ?? index"
                                />
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-slate-100 px-5 pb-6">
                        <pagination :links="searchedData.links" />
                    </div>
                </div>
                <BrandSelector :categories="brands" :is-show="true" bg-header-color="bg-green-700" class="xl:sticky xl:top-24" />
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
        type: Object,
        default: () => ({ data: [], links: [] }),
    },
    brands: {
        type: Array,
        default: () => [],
    }})

let searchedData = ref(props.details);
let columnNames = ['#', 'Фирма', 'Тип', 'Invoice', 'Cargo', 'Ost'];

function searchData(data) {
    searchedData.value = data.detail;
}
</script>
