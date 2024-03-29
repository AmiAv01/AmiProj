<template>
    <UserLayout v-bind:details="details"> </UserLayout>
</template>

<script setup>
import UserLayout from "@/Pages/User/Layouts/UserLayout.vue";
defineProps({
    details: Array,
});
</script>
