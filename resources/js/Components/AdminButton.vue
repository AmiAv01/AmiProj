<template>
    <inertia-link
        :href="route(`${href}`)"
        class="flex items-center p-6 pl-11 w-full text-lg font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100"
        ><slot
    /></inertia-link>
</template>

<script>
export default {
    props: {
        href: {
            type: String,
            default: "#",
        },
    },
};
</script>
