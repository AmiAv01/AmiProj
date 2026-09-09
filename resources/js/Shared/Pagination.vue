<template>
    <nav class="flex justify-center" v-if="links.length > 3" aria-label="Навигация по страницам">
        <div class="mt-6 flex flex-wrap justify-center gap-1.5">
            <template v-for="(link, key) in links" :key="key">
                <div
                    v-if="link.url === null"
                    class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-400"
                >{{ displayLabel(link.label) }}</div>

                <spa-link
                    v-else
                    class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg border px-3 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
                    :class="link.active
                        ? 'border-green-700 bg-green-700 text-white shadow-sm'
                        : 'border-gray-200 bg-white text-gray-700 hover:border-green-300 hover:bg-green-50 hover:text-green-800'"
                    :href="link.url"
                >{{ displayLabel(link.label) }}</spa-link>
            </template>
        </div>
    </nav>
</template>

<script setup lang="ts">
interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

defineProps<{ links: PaginationLink[] }>();

function displayLabel(label: string): string {
    return label.replaceAll('&laquo;', '«').replaceAll('&raquo;', '»');
}
</script>
