<template>
    <layout>
        <news />
    </layout>
</template>

<script>
import UserLayout from "@/Shared/UserLayout.vue";
import News from "@/Shared/News.vue";

export default {
    components: {
        layout: UserLayout,
        news: News,
    },
};
</script>
