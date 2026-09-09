<script setup>
defineProps({
    value: {
        type: String,
    },
    link: {
        type: String,
    },
});
</script>

<template>
    <li class="min-w-0">
        <spa-link
            :href="`${link}`"
            class="group/link flex h-full w-full items-center justify-between gap-3 rounded-lg border border-transparent px-4 py-3 text-base font-medium text-gray-700 transition hover:border-green-200 hover:bg-green-50 hover:text-green-800 focus:border-green-300 focus:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500"
        >
            <span>{{ value }}</span>
            <svg
                class="h-4 w-4 shrink-0 -translate-x-1 text-green-700 opacity-0 transition group-hover/link:translate-x-0 group-hover/link:opacity-100 group-focus/link:translate-x-0 group-focus/link:opacity-100"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <path
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="m9 18 6-6-6-6"
                />
            </svg>
        </spa-link>
    </li>
</template>
