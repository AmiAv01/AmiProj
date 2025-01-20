<template>
    <AdminLayout>
        <section class="p-3 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div
                    class="bg-white  relative shadow-md sm:rounded-lg overflow-hidden"
                >
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4"
                    >
                        <Search
                            :placeholder="`Найти новость`"
                            :link="`../admin/api/search?category=news&searchQ`"
                            @setData="searchData"
                        />
                        <button
                            @click="showModal"
                            class="flex items-center text-white bg-green-700  hover:bg-green-800  font-medium rounded-lg  text-center max-w-[400px]  py-2 px-4"
                        >
                            Добавить
                        </button>
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
                                <NewsItem
                                    v-for="(post) in searchNews.data"
                                    :post="post"
                                    :key="post.id"
                                />
                            </tbody>
                        </table>
                    </div>
                    <Pagination :links="searchNews.links" />
                </div>
            </div>
            <NewsAddForm
                @closeModal="isShow = false"
                :show="isShow"
                :actionTitle="`Добавить`"
            />
        </section>
    </AdminLayout>
</template>

<script setup>
import Pagination from "@/Shared/Pagination.vue";
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";
import Search from "@/Pages/Admin/Search.vue";
import NewsItem from "@/Pages/Admin/News/NewsItem.vue";
import NewsAddForm from "@/Shared/Forms/NewsAddForm.vue";
import {ref} from "vue";

const props = defineProps({
    news: {
        type: Array,
        default: [],
    }
})

let searchNews = ref(props.news);
let isShow = false;
let columnNames = ['#', 'Заголовок', 'Дата', 'Описание', 'Автор'];

function searchData(data) {

    searchNews.value = data.news;
    console.log(searchNews.value);
}
function showModal() {
    isShow = true;
}
</script>
