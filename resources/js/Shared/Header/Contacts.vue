<script setup>
import {onBeforeUnmount, onMounted, ref} from 'vue';

const isOpen = ref(false);
const container = ref(null);

const toggle = () => {
    isOpen.value = !isOpen.value;
};

const close = () => {
    isOpen.value = false;
};

const handleOutsideClick = (event) => {
    if (!container.value?.contains(event.target)) close();
};

const handleKeydown = (event) => {
    if (event.key === 'Escape') close();
};

onMounted(() => {
    document.addEventListener('click', handleOutsideClick);
    document.addEventListener('keydown', handleKeydown);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleOutsideClick);
    document.removeEventListener('keydown', handleKeydown);
});
</script>

<template>
    <div ref="container" class="relative">
        <button
            type="button"
            class="flex items-center rounded-lg px-3 py-2 text-lg text-white transition hover:bg-white/5 hover:text-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 md:p-0 md:hover:bg-transparent"
            :aria-expanded="isOpen"
            aria-controls="header-contacts-menu"
            @click="toggle"
        >
            Контакты
            <svg class="ms-1.5 h-2.5 w-2.5 transition-transform" :class="{ 'rotate-180': isOpen }" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
            </svg>
        </button>

        <div
            v-show="isOpen"
            id="header-contacts-menu"
            class="absolute right-0 top-full z-50 mt-4 w-[340px] max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-2xl"
        >
            <div class="border-b border-slate-100 px-5 py-4">
                <p class="text-sm font-semibold text-slate-900">Связаться с нами</p>
                <p class="mt-1 text-xs text-slate-500">Ответим в рабочее время</p>
            </div>

            <div class="space-y-1 p-2">
                <a href="tel:+375296308535" class="group flex items-center gap-3 rounded-xl px-3 py-3 transition hover:bg-green-50 focus:bg-green-50 focus:outline-none" @click="close">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-700 group-hover:bg-white">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M2 3.5A1.5 1.5 0 0 1 3.5 2h1.15c.69 0 1.29.47 1.46 1.14l.7 2.8a1.5 1.5 0 0 1-.42 1.44L5.31 8.46a11.04 11.04 0 0 0 6.23 6.23l1.08-1.08a1.5 1.5 0 0 1 1.44-.42l2.8.7A1.5 1.5 0 0 1 18 15.35v1.15a1.5 1.5 0 0 1-1.5 1.5H16C8.27 18 2 11.73 2 4v-.5Z" /></svg>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-xs font-medium text-slate-500">Телефон</span>
                        <span class="mt-0.5 block text-base font-semibold text-slate-900 group-hover:text-green-800">+375 29 630-85-35</span>
                    </span>
                </a>

                <a href="mailto:amiauto.minsk@gmail.com" class="group flex items-center gap-3 rounded-xl px-3 py-3 transition hover:bg-green-50 focus:bg-green-50 focus:outline-none" @click="close">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-700 group-hover:bg-white">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M2.94 5.5 9.2 9.98a1.4 1.4 0 0 0 1.6 0l6.26-4.48A2.5 2.5 0 0 0 14.5 3h-9a2.5 2.5 0 0 0-2.56 2.5ZM18 7.09l-6.33 4.52a2.9 2.9 0 0 1-3.34 0L2 7.09v7.41A2.5 2.5 0 0 0 4.5 17h11a2.5 2.5 0 0 0 2.5-2.5V7.09Z" /></svg>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-xs font-medium text-slate-500">Email</span>
                        <span class="mt-0.5 block break-all text-sm font-semibold text-slate-900 group-hover:text-green-800">amiauto.minsk@gmail.com</span>
                    </span>
                </a>
            </div>

            <div class="border-t border-slate-100 bg-slate-50 px-5 py-3">
                <spa-link href="/info" class="inline-flex items-center text-sm font-semibold text-green-700 transition hover:text-green-900" @click="close">
                    Адрес и время работы
                    <svg class="ml-1.5 h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3.25 10a.75.75 0 0 1 .75-.75h10.19l-3.22-3.22a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H4a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" /></svg>
                </spa-link>
            </div>
        </div>
    </div>
</template>
