<script setup>
    const props = defineProps({
        title: {
            type: String,
            default: ''
        },
        link: {
            type: String,
        },
    })
</script>

<template>
    <li class="w-full border-b-2 border-white flex items-center cursor-pointer justify-start p-6 text-white text-xl">
        <inertia-link :href="`${link}`">
            <span>{{title}}</span>
        </inertia-link>
    </li>
</template>

<style scoped>

</style>
