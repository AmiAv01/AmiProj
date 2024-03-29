<template>
    <Header> </Header>
    <Main v-bind:details="details"></Main>
    <Footer></Footer>
</template>
<script setup>
import Header from "./Header.vue";
import Main from "./Main.vue";
import Footer from "./Footer.vue";
defineProps({
    details: Array,
});
</script>
