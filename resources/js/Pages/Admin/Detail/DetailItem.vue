<template>
    <tr
        class="border-b "
    >
        <th
            scope="row"
            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap "
        >
            {{ detail.dt_id }}
        </th>
        <td class="px-4 py-3">
            {{ detail.fr_code }}
        </td>
        <td class="px-4 py-3">
            {{ detail.dt_type }}
        </td>
        <td class="px-4 py-3">
            {{ detail.dt_invoice}}
        </td>
        <td class="px-4 py-3">
            {{ detail.dt_cargo }}
        </td>
        <td class="px-4 py-3">
            {{ detail.ostc }}
        </td>
    </tr>
</template>

<script setup>
const props = defineProps({
    detail: {
        type: Object,
        default: {}
    }
})
</script>
