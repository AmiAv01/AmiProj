<template>
    <AdminLayout>
        <push
            v-if="notification.show"
            :isShow="notification.show"
            :title="notification.message"
            @hide="hideNotification"
        />
        <section class="admin-content">
            <header class="admin-page-header">
                <p class="text-sm font-semibold uppercase tracking-[0.14em] text-green-700">Контент</p>
                <h1 class="admin-page-title">Новости</h1>
                <p class="admin-page-description">Публикуйте новости компании и редактируйте уже размещённые материалы.</p>
            </header>
            <div class="admin-panel">
                    <div class="admin-panel-header">
                        <Search
                            :placeholder="`Найти новость`"
                            category="news"
                            @setData="searchData"
                        />
                        <button
                            @click="showModal"
                            class="admin-button-primary w-full sm:w-auto"
                        >
                            <svg class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10 4a.75.75 0 0 1 .75.75v4.5h4.5a.75.75 0 0 1 0 1.5h-4.5v4.5a.75.75 0 0 1-1.5 0v-4.5h-4.5a.75.75 0 0 1 0-1.5h4.5v-4.5A.75.75 0 0 1 10 4Z" /></svg>
                            Добавить
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="admin-table min-w-[900px]">
                            <thead>
                                <tr>
                                    <th scope="col" v-for="columnName in columnNames" :key="columnName">{{columnName}}</th>
                                    <th scope="col"><span class="sr-only">Действия</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <NewsItem
                                    v-for="(post) in store.newsData.data"
                                    :post="post"
                                    :key="post.id"
                                    @updated="showNotification('Изменения сохранены')"
                                />
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-slate-100 px-5 pb-6">
                        <Pagination :links="store.newsData.links" />
                    </div>
                </div>
            <NewsAddForm
                @closeModal="isShow = false"
                @created="showNotification('Новость успешно добавлена')"
                :isShow="isShow"
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
import {useNewsStore} from "@/Store/newsStore";

const props = defineProps({
    news: {
        type: Object,
        default: () => ({ data: [], links: [] }),
    }
})

const store = useNewsStore();
const isShow = ref(false);
const notification = ref({ show: false, message: '' });
let columnNames = ['#', 'Заголовок', 'Дата', 'Описание', 'Автор'];

store.newsData = props.news;
function searchData(data) {
    store.newsData = data.news;
}
function showModal() {
    isShow.value = true;
}
function showNotification(message) {
    notification.value.message = message;
    notification.value.show = true;
}
function hideNotification(show) {
    notification.value.show = show;
}
</script>
