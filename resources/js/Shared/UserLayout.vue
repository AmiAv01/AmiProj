<template class="w-full">
    <Header> </Header>
    <main class="w-full">
        <slot />
    </main>
    <Footer></Footer>
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
