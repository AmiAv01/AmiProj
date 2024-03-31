<template>
    <Header v-bind:canLogin="canLogin" v-bind:canRegister="canRegister">
    </Header>
    <Main v-bind:details="details"></Main>
    <Footer></Footer>
</template>
<script setup>
import Header from "./Header.vue";
import Main from "./Main.vue";
import Footer from "./Footer.vue";
defineProps({
    details: Object,
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>
