<template>
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="bg-gray-50 p-8 rounded-full mb-6">
            <slot name="icon">
                <!-- Дефолтная иконка -->
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4m16 0H4"></path>
                </svg>
            </slot>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ title }}</h2>
        <p class="text-gray-500 mb-8 max-w-sm">{{ description }}</p>
        <inertia-link
            v-if="link"
            :href="link"
            class="bg-green-700 text-white px-8 py-3 rounded-xl font-semibold hover:bg-green-800 transition"
        >
            {{ buttonText }}
        </inertia-link>
    </div>
</template>

<script setup>
defineProps({
    title: { type: String, default: 'Ничего не найдено' },
    description: { type: String, default: 'Здесь пока пусто.' },
    buttonText: { type: String, default: 'Перейти в каталог' },
    link: { type: String, default: '/catalog' }
});
</script>
