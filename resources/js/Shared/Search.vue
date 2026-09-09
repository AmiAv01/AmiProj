<template>
    <form @submit.prevent="handleSearch">
        <div class="flex flex-wrap justify-around gap-2">
            <div class="relative w-full md:w-[60%]">
                <input
                    type="search"
                    id="search-dropdown"
                    class="block h-12 w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-4 pr-28 text-base text-gray-900 shadow-sm transition focus:border-green-600 focus:ring-2 focus:ring-green-200"
                    placeholder="Поиск по артикулу + деталировка"
                    v-model="searchQuery"
                    @input="getSearchingDetails"
                />
                <div
                    v-if="categoryList.length !== 0 || details.length !== 0"
                    class="absolute top-full z-30 mt-2 max-h-[420px] w-full overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-2xl"
                >
                    <section v-if="categoryList.length !== 0">
                        <p class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Категории
                        </p>
                        <div class="divide-y divide-gray-100 border-t border-gray-100">
                            <spa-link
                                v-for="category in categoryList"
                                :key="category"
                                :href="`${otherParts.get(`${category}`)}`"
                                class="block px-4 py-3 text-base font-medium text-gray-800 transition hover:bg-green-50 hover:text-green-800 focus:bg-green-50 focus:outline-none"
                            >
                                {{ category }}
                            </spa-link>
                        </div>
                    </section>

                    <section
                        v-if="details.length !== 0"
                        :class="{ 'border-t border-gray-200': categoryList.length !== 0 }"
                    >
                        <p class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Найденные товары
                        </p>
                        <div class="overflow-x-auto border-t border-gray-200">
                            <div class="min-w-[640px]">
                                <div class="sticky top-0 grid grid-cols-[minmax(150px,1fr)_minmax(150px,1fr)_minmax(240px,1.4fr)] bg-gray-50 text-sm font-semibold text-gray-700">
                                    <span class="px-4 py-3">Код</span>
                                    <span class="border-l border-gray-200 px-4 py-3">Бренд</span>
                                    <span class="border-l border-gray-200 px-4 py-3">Наименование</span>
                                </div>
                                <spa-link
                                    v-for="detail in details"
                                    :key="`${detail.dt_code}-${detail.dt_firm}`"
                                    :href="`/catalog/product/${detail.dt_code}`"
                                    class="grid grid-cols-[minmax(150px,1fr)_minmax(150px,1fr)_minmax(240px,1.4fr)] border-t border-gray-200 text-base text-gray-800 transition hover:bg-green-50 focus:bg-green-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-600"
                                >
                                    <span class="px-4 py-3 font-semibold text-green-800">{{ detail.dt_code }}</span>
                                    <span class="border-l border-gray-200 px-4 py-3">{{ editTitle(detail.dt_firm) }}</span>
                                    <span class="border-l border-gray-200 px-4 py-3">{{ editTitle(detail.dt_typec) }}</span>
                                </spa-link>
                            </div>
                        </div>
                    </section>
                </div>
                <button
                    type="submit"
                    class="absolute end-0 top-0 flex h-12 items-center rounded-r-xl border border-green-700 bg-green-700 px-4 font-medium text-white transition hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-300"
                >
                    <svg
                        class="w-4 h-4"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 20 20"
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
                        />
                    </svg>
                    <span class="pl-2 text-md">Найти</span>
                </button>
            </div>
            <div class="mt-2 flex space-x-2">
                <svg
                    class="w-6 h-6 text-white dark:text-white"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 20 20"
                >
                    <path
                        stroke="currentColor"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10 6v4l3.276 3.276M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                    />
                </svg>
                <p class="text-white">Мы работаем: пн-пт - 9:00-18:00</p>
            </div>
        </div>
    </form>
</template>

<script setup>
import { ref, computed, onUnmounted } from 'vue';
import axios from 'axios';
import debounce from 'lodash.debounce';
import { editDetailTitle } from '@/Services/TitleService';
import { otherParts } from '@/Store/index';
import { api } from '@/api/client';
import { useRouter } from 'vue-router';

const props = defineProps({
    link: {
        type: String,
        default: "/catalog/autocomplete",
    },
});

const router = useRouter();
const searchQuery = ref('');
const details = ref([]);
const categoryList = ref([]);
let requestController = null;

const otherPartsData = computed(() => otherParts);

const getSearchingDetails = debounce(async () => {
    const query = searchQuery.value.trim();
    const normalizedQuery = query.toLocaleLowerCase();
    const categories = Array.from(otherPartsData.value.keys()).filter(key => key.toLocaleLowerCase().includes(normalizedQuery));
    categoryList.value = Array.from(categories);
    if (query === "") {
        details.value = [];
        categoryList.value = [];
        requestController?.abort();
        return;
    }

    requestController?.abort();
    requestController = new AbortController();
    try {
        const response = await api.get(props.link, {
            params: { searchQ: query },
            signal: requestController.signal,
        });
        details.value = response.data.data.details;
    } catch (reason) {
        if (!axios.isCancel(reason)) {
            details.value = [];
        }
    }
}, 1000);

const editTitle = (res) => editDetailTitle(res);

async function handleSearch(){
    const query = searchQuery.value.trim();
    if (!query) return;
    await router.push({ path: '/catalog/search', query: { searchQ: query } });
}

onUnmounted(() => {
    getSearchingDetails.cancel();
    requestController?.abort();
});
</script>

