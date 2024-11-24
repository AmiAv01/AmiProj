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
                            :placeholder="`Найти пользователя`"
                            :link="`../admin/api/search?category=users&searchQ`"
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
                                <th scope="col" class="px-4 py-3">Имя</th>
                                <th scope="col" class="px-4 py-3">Email</th>
                                <th scope="col" class="px-4 py-3">
                                    Админ
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <user-item
                                v-for="(user, index) in searchUser.data"
                                :user="user"
                                :key="user.id"
                            />
                            </tbody>
                        </table>
                    </div>

                    <Pagination :links="searchUser.links" />
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
import UserItem from "@/Pages/Admin/User/UserItem.vue";

export default {
    data() {
        return {
            searchUser: this.users,
            isShow: false
        };
    },
    components: {
        search: Search,
        "user-item": UserItem,
        modal: Modal,
        "admin-layout": AdminLayout,
    },
    props: {
        users: {
            type: Array,
            default: [],
        },
    },
    created() {
        console.log(this.users);
    },
    methods: {
        searchData(data) {
            console.log(data.users);
            this.searchUser = data.users;
        },
        showModal() {
            this.isShow = true;
        },
    },
};
</script>
