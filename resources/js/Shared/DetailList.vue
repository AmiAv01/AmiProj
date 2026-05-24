<template>
    <div>
        <div
            v-for="detail in details"
            :key="detail.dt_id"
            class="bg-white w-full p-4 flex border-b-2"
        >
            <div class="flex flex-col mr-4">
                <p class="font-manrope font-semibold text-lg text-black mb-2">
                    <a
                        :href="`../../catalog/product/${detail.dt_invoice}`"
                    >
                        <span aria-hidden="true" />
                        {{ detail.dt_typec }}
                        {{ detail.dt_invoice }}
                    </a>
                </p>
                <p class="font-normal text-md text-gray-500">
                    Артикул: {{ detail.dt_cargo }}
                </p>
                <p class="font-normal text-md text-gray-500">
                    Бренд: {{ detail.fr_code }}
                </p>
            </div>
        </div>
    </div>
    <div class="bg-white w-full p-4 flex text-3xl text-gray-400 items-center justify-center" v-if="details === undefined || !details.length">
        <p>Запчастей не найдено</p>
    </div>
</template>

<script setup>

const props = defineProps({
    details: Array,
})
</script>
