<template>
    <modal
        :show="isShow"
        title="Редактирование новости"
        max-width="xl"
        @closeModal="closeModal"
    >
        <form class="p-5 sm:p-6" @submit.prevent="editPost">
                <p class="mb-6 text-base leading-7 text-slate-500">Измените содержание новости. После сохранения список обновится автоматически.</p>
                <div>
                    <label class="block text-base font-semibold text-slate-900" :for="`news-edit-title-${postId}`">Заголовок</label>
                    <input
                        :id="`news-edit-title-${postId}`"
                        class="admin-input mt-2"
                        type="text"
                        required
                        maxlength="255"
                        v-model="currentTitle"
                        placeholder="Введите заголовок"
                    />
                    <div class="mt-1 flex justify-between gap-4 text-sm">
                        <p v-if="errors.title" class="font-medium text-red-700">{{ errors.title }}</p>
                        <span class="ml-auto text-slate-400">{{ currentTitle.length }}/255</span>
                    </div>
                </div>
                <div class="mt-5">
                    <label class="block text-base font-semibold text-slate-900" :for="`news-edit-description-${postId}`">Описание</label>
                    <textarea
                        :id="`news-edit-description-${postId}`"
                        v-model="currentDescription"
                        placeholder="Введите описание"
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
                    <button type="submit" :disabled="isSubmitting || !hasChanges" class="admin-button-primary">
                        {{ isSubmitting ? 'Сохраняем…' : actionTitle }}
                    </button>
                </div>
        </form>
    </modal>
</template>

<script setup>
import axios from "axios";
import {computed, reactive, ref, watch} from "vue";
import {useNewsStore} from "@/Store/newsStore";

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
        default: 'Сохранить изменения',
    }
})

const emit = defineEmits(['closeModal', 'updated'])
const store = useNewsStore();
const currentTitle = ref(props.title)
const currentDescription = ref(props.description)
const isSubmitting = ref(false);
const errors = reactive({ title: '', description: '', general: '' });
const hasChanges = computed(() => currentTitle.value !== props.title || currentDescription.value !== props.description);

watch(() => props.isShow, (isOpen) => {
    if (isOpen) {
        currentTitle.value = props.title;
        currentDescription.value = props.description;
        errors.title = '';
        errors.description = '';
        errors.general = '';
    }
});

const editPost = async () => {
    if (isSubmitting.value || !hasChanges.value) return;
    isSubmitting.value = true;
    errors.title = '';
    errors.description = '';
    errors.general = '';

    try {
        await store.editPost(props.postId, currentTitle.value, currentDescription.value);
        emit('updated');
        emit('closeModal');
    } catch (reason) {
        if (axios.isAxiosError(reason) && reason.response?.status === 422) {
            errors.title = reason.response.data.errors?.title?.[0] ?? '';
            errors.description = reason.response.data.errors?.description?.[0] ?? '';
        } else {
            errors.general = 'Не удалось сохранить изменения. Попробуйте ещё раз.';
        }
    } finally {
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    if (isSubmitting.value) return;
    emit("closeModal");
}

</script>
