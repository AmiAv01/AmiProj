<template>
    <HeaderTest> </HeaderTest>
    <main scroll-region>
        <slot />
    </main>

    <Footer></Footer>
</template>
<script setup>
import Header from "./Header.vue";
import HeaderTest from "./HeaderTest.vue";
import Footer from "./Footer.vue";
defineProps({
    details: Object,
    auth: {
        type: Object,
    },
});
</script>
