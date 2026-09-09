<template>
    <spa-link
        :href="`${href}`"
        class="group flex min-h-12 w-full items-center gap-3 rounded-xl px-3.5 py-3 text-base font-semibold transition"
        :class="isActive ? 'bg-green-700 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950'"
        ><slot
    /></spa-link>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    href: {
        type: String,
        default: "#",
    },
});

const isActive = computed(() => {
    if (typeof window === 'undefined') return false;
    return window.location.pathname.startsWith(props.href);
});
</script>
