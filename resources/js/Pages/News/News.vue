<script setup>
import Layout from "@/Shared/UserLayout.vue";
import NewsList from "@/Pages/News/NewsList.vue";

const props = defineProps({
    posts: {
        type: Array,
        default: [],
    }
})
</script>

<template>
    <layout>
        <NewsList :posts="posts"/>
    </layout>
</template>


