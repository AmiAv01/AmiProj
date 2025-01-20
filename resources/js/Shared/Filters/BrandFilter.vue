<template>
    <div v-show="isShow" :class="isMobile ? 'absolute top-[380px] left-[30px] z-10' : 'block '" class="rounded-[15px] h-[450px] rounded-bl-[15px] rounded-br-[15px]">
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

        <form :class="`h-[350px] flex flex-col rounded-bl-[15px] rounded-br-[15px]  rounded-tr-0 rounded-tl-0 ${bgBodyColor}`">
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
                :href="`${currentUrl}?filter[id]=${checked.join()}`"
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
        default: "bg-green-700",
    },
    bgBodyColor: {
        type: String,
        default: "bg-green-300",
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


