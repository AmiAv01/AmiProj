<template>
    <div>
        <div
            class="flex overflow-y-auto flex-col lg:flex-row mx-auto lg:items-center gap-5 py-6 border-b border-gray-200 group"
        >
            <div class="w-full md:max-w-[126px]">
                <img
                    src="public/build/no-photo--lg.png"
                    alt="perfume bottle image"
                    class="mx-auto"
                />
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-4 w-full">
                <div class="md:col-span-2">
                    <div class="flex flex-col  gap-3">
                        <p class="font-semibold text-xl leading-7 text-black">
                            {{ item.dt_typec }} {{ item.dt_invoice }}
                        </p>
                        <p class="font-normal text-xl text-gray-500">
                            Артикул: {{ item.cargo }}
                        </p>
                        <p class="font-normal text-xl leading-7 text-gray-500">
                            Бренд:
                            {{ item.fr_code }}
                        </p>
                        <p
                            class="font-medium text-xl leading-7 text-gray-600 transition-all duration-300 group-hover:text-indigo-600"
                        >
                            {{ item.unit_price }} BYN
                        </p>
                        <p class=" text-2xl text-gray-600">
                            {{ item.quantity }} шт.
                        </p>
                    </div>
                </div>

                <div
                    class="flex flex-col justify-center mt-2 sm:mt-0 items-center"
                >
                    <p
                        class="font-bold text-2xl mb-2 text-gray-600 transition-all duration-300 group-hover:text-indigo-600"
                    >
                        {{ item.unit_price * item.quantity }} BYN
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    item: {},
})

</script>
