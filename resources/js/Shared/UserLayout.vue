<template class="w-full">
    <div class="flex flex-col min-h-screen bg-white">
        <Header />
        <main class="flex-grow w-full">
            <slot />
        </main>
        <Footer />
    </div>
</template>

<script setup>
import Header from "./Header/Header.vue";
import Footer from "./Footer.vue";
defineProps({
    details: Object,
    auth: {
        type: Object,
    },
});
</script>
