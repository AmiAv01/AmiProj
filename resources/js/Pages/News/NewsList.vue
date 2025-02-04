<template>
    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-4 xl:px-0">
            <div class="mx-auto max-w-2xl lg:mx-0">
                <p class="text-5xl font-bold tracking-tight text-gray-900">
                    Новости
                </p>
            </div>
            <div
                class="mx-auto my-0 mt-10 grid w-full grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16  md:grid-cols-2 xl:grid-cols-3"
            >
                <NewsItem v-for="post in posts.data" :post="post"/>
            </div>
        </div>
    </div>
</template>

<script setup>
import NewsItem from "@/Pages/News/NewsItem.vue";

const props = defineProps({
    posts: {
        type: Array,
        default: [],
    }
})
</script>
