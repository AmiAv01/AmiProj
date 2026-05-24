<template>
    <div v-show="isShow" :class="isMobile ? 'absolute top-[380px] left-[30px] z-10' : 'block '" class="rounded-[15px] bg-green-300 shadow-md">
        <div :class="`flex justify-between items-center w-full ${bgHeaderColor} rounded-tr-[15px] rounded-tl-[15px]`">
            <p
                :class="`text-3xl text-white ${bgHeaderColor} rounded-tr-[15px] rounded-tl-[15px] font-bold p-4`"
            >
                Бренды
            </p>
            <p
                @click="closeModal"
                v-if="isMobile"
                class="text-white text-5xl cursor-pointer hover:text-red-400"
            >
                &times;
            </p>
        </div>

        <form :class="`flex flex-col p-4 pb-6 rounded-bl-[15px] rounded-br-[15px] bg-gray-50/50`">
            <input
                class="rounded-[15px] mb-4 h-[40px] px-3"
                placeholder="Введите название"
                v-model="searchQuery"
                @input="searchCategory"
            />
            <ul :class="`max-h-[180px] overflow-y-auto mb-4 p-2`">
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
                :href="`${currentUrl}?filter[id]=${checked.join()}`"
                :attributes="`px-5 py-2 mx-auto text-lg mb-2`"
            >
                Подобрать
            </menu-button>
            <button class="text-sm text-gray-700 underline mx-auto hover:text-red-500" @click.prevent="resetChecked">
                Сбросить фильтр
            </button>
        </form>
    </div>
</template>

<script setup>
import axios from "axios";
import {computed, onMounted, ref} from "vue";

const props = defineProps({
    categories: {
        type: Array,
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
    console.log(`Current URL => ${currentUrl.value}`);
    console.log(props.clientBrands);
    console.log(props.isMobile)
    if (props.clientBrands !== null) {
        checked.value = props.clientBrands["id"].split(",");
    }
})

const searchedCategories = computed(() => {
    return props.categories.filter((name) => name.fr_name.toLowerCase().includes(searchQuery.value.toLowerCase()));
});

const getCheckbox = () => {
    console.log(checked.value);
}

const toggleForm = () => {
    isShow.value = !isShow.value
}

const getFilteredData = () => {
    axios.get().then((res) => console.log(res));
}
const resetChecked = () => {
    checked.value = [];
}
const closeModal = () => {
    emit("closeModal");
}
</script>


