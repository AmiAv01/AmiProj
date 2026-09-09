<script setup>
    import { computed } from "vue";

    const props = defineProps({
        post: Object,
    })

    const newsImage = computed(() => props.post?.image_url || props.post?.image || null);
</script>

<template>
    <article class="flex min-h-64 w-full flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md">
        <div v-if="newsImage" class="aspect-[16/9] w-full overflow-hidden bg-gray-100">
            <img
                :src="newsImage"
                :alt="post.image_alt || post.title"
                class="h-full w-full object-cover transition-transform duration-500 hover:scale-[1.03]"
            />
        </div>

        <div class="flex flex-1 flex-col p-6 sm:p-7">
            <div class="flex items-center justify-between gap-4">
                <time :datetime="post.date" class="text-sm font-medium text-gray-500">{{ post.date }}</time>
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-green-50 text-green-700" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-13Z" stroke-width="1.7" />
                        <path d="M8 8h8M8 12h8M8 16h5" stroke-width="1.7" stroke-linecap="round" />
                    </svg>
                </span>
            </div>

            <h3 class="mt-7 break-words text-2xl font-bold leading-8 text-gray-900">{{ post.title }}</h3>
            <p class="mt-4 line-clamp-4 break-words text-base leading-7 text-gray-600">{{ post.description }}</p>
        </div>
    </article>
</template>
