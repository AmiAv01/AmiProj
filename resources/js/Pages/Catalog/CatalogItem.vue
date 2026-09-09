<template>
    <div
        class="group flex w-full cursor-pointer items-center gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-green-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
        role="link"
        tabindex="0"
        :aria-label="`Открыть товар ${detail.dt_invoice}`"
        @click="openProduct"
        @keydown.enter="openProduct"
        @keydown.space.prevent="openProduct"
    >

        <div class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-gray-50 sm:h-28 sm:w-28">
            <img
                src="/no-photo--lg.png"
                alt="Нет фотографии товара"
                class="h-full w-full object-contain"
            />
        </div>

        <div class="min-w-0 flex-grow">
            <h3 class="font-manrope text-lg font-semibold leading-snug text-gray-900 transition group-hover:text-green-800 sm:text-xl md:text-2xl">
                <a :href="`product/${detail.dt_invoice}`">
                    {{ editTitle(detail.dt_typec) }} {{ detail.dt_invoice }}
                </a>
            </h3>
            <div class="mt-3 flex flex-wrap gap-x-6 gap-y-1 text-sm text-gray-600 sm:text-base">
                <p><span class="text-gray-400">Cargo:</span> {{ detail.dt_cargo }}</p>
                <p><span class="text-gray-400">Бренд:</span> {{ detail.fr_code }}</p>
            </div>
        </div>

        <div
            v-if="!$page.props.auth.user"
            class="shrink-0"
            @click.stop
            @keydown.stop
        >
            <a
                href="/login"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-gray-50 text-gray-500 transition hover:border-green-300 hover:bg-green-50 hover:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-600"
                title="Войти для просмотра цены"
                aria-label="Войти для просмотра цены"
            >
                <svg class="h-4 w-4" aria-hidden="true" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="5" y="10" width="14" height="10" rx="2" stroke="currentColor" stroke-width="2" />
                    <path d="M8 10V7a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </a>
        </div>
        <svg class="hidden h-5 w-5 shrink-0 text-gray-300 transition group-hover:translate-x-0.5 group-hover:text-green-700 sm:block" aria-hidden="true" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>
</template>

<script setup>
import { editDetailTitle } from "@/Services/TitleService";
const props = defineProps({ detail: Object });
const editTitle = (res) => editDetailTitle(res);
const openProduct = () => {
    window.location.href = `product/${props.detail.dt_invoice}`;
};
</script>
