<template>
    <Header> </Header>
    <main scroll-region>
        <slot />
    </main>
    <Footer></Footer>
</template>
<script setup>
import Header from "./Header.vue";
import Footer from "./Footer.vue";
defineProps({
    details: Object,
    auth: {
        type: Object,
    },
});
</script>
