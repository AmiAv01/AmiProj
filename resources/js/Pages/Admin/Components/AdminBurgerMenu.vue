<script setup>
import {adminMenuItems} from "@/Store/index.js";
import AdminButton from "@/Components/AdminButton.vue";

const props = defineProps({
    isShow:{
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <ul class="w-[100vw] h-[100vh] absolute top-[50px] bg-black " v-show="isShow">
        <li v-for="[key, value] in adminMenuItems">
            <AdminButton
                :href="`${value}`"
                class="flex items-center text-white p-2 pl-11 w-full text-base font-medium  rounded-lg transition duration-75 group hover:bg-gray-100 "
            >{{key}}</AdminButton>
        </li>
    </ul>
</template>

