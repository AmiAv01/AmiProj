<template>
    <AdminLayout>
        <section class="admin-content">
            <header class="admin-page-header">
                <p class="text-sm font-semibold uppercase tracking-[0.14em] text-green-700">Клиенты</p>
                <h1 class="admin-page-title">Пользователи</h1>
                <p class="admin-page-description">Управляйте доступом клиентов и просматривайте связанную с ними историю заказов.</p>
            </header>
            <div class="admin-panel">
                    <div class="admin-panel-header">
                        <Search
                            :placeholder="`Найти пользователя`"
                            category="user"
                            @setData="searchData"
                        />
                    </div>
                    <div class="overflow-x-auto">
                        <table class="admin-table min-w-[760px]">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Имя</th>
                                <th scope="col">Email</th>
                                <th scope="col">
                                    Админ
                                </th>
                                <th scope="col"><span class="sr-only">Действия</span></th>
                            </tr>
                            </thead>
                            <tbody>
                            <UserItem
                                v-for="(user, index) in searchUser.data"
                                :user="user"
                                :key="user.id"
                            />
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-slate-100 px-5 pb-6">
                        <Pagination :links="searchUser.links" />
                    </div>
                </div>
        </section>
    </AdminLayout>
</template>

<script setup>
import Pagination from "@/Shared/Pagination.vue";
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";
import Search from "@/Pages/Admin/Search.vue";
import UserItem from "@/Pages/Admin/User/UserItem.vue";
import {ref} from "vue";

const props = defineProps({
    users: {
        type: Object,
        default: () => ({ data: [], links: [] }),
    }
})

let searchUser = ref(props.users);

function searchData(data) {
    searchUser.value = data.user;
}

</script>
