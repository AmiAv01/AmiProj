<template>
    <Layout>
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
                    <article
                        v-for="post in posts.data"
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
                                src="../../../../public/build/aspl.png"
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
                </div>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "@/Shared/UserLayout.vue";

const props = defineProps({
    posts: {
        type: Array,
        default: [],
    }
})
</script>
