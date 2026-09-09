<template>
    <form class="w-full">
        <select @change="changeOrderStatus" v-model="selectedValue" :disabled="processing" class="admin-input">
            <option v-for="statusOption in ORDER_STATUSES" :key="statusOption" :value="statusOption">
                {{ statusOption }}
            </option>
        </select>
        <p v-if="error" class="mt-2 text-sm text-red-700" role="alert">{{ error }}</p>
    </form>
</template>

<script setup lang="ts">
import axios from 'axios';
import { ref, watch } from 'vue';
import { api } from '@/api/client';
import { ORDER_STATUSES, type OrderStatus } from '@/api/types';

const props = defineProps<{ orderId: number; status: OrderStatus }>();
const selectedValue = ref<OrderStatus>(props.status);
const savedValue = ref<OrderStatus>(props.status);
const processing = ref(false);
const error = ref<string | null>(null);

watch(() => props.status, status => {
    selectedValue.value = status;
    savedValue.value = status;
});

async function changeOrderStatus(): Promise<void> {
    processing.value = true;
    error.value = null;
    try {
        await api.put(`/admin/orders/${props.orderId}`, { status: selectedValue.value });
        savedValue.value = selectedValue.value;
    } catch (reason) {
        selectedValue.value = savedValue.value;
        error.value = axios.isAxiosError<{ message?: string }>(reason)
            ? reason.response?.data.message ?? 'Не удалось изменить статус заказа.'
            : 'Не удалось изменить статус заказа.';
    } finally {
        processing.value = false;
    }
}
</script>
