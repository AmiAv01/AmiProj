<template>
    <layout>
        <section>
            <div class="w-full"></div>
        </section>
        <HeroSection />
        <section>
            <ProductList v-bind:details="details" />
            <LogoClouds/>
            <NewsList :posts="posts"/>
        </section>
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

