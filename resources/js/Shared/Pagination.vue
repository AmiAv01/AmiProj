<template>
    <div class="flex justify-center" v-if="links.length > 3">
        <div class="flex flex-wrap mt-8">
            <template v-for="(link, key) in links" :key="key">
                <div
                    v-if="link.url === null"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                    v-html="link.label"
                />

                <inertia-link
                    v-else
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-blue-200 focus:border-primary focus:text-primary"
                    :class="{ 'bg-blue-200': link.active }"
                    :href="link.url"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
export default defineComponent({
    props: {
        links: Array,
    },
});
</script>
