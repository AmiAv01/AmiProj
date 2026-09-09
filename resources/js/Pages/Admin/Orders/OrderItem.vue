<template>
    <tr
        class="cursor-pointer focus-within:bg-green-50 focus:outline-none"
        role="link"
        tabindex="0"
        @click="openOrder"
        @keydown.enter="openOrder"
        @keydown.space.prevent="openOrder"
    >
        <th
            scope="row"
            class="whitespace-nowrap font-semibold text-slate-900"
        >
            <a :href="`/admin/resource/orders/${order.order_number}`">
                {{ order.order_number }}
            </a>
        </th>
        <td class="font-semibold text-slate-900">
            {{ formatMoney(order.total_price) }}
        </td>
        <td class="px-4 py-3">
            <span class="inline-flex rounded-full px-2.5 py-1 text-sm font-semibold" :class="statusClass">
                {{ order.status }}
            </span>
        </td>
        <td class="px-4 py-3">
            {{ order.name }}
        </td>
        <td class="px-4 py-3">
            {{ order.email }}
        </td>
        <td class="px-4 py-3">
            {{ new Date(order.created_at).toLocaleDateString() }}
        </td>
    </tr>
</template>

<script setup>

import {computed} from "vue";
import { formatMoney } from "@/Services/PriceFormatter";

const props = defineProps({
    order: {
        type: Object,
        default: null,
    },
})

const statusClass = computed(() => ({
    'Новый': 'bg-blue-50 text-blue-700',
    'Принят': 'bg-amber-50 text-amber-700',
    'Выполнен': 'bg-green-50 text-green-700',
}[props.order.status] ?? 'bg-slate-100 text-slate-700'));

const openOrder = () => {
    window.location.href = `/admin/resource/orders/${props.order.order_number}`;
};

</script>
