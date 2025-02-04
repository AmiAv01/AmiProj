<template>
    <AdminLayout>
        <section class="p-3 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div
                    class="bg-white relative shadow-md sm:rounded-lg overflow-hidden"
                >
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4"
                    >
                        <Search
                            :placeholder="`Найти пользователя`"
                            :link="`../admin/api/search?category=users&searchQ`"
                            @setData="searchData"
                        />
                    </div>
                    <div class="overflow-x-auto">
                        <table
                            class="w-full text-sm text-left text-gray-500 "
                        >
                            <thead
                                class="text-xs text-gray-700  bg-gray-50 "
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
                            <UserItem
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
        type: Array,
        default: [],
    }
})

let searchUser = ref(props.users);

function searchData(data) {
    console.log(data.users);
    searchUser.value = data.users;
}

</script>
