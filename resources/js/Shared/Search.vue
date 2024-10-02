<template>
    <form>
        <div class="flex flex-wrap">
            <div class="relative w-[60%]">
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
                        <div v-for="category in categoryList" class="overflow-hidden border-t-2 p-4 border-gray-300">
                            <inertia-link
                                :href="`${otherParts.get(`${category}`)}`"
                                class=" text-2xl p-8 border-b-gray-300"
                            >
                                {{ category }}
                            </inertia-link>
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
                        <div v-for="detail in details" class="grid grid-cols-3 gap-4 overflow-hidden border-t-2 border-gray-300">
                            <a :href="`/catalog/product/${detail.dt_code}`" class="text-xl p-4 border-r-2 border-gray-300">{{detail.dt_code}}</a>
                            <p class="text-xl p-4 border-r-2 border-gray-300">{{editTitle(detail.dt_firm)}}</p>
                            <p class="text-xl p-4 border-r-2 border-gray-300">{{editTitle(detail.dt_typec)}}</p>
                        </div>
                    </div>

                </div>
                <inertia-link
                    :href="`/catalog/search?searchQ=${searchQuery}`"
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
                </inertia-link>
            </div>
            <div class="flex space-x-2 ml-[80px]">
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

<script>
import axios from "axios";
import debounce from "lodash.debounce";
import { editDetailTitle } from "@/Services/TitleService";
import {otherParts} from "@/Store/index.js";

export default {
    computed: {
        otherParts() {
            return otherParts
        }
    },
    props: {
        link: {
            type: String,
            default: "",
        },
    },
    data() {
        return {
            searchQuery: "",
            details: [],
            search: '',
            categoryList: []
        };
    },
    methods: {
        getSearchingDetails: debounce(function () {
            let categories = Array.from(otherParts.keys()).filter(key => key.includes(this.searchQuery));
            this.categoryList = Array.from(categories);
            if (this.searchQuery === "") {
                this.details = [];
                this.categoryList = [];
            } else {
                axios
                    .get(`${this.link}=${this.searchQuery}`)
                    .then((res) => {
                        console.log(res.data.details);
                        this.details = res.data.details;
                        this.search = res.data.search;
                    })
                    .catch((err) => console.log(err));
            }
        }, 1000),
        editTitle(res) {
            return editDetailTitle(res);
        },
    },
};
</script>
