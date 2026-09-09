<template>
    <aside
        class="fixed bottom-0 left-0 top-[72px] z-40 hidden w-64 border-r border-slate-200 bg-white lg:block"
        aria-label="Sidenav"
        id="drawer-navigation"
    >
        <div class="flex h-full flex-col overflow-y-auto px-3 py-6">
            <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">
                Управление
            </p>
            <ul class="space-y-1">
                <li v-for="[key, value] in adminMenuItems" :key="value">
                    <AdminButton
                        :href="`${value}`"
                    >{{key}}</AdminButton>
                </li>
            </ul>
            <div class="mt-auto rounded-xl bg-green-50 p-4 text-sm leading-6 text-green-900">
                <p class="font-semibold">AmiAvto Admin</p>
                <p class="mt-1 text-green-800/70">Управление каталогом и заказами</p>
            </div>
        </div>
    </aside>
</template>

<script setup>
import AdminButton from "@/Components/AdminButton.vue";
import {adminMenuItems} from "@/Store/index";
</script>
