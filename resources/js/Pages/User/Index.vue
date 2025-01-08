<template>
    <layout>
        <section>
            <div class="w-full"></div>
        </section>
        <HeroSection />
        <section>
            <ProductList v-bind:details="details" />
            <LogoClouds/>
            <News/>
        </section>
    </layout>
</template>

<script setup>
import LogoClouds from "./LogoClouds.vue";
import ProductList from "./ProductList.vue";
import News from "@/Shared/News.vue";
import HeroSection from "@/Pages/User/HeroSection.vue";

const props = defineProps({
    details: Object,
});

</script>

