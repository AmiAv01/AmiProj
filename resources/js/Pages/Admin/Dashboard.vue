<template>
    <AdminLayout>
    </AdminLayout>
</template>

<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import AdminLayout from './Components/AdminLayout.vue'
// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})
</script>
