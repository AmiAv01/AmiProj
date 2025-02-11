<template>
    <tr class="border-b">
        <th
            scope="row"
            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap"
        >
            <a :href="`/admin/orders/${order.id}`">
                {{ order.id }}
            </a>
        </th>
        <td class="px-4 py-3">
            {{ order.total_price }}
        </td>
        <td class="px-4 py-3">
            {{ order.status }}
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

import {ref} from "vue";

const props = defineProps({
    order: {
        type: Object,
        default: null,
    },
})

const isShow = ref(false);

const showModal = () => {
    isShow.value = true;
}
</script>
