<template>
    <modal
        :show="isShow"
        title="Новая новость"
        max-width="xl"
        @closeModal="closeModal"
    >
        <form class="p-5 sm:p-6" @submit.prevent="addPost">
                <p class="mb-6 text-base leading-7 text-slate-500">Добавьте заголовок и короткое описание — новость появится на сайте сразу после сохранения.</p>
                <div>
                    <label class="block text-base font-semibold text-slate-900" for="news-create-title">Заголовок</label>
                    <input
                        id="news-create-title"
                        class="admin-input mt-2"
                        type="text"
                        required
                        maxlength="255"
                        v-model="currentTitle"
                        placeholder="Например, новое поступление запчастей"
                    />
                    <div class="mt-1 flex justify-between gap-4 text-sm">
                        <p v-if="errors.title" class="font-medium text-red-700">{{ errors.title }}</p>
                        <span class="ml-auto text-slate-400">{{ currentTitle.length }}/255</span>
                    </div>
                </div>
                <div class="mt-5">
                    <label class="block text-base font-semibold text-slate-900" for="news-create-description">Описание</label>
                    <textarea
                        id="news-create-description"
                        v-model="currentDescription"
                        placeholder="Коротко расскажите, что произошло"
                        required
                        maxlength="255"
                        rows="5"
                        class="admin-input mt-2 resize-y py-3 leading-7"
                    />
                    <div class="mt-1 flex justify-between gap-4 text-sm">
                        <p v-if="errors.description" class="font-medium text-red-700">{{ errors.description }}</p>
                        <span class="ml-auto text-slate-400">{{ currentDescription.length }}/255</span>
                    </div>
                </div>
                <p v-if="errors.general" class="mt-4 rounded-xl bg-red-50 px-4 py-3 text-sm font-medium text-red-700" role="alert">{{ errors.general }}</p>
                <div class="mt-7 flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                    <button type="button" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-base font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2" @click="closeModal">Отмена</button>
                    <button type="submit" :disabled="isSubmitting" class="admin-button-primary">
                        {{ isSubmitting ? 'Сохраняем…' : actionTitle }}
                    </button>
                </div>
        </form>
    </modal>
</template>

<script setup>
import axios from "axios";
import {reactive, ref} from "vue";
import {useNewsStore} from "@/Store/newsStore";

const props = defineProps({
    isShow: {
        type: Boolean,
        default: false,
    },
    actionTitle: {
        type: String,
        default: 'Добавить новость',
    }
});

const emit = defineEmits(['closeModal', 'created']);
const store = useNewsStore();
const currentTitle = ref("");
const currentDescription = ref("");
const isSubmitting = ref(false);
const errors = reactive({ title: '', description: '', general: '' });

const addPost = async () => {
    if (isSubmitting.value) {
        return;
    }

    isSubmitting.value = true;
    errors.title = '';
    errors.description = '';
    errors.general = '';

    try {
        await store.addPost(currentTitle.value, currentDescription.value);
        currentTitle.value = "";
        currentDescription.value = "";
        emit("created");
        closeModal();
    } catch (reason) {
        if (axios.isAxiosError(reason) && reason.response?.status === 422) {
            errors.title = reason.response.data.errors?.title?.[0] ?? '';
            errors.description = reason.response.data.errors?.description?.[0] ?? '';
        } else {
            errors.general = 'Не удалось добавить новость. Попробуйте ещё раз.';
        }
    } finally {
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    if (isSubmitting.value) return;
    errors.title = '';
    errors.description = '';
    errors.general = '';
    emit("closeModal");
}

</script>
