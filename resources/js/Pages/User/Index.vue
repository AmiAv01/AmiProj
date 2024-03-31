<template>
    <UserLayout
        v-bind:details="details"
        v-bind:canLogin="canLogin"
        v-bind:canRegister="canRegister"
    >
    </UserLayout>
</template>

<script setup>
import UserLayout from "@/Pages/User/Layouts/UserLayout.vue";
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
