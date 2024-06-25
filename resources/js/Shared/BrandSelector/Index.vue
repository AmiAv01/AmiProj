<template>
    <div class="rounded-[15px] h-[450px] bg-green-300">
        <p
            class="text-3xl text-white bg-green-700 rounded-tr-[15px] rounded-tl-[15px] font-bold p-4"
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
            <ul class="h-[300px] bg-green-300 overflow-y-auto p-6">
                <li
                    v-for="category in searchedCategories"
                    :key="category.fr_code"
                    class="py-[4px]"
                >
                    <input
                        type="checkbox"
                        :value="category.fr_name"
                        class="mr-2 rounded-[5px] w-5 h-5 focus:ring-0 focus:outline-none cursor-pointer"
                        v-model="checked"
                    />
                    <label class="text-lg">{{ category.fr_name }}</label>
                </li>
            </ul>
            <button
                class="bg-green-700 hover:bg-green-500 text-lg text-white mt-4 mx-auto bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 flex items-center justify-center"
            >
                Подобрать
            </button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            searchQuery: "",
            searchedCategories: this.categories,
        };
    },
    props: {
        categories: {
            type: Array,
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
    },
};
</script>
