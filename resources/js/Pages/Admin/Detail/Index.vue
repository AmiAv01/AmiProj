<template>
    <AdminLayout>
        <DetailList :details = "details" :pagination = "pagination" ></DetailList>
    </AdminLayout>
</template>

<script setup>

defineProps({
    details:Array,
    pagination: Array,
})
import AdminLayout from '../Components/AdminLayout.vue'
import DetailList from '../Detail/DetailList.vue'
</script>
