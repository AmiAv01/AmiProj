<template>
    <tr>
        <th
            scope="row"
            class="whitespace-nowrap font-semibold text-slate-900"
        >
            {{ post.id }}
        </th>
        <td class="px-4 py-3">{{ post.title }}</td>
        <td class="px-4 py-3">{{ new Date(post.date).toLocaleDateString() }}</td>
        <td class="max-w-md">
            <p class="line-clamp-2">{{ post.description }}</p>
        </td>
        <td class="px-4 py-3">{{ post.name }}</td>

        <td class="flex items-center justify-end">
            <button
                :id="`${post.id}-button`"
                :data-dropdown-toggle="`${post.id}`"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-600"
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
                class="z-10 hidden w-44 divide-y divide-slate-100 rounded-xl border border-slate-200 bg-white shadow-xl"
            >
                <ul
                    class="py-1 text-sm text-gray-700 "
                    :aria-labelledby="`${post.id}-button`"
                >
                    <li>
                        <button
                            @click="showModal"
                            class="flex w-full px-4 py-2.5 hover:bg-slate-50"
                        >
                            Изменить
                        </button>
                    </li>
                </ul>
                <div class="py-1">
                    <button
                        @click = "store.deletePost(post.id)"
                        class="flex w-full px-4 py-2.5 text-sm text-red-700 hover:bg-red-50"
                    >
                        Удалить
                    </button>
                </div>
            </div>
        </td>
    </tr>
    <NewsEditForm
        @closeModal="isShow = false"
        @updated="emit('updated')"
        :is-show="isShow"
        :title="post.title"
        :description="post.description"
        :post-id="post.id"
        action-title="Сохранить изменения"
    />
</template>

<script setup>
import NewsEditForm from "@/Shared/Forms/NewsEditForm.vue";
import {ref} from "vue";
import {useNewsStore} from "@/Store/newsStore";

const isShow = ref(false);
const store = useNewsStore();
const emit = defineEmits(['updated']);
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
