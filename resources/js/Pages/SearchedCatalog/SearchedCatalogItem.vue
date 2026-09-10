<template>
    <article
        class="group flex w-full cursor-pointer items-center gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-green-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
        role="link"
        tabindex="0"
        :aria-label="`Открыть товар ${detail.dt_code}`"
        @click="openProduct"
        @keydown.enter="openProduct"
        @keydown.space.prevent="openProduct"
    >
        <div class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-gray-50 sm:h-28 sm:w-28">
            <img
                :src="detail.imageUrl || defaultImage"
                :alt="`Изображение товара ${detail.dt_code}`"
                class="h-full w-full object-contain"
                loading="lazy"
                @error="useDefaultImage"
            />
        </div>

        <div class="min-w-0 flex-grow">
            <h2 class="font-manrope text-lg font-semibold leading-snug text-gray-900 transition group-hover:text-green-800 sm:text-xl md:text-2xl">
                <a :href="productUrl" @click.stop>
                    {{ editTitle(detail.dt_typec) }} {{ detail.dt_code }}
                </a>
            </h2>
            <p class="mt-3 text-sm text-gray-600 sm:text-base">
                <span class="text-gray-400">Бренд:</span> {{ detail.dt_firm || '—' }}
            </p>
        </div>

        <svg class="hidden h-5 w-5 shrink-0 text-gray-300 transition group-hover:translate-x-0.5 group-hover:text-green-700 sm:block" aria-hidden="true" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </article>
</template>

<script setup>
import { computed } from "vue";
import { editDetailTitle } from "@/Services/TitleService";

const props = defineProps({
    detail: {
        type: Object,
        required: true,
    },
});

const defaultImage = '/no-photo--lg.png';
const productUrl = computed(() => `/catalog/product/${encodeURIComponent(props.detail.dt_code)}`);
const editTitle = (value) => editDetailTitle(value);
const openProduct = () => {
    window.location.href = productUrl.value;
};
const useDefaultImage = (event) => {
    if (event.target.src.endsWith(defaultImage)) return;
    event.target.src = defaultImage;
};
</script>
