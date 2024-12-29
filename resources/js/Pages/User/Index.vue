<template>
    <layout>
        <section>
            <div class="w-full"></div>
        </section>
        <hero-section />
        <section>
            <product-list v-bind:details="details" />
            <logo-clouds />
            <news />
        </section>
    </layout>
</template>

<script>
import LogoClouds from "./LogoClouds.vue";
import ProductList from "./ProductList.vue";
import News from "@/Shared/News.vue";

import HeroSection from "@/Pages/User/HeroSection.vue";

export default {
    components: {
        'hero-section': HeroSection,
        'product-list': ProductList,
        'logo-clouds': LogoClouds,
        'news': News
    }
};
</script>

<script setup>
defineProps({
    details: Object,
});
</script>
