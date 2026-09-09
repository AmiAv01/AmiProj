<template>
    <div class="min-h-screen bg-slate-50 text-[17px] text-slate-700 antialiased">
        <Navbar />
        <Sidebar />
        <main class="min-h-screen pt-[72px] lg:ml-64">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import Navbar from './Navbar.vue'
import Sidebar from './Sidebar.vue'

onMounted(() => {
    initFlowbite();
})
</script>
