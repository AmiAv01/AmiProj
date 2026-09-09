<template>
    <aside class="admin-panel xl:sticky xl:top-24">
        <div class="border-b border-slate-200 px-5 py-5">
            <p class="text-lg font-semibold text-slate-900">Статус заказа</p>
            <p class="mt-1 text-sm text-slate-500">Отфильтруйте список</p>
        </div>
        <form class="flex flex-col p-5" @submit.prevent>
            <ul class="space-y-2">
                <li
                    v-for="(status, index) in statuses"
                    :key="index"
                    class="rounded-lg transition hover:bg-green-50"
                >
                    <label class="flex cursor-pointer items-center px-2 py-2.5 text-base text-slate-700">
                        <input
                            type="checkbox"
                            :value="status"
                            class="mr-3 h-5 w-5 cursor-pointer rounded border-slate-300 text-green-700 focus:ring-green-600"
                            v-model="checked"
                        />
                        <span>{{ status }}</span>
                    </label>
                </li>
            </ul>
            <menu-button
                :href="`${currentUrl}?filter[id]=${checked.join()}`"
                :attributes="`justify-center px-5 py-2.5 w-full text-base mt-5`"
            >
                Применить<span v-if="checked.length"> ({{ checked.length }})</span>
            </menu-button>
            <button type="button" class="mx-auto mt-3 text-sm text-slate-500 underline decoration-slate-300 underline-offset-4 transition hover:text-red-700" @click="resetChecked">
                Сбросить фильтр
            </button>
        </form>
    </aside>
</template>

<script setup>

import {onMounted, ref} from "vue";

const currentUrl = ref(null);
const statuses = ["Новый", "Принят", "Выполнен"];
const checked = ref([]);

onMounted(() => {
    currentUrl.value = window.location.pathname;
})

const resetChecked = () => {
    checked.value = [];
};
</script>
