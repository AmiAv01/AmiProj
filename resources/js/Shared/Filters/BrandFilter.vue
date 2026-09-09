<template>
    <div
        v-show="isShow"
        :class="isMobile ? 'fixed inset-x-4 top-24 z-50 max-h-[calc(100vh-8rem)] overflow-y-auto' : 'block'"
        class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm"
    >
        <div :class="`flex w-full items-center justify-between ${bgHeaderColor} px-5 py-4`">
            <p
                class="text-xl font-semibold text-white"
            >
                Бренды
            </p>
            <button
                type="button"
                @click="closeModal"
                v-if="isMobile"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-3xl leading-none text-white transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white"
                aria-label="Закрыть фильтр"
            >
                &times;
            </button>
        </div>

        <form class="flex flex-col p-5">
            <input
                class="mb-4 h-11 w-full rounded-lg border-gray-300 px-3 text-sm shadow-sm focus:border-green-600 focus:ring-green-600"
                placeholder="Введите название"
                v-model="searchQuery"
            />
            <ul class="mb-5 max-h-[300px] space-y-1 overflow-y-auto pr-1">
                <li
                    v-for="category in searchedCategories"
                    :key="category.fr_code"
                    class="rounded-lg transition hover:bg-green-50"
                >
                    <label class="flex cursor-pointer items-center px-2 py-2 text-sm text-gray-700">
                        <input
                            type="checkbox"
                            :value="category.fr_code"
                            class="mr-3 h-4 w-4 cursor-pointer rounded border-gray-300 text-green-700 focus:ring-green-600"
                            v-model="checked"
                        />
                        <span>{{ category.fr_name }}</span>
                    </label>
                </li>
            </ul>
            <menu-button
                :href="`${currentUrl}?filter[id]=${checked.join()}`"
                :attributes="`justify-center px-5 py-2.5 w-full text-sm mb-3`"
            >
                Подобрать<span v-if="checked.length"> ({{ checked.length }})</span>
            </menu-button>
            <button class="mx-auto text-sm text-gray-500 underline decoration-gray-300 underline-offset-4 transition hover:text-red-600" @click.prevent="resetChecked">
                Сбросить фильтр
            </button>
        </form>
    </div>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";

const props = defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
    clientBrands: {
        type: Object,
        default: null,
    },
    bgHeaderColor: {
        type: String,
        default: "bg-green-900",
    },
    bgBodyColor: {
        type: String,
        default: "bg-green-100",
    },
    isShow: {
        type: Boolean,
        default: false
    },
    isMobile: {
        type: Boolean,
        default: false
    }
})

const searchQuery = ref('');
const checked = ref([]);
const currentUrl = ref(null);

const emit = defineEmits(['closeModal'])

onMounted(() => {
    currentUrl.value = window.location.pathname;
    if (props.clientBrands !== null) {
        checked.value = props.clientBrands["id"].split(",");
    }
})

const searchedCategories = computed(() => {
    return props.categories.filter((name) => name.fr_name.toLowerCase().includes(searchQuery.value.toLowerCase()));
});

const resetChecked = () => {
    checked.value = [];
}
const closeModal = () => {
    emit("closeModal");
}
</script>
