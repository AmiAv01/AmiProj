<template>
    <div v-if="isShow" :class="`absolute top-[30%] left-[70%] w-[450px] ${bgColor} opacity-75 flex p-4 items-center justify-around h-[150px] rounded-lg `">
        <slot/>
        <p class="text-2xl font-bold text-white">{{title}}</p>
    </div>
</template>


<script setup>

const props = defineProps({
    title: {
        type: String,
        default: ''
    },
    isShow: {
        type: Boolean,
        default: false
    },
    bgColor: {
        type: String,
        default: 'bg-green-600'
    }
})

const emit = defineEmits(['hide']);

setTimeout(() => {
    emit('hide', false);
}, 1500);
</script>




