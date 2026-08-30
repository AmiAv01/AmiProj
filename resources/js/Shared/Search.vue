<template>
    <form @submit.prevent="handleSearch">
        <div class="flex flex-wrap justify-around">
            <div class="relative w-full md:w-[60%]">
                <input
                    type="search"
                    id="search-dropdown"
                    class="block px-2.5 py-2.5 w-full z-20 text-md text-gray-900 bg-gray-50 rounded-e-lg rounded-s-gray-100 rounded-s-2 rounded-tl-[15px] rounded-bl-[15px] border focus:ring-0 border-gray-300"
                    placeholder="Поиск по артикулу + деталировка"
                    v-model="searchQuery"
                    @input="getSearchingDetails"
                />
                <div v-if="categoryList.length !== 0"  class="absolute top-[45px] rounded-[15px]  z-10 w-full h-[200px] overflow-y-auto">
                    <div  class="bg-white  pt-4  border-b-6 border-gray-300 ">
                        <div class="overflow-hidden">
                            <p class="font-bold text-xl px-4">Категории</p>
                        </div>
                        <div v-for="category in categoryList" :key="category" class="overflow-hidden border-t-2 p-4 border-gray-300">
                            <spa-link
                                :href="`${otherParts.get(`${category}`)}`"
                                class=" text-2xl p-8 border-b-gray-300"
                            >
                                {{ category }}
                            </spa-link>
                        </div>
                    </div>
                </div>
                <div
                    class="absolute top-[45px] rounded-[15px]  z-10 w-full h-[200px] overflow-y-auto"
                    v-if="details.length !== 0"
                >
                    <!--detail-list :details="details" /!-->
                    <div  class="bg-white  pt-4  border-b-2 border-gray-300">
                        <div class="grid grid-cols-3 gap-4 overflow-hidden">
                            <p class="font-bold text-lg px-4">Код</p>
                            <p class="font-bold text-lg ">Бренд</p>
                            <p class="font-bold text-lg px-4">Наименование</p>
                        </div>
                        <div v-for="detail in details" :key="`${detail.dt_code}-${detail.dt_firm}`" class="grid grid-cols-3 gap-4 overflow-hidden border-t-2 border-gray-300">
                            <spa-link :href="`/catalog/product/${detail.dt_code}`" class="text-xl p-4 border-r-2 border-gray-300">{{detail.dt_code}}</spa-link>
                            <p class="text-xl p-4 border-r-2 border-gray-300">{{editTitle(detail.dt_firm)}}</p>
                            <p class="text-xl p-4 border-r-2 border-gray-300">{{editTitle(detail.dt_typec)}}</p>
                        </div>
                    </div>

                </div>
                <button
                    type="submit"
                    class="absolute flex items-center top-0 end-0 p-2.5 h-full font-medium text-white bg-green-700 rounded-e-lg border border-green-700 hover:bg-green-800 focus:outline-none"
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
            <div class="flex space-x-2 mt-2">
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


