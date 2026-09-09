<template>
    <layout>
        <HeroSection />
        <ProductList :details="details" />
        <LogoClouds />
        <NewsList :posts="posts" />
    </layout>
</template>

<script setup>
import LogoClouds from "./LogoClouds.vue";
import ProductList from "./ProductList.vue";
import HeroSection from "@/Pages/User/HeroSection.vue";
import NewsList from "@/Pages/News/NewsList.vue";

const props = defineProps({
    details: Object,
    posts: {
        type: Array,
        default: [],
    }
});

</script>
