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
    <li class="px-4 py-4 border-r-2 border-white">
        <inertia-link
            :href="`${link}`"
            class="text-white w-full block group/link relative"
            ><span
                class="absolute top-10 left-0 w-0 h-0.5 bg-white group-hover/link:w-full group-hover:transition-all"
            ></span
            >{{ value }}</inertia-link
        >
    </li>
</template>
