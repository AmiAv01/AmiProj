<script setup>
    const props = defineProps({
        post: Object,
    })
</script>

<template>
    <article
        :key="post.id"
        class="flex max-w-xl w-full flex-col mx-auto items-center xl:items-start justify-between border rounded-[15px] py-8 px-8"
    >
        <div class="flex items-center gap-x-4 text-xs">
            <time :datetime="post.date" class="text-gray-500 self-start">{{
                    post.date
                }}</time>
        </div>
        <div class="group relative">
            <img
                src="/public/build/aspl.png"
                class="w-[80%] mx-auto h-[300px] border rounded-[15px] border-white"
                alt=""
            />
            <h3
                class="mt-3 text-3xl font-bold leading-6 text-gray-900 group-hover:text-gray-600"
            >
                <a href="/public">
                    <span class="absolute inset-0" />
                    {{ post.title }}
                </a>
            </h3>

            <p
                class="mt-5 line-clamp-3 text-md leading-6 text-gray-600"
            >
                {{ post.description }}
            </p>
        </div>
    </article>
</template>

<style scoped>

</style>
