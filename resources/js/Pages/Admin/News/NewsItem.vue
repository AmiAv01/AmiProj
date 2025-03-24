<template>
    <tr class="border-b ">
        <th
            scope="row"
            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap "
        >
            {{ post.id }}
        </th>
        <td class="px-4 py-3">{{ post.title }}</td>
        <td class="px-4 py-3">{{ new Date(post.date).toLocaleDateString() }}</td>
        <td class="px-4 py-3">
            {{ post.description }}
        </td>
        <td class="px-4 py-3">{{ post.name }}</td>

        <td class="px-4 py-3 flex items-center justify-end">
            <button
                :id="`${post.id}-button`"
                :data-dropdown-toggle="`${post.id}`"
                class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none "
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
                :id="`${post.id}`"
                class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow "
            >
                <ul
                    class="py-1 text-sm text-gray-700 "
                    :aria-labelledby="`${post.id}-button`"
                >
                    <li>
                        <button
                            @click="showModal(post.id)"
                            class="flex w-full py-2 px-4 hover:bg-gray-100 "
                        >
                            Изменить
                        </button>
                    </li>
                </ul>
                <div class="py-1">
                    <button
                        @click = "store.deletePost(post.id)"
                        class="flex w-full py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 "
                    >
                        Удалить
                    </button>
                </div>
            </div>
        </td>
    </tr>
    <NewsEditForm
        @closeModal="isShow = false"
        :show="isShow"
        :title="post.title"
        :description="post.description"
        :postId="post.id"
        :actionTitle="`Изменить`"
    />
</template>

<script setup>
import NewsEditForm from "@/Shared/Forms/NewsEditForm.vue";
import {ref} from "vue";
import {useNewsStore} from "@/Store/newsStore.js";

const isShow = ref(false);
const store = useNewsStore();
const props = defineProps({
    post: {
        type: Object,
        default: null,
    }
})

const showModal = () => {
    isShow.value = true;
}
</script>
