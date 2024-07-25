<template>
    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl">
            <div class="mx-auto max-w-2xl lg:mx-0">
                <p class="text-5xl font-bold tracking-tight text-gray-900">
                    Новости
                </p>
            </div>
            <div
                class="mx-auto mt-10 grid max-w-7xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3"
            >
                <article
                    v-for="post in posts.data"
                    :key="post.id"
                    class="flex max-w-xl flex-col items-start justify-between border rounded-[15px] py-8 px-8"
                >
                    <div class="flex items-center gap-x-4 text-xs">
                        <time :datetime="post.date" class="text-gray-500">{{
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
                            <a href="/">
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
</template>

<script>
export default {
    props: {
        posts: {
            type: Array,
            default: [],
        },
    },
};
</script>
