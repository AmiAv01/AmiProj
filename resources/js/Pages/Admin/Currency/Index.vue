<template>
    <admin-layout>
        <p class="text-5xl font-bold tracking-tight text-gray-900 mb-10">
            Курс валют
        </p>
        <div class="flex flex-col sm:flex-row items-center sm:items-start">
            <input
                class="shadow appearance-none border rounded-md w-[300px] h-[50px] px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                type="number"
                step="0.1"
                min="1"
                v-model="currencyValue"
                placeholder="Ввести курс валют"
            />
            <button
                @click="changeCurrency"
                class="bg-green-700 ml-2 rounded-lg mt-2 sm:mt-0 text-white w-[150px] max-w-[150px] h-[50px]  text-lg "
            >
                Изменить
            </button>
        </div>
    </admin-layout>
</template>

<script setup>
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";
import {onMounted, ref} from "vue";

const props = defineProps({
    currency: {
        type: String,
        default: 0
    }
});
let currencyValue = ref(0);

function changeCurrency(){
    axios.post(`/admin/resource/currency`, {currency: currencyValue.value})
        .then(res => console.log(res.data))
        .catch(err => console.error(err))
}

onMounted(() => {
    currencyValue.value = props.currency;
})

</script>

