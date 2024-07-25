<template>
    <div :class="`rounded-[15px] h-[450px] ${bgBodyColor}`">
        <p
            :class="`text-3xl text-white ${bgHeaderColor} rounded-tr-[15px] rounded-tl-[15px] font-bold p-4`"
        >
            Бренды
        </p>
        <form class="h-[350px] flex flex-col">
            <input
                class="rounded-[15px] m-4 h-[40px]"
                placeholder="Введите название"
                v-model="searchQuery"
                @input="searchCategory"
            />
            <ul :class="`h-[300px] ${bgBodyColor} overflow-y-auto p-6`">
                <li
                    v-for="category in searchedCategories"
                    :key="category.fr_code"
                    class="py-[4px]"
                >
                    <input
                        type="checkbox"
                        :value="category.fr_code"
                        class="mr-2 rounded-[5px] w-5 h-5 focus:ring-0 focus:outline-none cursor-pointer"
                        v-model="checked"
                    />
                    <label class="text-lg">{{ category.fr_name }}</label>
                </li>
            </ul>
            <menu-button
                :href="`${this.currentUrl}?filter[id]=${this.checked.join()}`"
                :attributes="`px-5 py-2.5 mx-auto text-lg mt-4`"
            >
                Подобрать
            </menu-button>
            <button class="text-lg mx-auto" @click="resetChecked">
                Сбросить фильтр
            </button>
        </form>
    </div>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            searchQuery: "",
            searchedCategories: this.categories,
            checked: [],
            currentUrl: null,
        };
    },
    props: {
        categories: {
            type: Array,
        },
        clientBrands: {
            type: Object,
            default: null,
        },
        bgHeaderColor: {
            type: String,
            default: "bg-green-700",
        },
        bgBodyColor: {
            type: String,
            default: "bg-green-300",
        },
    },
    methods: {
        searchCategory() {
            console.log(this.categories);
            this.searchedCategories = [...this.categories].filter((name) =>
                name.fr_name
                    .toLowerCase()
                    .includes(this.searchQuery.toLowerCase())
            );
        },
        getCheckbox() {
            console.log(this.checked);
        },
        getFilteredData() {
            axios.get().then((res) => console.log(res));
        },
        resetChecked() {
            this.checked = [];
        },
    },
    created: function () {
        this.currentUrl = window.location.pathname;
        console.log(`Current URL => ${this.currentUrl}`);
        console.log(this.clientBrands);
        if (this.clientBrands !== null) {
            this.checked = this.clientBrands["id"].split(",");
        }
    },
};
</script>
