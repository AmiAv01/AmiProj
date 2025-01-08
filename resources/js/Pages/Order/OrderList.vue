<template>
    <layout>
        <p class="text-5xl font-bold tracking-tight text-gray-900 ml-10 py-24">
            Заказы
        </p>
        <div class="relative overflow-x-auto w-[80%] mx-auto pb-10 rounded-lg">
            <table
                class="w-full text-lg text-left rtl:text-right text-gray-500 dark:text-gray-400"
            >
                <thead
                    class="text-xl text-white uppercase bg-green-700 rounded-lg"
                >
                    <tr class="rounded-[15px]">
                        <th v-for="name of columnNames" scope="col" class="px-8 py-6">{{name}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(order, index) in orders"
                        :key="index"
                        @click="setLink(order.id)"
                        class="bg-white w-full border-b text-lg dark:bg-gray-800 cursor-pointer dark:border-gray-700"
                    >
                        <th
                            scope="row"
                            class="px-8 py-6 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                        >
                            {{ order.id }}
                        </th>
                        <td class="px-8 py-6">
                            {{ order.created_at }}
                        </td>
                        <td class="px-8 py-6">
                            {{ order.total_price }}
                        </td>
                        <td class="px-8 py-6">{{ order.status }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </layout>
</template>

<script setup>

const props = defineProps({
    orders: Array,
});

const columnNames = ['№ заказа', 'Дата', 'Сумма', 'Статус']
const setLink = (id) => {
    console.log(id);
    window.location = `order/${id}`;
}
</script>

