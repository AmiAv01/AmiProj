<template>
    <tr class="border-b dark:border-gray-700">
        <th
            scope="row"
            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
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

<script>
import NewsEditForm from "@/Shared/Forms/NewsEditForm.vue";

export default {
    data() {
        return {
            isShow: false,
        };
    },
    props: {
        order: {
            type: Object,
            default: null,
        },
    },
    methods: {
        showModal() {
            this.isShow = true;
        },
    },
};
</script>
