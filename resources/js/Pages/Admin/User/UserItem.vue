<template>
    <tr class="border-b dark:border-gray-700">
        <th
            scope="row"
            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
        >
            <a :href="`/admin/users/${user.id}`">
                {{ user.id }}
            </a>
        </th>
        <td class="px-4 py-3">{{ user.name }}</td>
        <td class="px-4 py-3">{{ user.email }}</td>
        <td class="px-4 py-3">
            {{ user.isAdmin }}
        </td>

        <td class="px-4 py-3 flex items-center justify-end">
            <button
                :id="`${user.id}-button`"
                :data-dropdown-toggle="`${user.id}`"
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
                :id="`${user.id}`"
                class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
            >
                <ul
                    class="py-1 text-sm text-gray-700 dark:text-gray-200"
                    :aria-labelledby="`${user.id}-button`"
                >
                    <li>
                        <button
                            @click="showModal(user.id)"
                            class="flex w-full py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                        >
                            Изменить
                        </button>
                    </li>
                </ul>
                <div class="py-1">
                    <button
                        @click = "deleteUser(user.id)"
                        class="flex w-full py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                    >
                        Удалить
                    </button>
                </div>
            </div>
            <button @click="approveUser(user.id)" v-if="!user.approved" class="`flex items-center text-white bg-green-700  hover:bg-green-800  font-medium rounded-lg  text-center dark:bg-green-600 p-2 ml-2`">Подтвердить</button>
            <menu-button/>
        </td>
    </tr>
</template>

<script>
import NewsEditForm from "@/Shared/Forms/NewsEditForm.vue";
import MenuButton from "@/Components/MenuButton.vue";

export default {
    data() {
        return {
            isShow: false,
        };
    },
    props: {
        user: {
            type: Object,
            default: null,
        },
    },
    components: {
        MenuButton,
        "news-form": NewsEditForm,
    },
    methods: {
        showModal() {
            this.isShow = true;
        },
        deleteUser(id){
            axios.delete(`/admin/users/${id}`)
                .then(res => console.log(res.data))
                .catch(err => console.log(err))
        },
        approveUser(id){
            axios.put(`/admin/approve/${id}`)
                .then(res => {
                    console.log(res.data);
                    window.location.reload();
                })
                .catch(err => console.log(err))
        }
    },
};
</script>
