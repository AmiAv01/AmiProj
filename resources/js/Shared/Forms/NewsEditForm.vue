<template>
    <modal
        :show="isShow"
        :title="`Редактирование новости`"
        @closeModal="closeModal"
    >
        <div class="w-full">
            <form
                class="bg-white shadow-md flex flex-col rounded px-8 pt-6 pb-8 "
            >
                <div class="mb-4">
                    <label
                        class="block text-gray-700 text-sm font-bold mb-2"
                        for="username"
                    >
                        Название
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="text"
                        v-model="currentTitle"
                        placeholder="Ввести название"
                    />
                </div>
                <div class="mb-6">
                    <label
                        class="block text-gray-700 text-sm font-bold mb-2"
                        for="password"
                    >
                        Описание
                    </label>
                    <textarea
                        v-model="currentDescription"
                        placeholder="Ввести описание"
                        class="shadow appearance-none border  rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                    />
                </div>
                <button
                    @click.prevent = "store.editPost(props.postId, currentTitle, currentDescription)"
                    class="bg-green-700 rounded-lg text-white px-5 py-2.5 mx-auto text-lg mt-4"
                >
                    {{ actionTitle }}
                </button>
            </form>
        </div>
    </modal>
</template>

<script setup>
import axios from "axios";
import {ref} from "vue";
import {useNewsStore} from "@/Store/newsStore.js";

const props = defineProps({
    title: {
        type: String,
        default: "",
    },
    description: {
        type: String,
        default: "",
    },
    isShow: {
        type: Boolean,
        default: false,
    },
    postId: {
        type: Number,
        default: 0,
    },
    actionTitle: {
        type: String,
        default: '',
    }
})

const emit = defineEmits(['closeModal'])
const store = useNewsStore();
const currentTitle = ref(props.title)
const currentDescription = ref(props.description)

const closeModal = () => {
    emit("closeModal");
}

</script>
