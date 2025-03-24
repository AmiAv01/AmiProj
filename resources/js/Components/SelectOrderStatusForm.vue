<template>
    <form class="max-w-sm ">
        <select @change="changeOrderStatus" v-model="selectedValue" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  ">
            <option value="Новый">Новый</option>
            <option value="Принят">Принят</option>
            <option value="Завершён">Завершён</option>
        </select>
    </form>
</template>

<script setup>

import {onMounted, ref} from "vue";

    const props = defineProps({
        orderId: {
            type: Number,
            default: null
        },
        status: {
            type: String,
            default: 'Новый'
        }
    })

    const selectedValue = ref(null);

    const changeOrderStatus = () => {
        if (!selectedValue){
            return ''
        }
        axios.put(`/admin/resource/orders/${props.orderId}`, {status: selectedValue.value})
            .then(res => console.log(res.data))
            .catch(err => console.error(err))
    }

    onMounted(() => {
        selectedValue.value = props.status;
    })
</script>

<style scoped>

</style>
