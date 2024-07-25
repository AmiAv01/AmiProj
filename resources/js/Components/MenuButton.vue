<template>
    <inertia-link
        :href="`${href}`"
        :class="`flex items-center text-white bg-green-700  hover:bg-green-800  font-medium rounded-lg   text-center dark:bg-green-600  ${attributes}`"
    >
        <slot />
    </inertia-link>
</template>

<script>
export default {
    props: {
        href: "",
        attributes: {
            type: String,
            default: "",
        },
    },
};
</script>
