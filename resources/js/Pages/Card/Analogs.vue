<template>
    <div class="grid grid-cols sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-[80%] mx-auto">
        <div
            v-for="detail in details"
            :key="detail.dt_id"
            class="bg-white w-full p-4 flex border-b-2 flex-col items-center"
        >
            <div class="mr-4 mb-4">
                <img
                    src="/no-photo--lg.png"
                    alt="#"
                    class="w-[250px] h-[250px] rounded-lg object-cover object-center "
                />
            </div>
            <div class="flex flex-col ">
                <p class="font-manrope font-semibold text-lg text-black mb-2">
                    <a
                        :href="`../../catalog/product/${detail.dt_invoice}`"
                    >
                        <span aria-hidden="true" />
                        {{ editTitle(detail.dt_typec) }}
                        {{ detail.dt_invoice }}
                    </a>
                </p>
                <p class="font-normal text-md text-gray-500">
                    Cargo: {{ detail.dt_cargo }}
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

import { editDetailTitle } from "@/Services/TitleService";

const props = defineProps({
    details: Array,
})

const editTitle = (res) => editDetailTitle(res)

</script>
