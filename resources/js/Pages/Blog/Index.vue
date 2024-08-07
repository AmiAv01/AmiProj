<template>
    <layout>
        <news-list :posts="news" />
    </layout>
</template>

<script>
import NewsList from "@/Shared/News/NewsList.vue";

export default {
    components: {
        "news-list": "NewsList",
    },
    created() {
        console.log(this.news);
    },
};
</script>

<script setup>
defineProps({
    news: Array,
});
</script>
