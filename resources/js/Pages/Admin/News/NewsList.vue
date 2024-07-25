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
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <!-- Start coding here -->
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
                                    <th scope="col" class="px-4 py-3">Title</th>
                                    <th scope="col" class="px-4 py-3">Date</th>
                                    <th scope="col" class="px-4 py-3">
                                        Description
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Author
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <news-item
                                    v-for="(post, index) in searchNews.data"
                                    :post="post"
                                    :key="post.id"
                                />
                            </tbody>
                        </table>
                    </div>

                    <Pagination :links="searchNews.links" />
                </div>
            </div>
        </section>
    </admin-layout>
</template>

<script>
import Pagination from "@/Shared/Pagination.vue";
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";
import Search from "@/Pages/Admin/Search.vue";
import Modal from "@/Components/Modal.vue";
import NewsItem from "@/Pages/Admin/News/NewsItem.vue";

export default {
    data() {
        return {
            searchNews: this.news,
        };
    },
    components: {
        search: Search,
        "news-item": NewsItem,

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
    },
};
</script>
