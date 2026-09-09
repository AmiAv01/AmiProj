<script setup>
import {adminMenuItems} from "@/Store/index";
import AdminButton from "@/Components/AdminButton.vue";

defineProps({
    isShow:{
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <div class="fixed inset-x-0 top-[72px] z-40 border-b border-slate-200 bg-white p-4 shadow-xl lg:hidden" v-show="isShow">
        <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Управление</p>
        <ul class="space-y-1">
        <li v-for="[key, value] in adminMenuItems" :key="value">
            <AdminButton
                :href="`${value}`"
            >{{key}}</AdminButton>
        </li>
        </ul>
    </div>
</template>
