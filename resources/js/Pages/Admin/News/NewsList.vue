<template>
    <admin-layout>
        <section class="p-3 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div
                    class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden"
                >
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4"
                    >
                        <search
                            :placeholder="`Найти новость`"
                            :link="`../admin/api/search?category=news&searchQ`"
                            @setData="searchData"
                        />
                        <button
                            @click="showModal"
                            class="flex items-center text-white bg-green-700  hover:bg-green-800  font-medium rounded-lg  text-center dark:bg-green-600flex max-w-[400px]  py-2 px-4"
                        >
                            Добавить
                        </button>
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
                                    <th scope="col" class="px-4 py-3">Заголовок</th>
                                    <th scope="col" class="px-4 py-3">Дата</th>
                                    <th scope="col" class="px-4 py-3">
                                        Описание
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Автор
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <news-item
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
            <news-form
                @closeModal="isShow = false"
                :show="isShow"
                :actionTitle="`Добавить`"
            />
        </section>
    </admin-layout>
</template>

<script>
import Pagination from "@/Shared/Pagination.vue";
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";
import Search from "@/Pages/Admin/Search.vue";
import Modal from "@/Components/Modal.vue";
import NewsItem from "@/Pages/Admin/News/NewsItem.vue";
import NewsAddForm from "@/Shared/Forms/NewsAddForm.vue";

export default {
    data() {
        return {
            searchNews: this.news,
            isShow: false
        };
    },
    components: {
        search: Search,
        "news-item": NewsItem,
        "news-form": NewsAddForm,
        modal: Modal,
        "admin-layout": AdminLayout,
    },
    props: {
        news: {
            type: Array,
            default: [],
        },
    },

    created() {
        console.log(this.news.links);
    },
    methods: {
        searchData(data) {
            console.log(data.news);
            this.searchNews = data.news;
        },
        showModal() {
            this.isShow = true;
        },
    },
};
</script>
