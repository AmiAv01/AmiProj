<template>
    <tr>
        <th
            scope="row"
            class="whitespace-nowrap font-semibold text-slate-900"
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
