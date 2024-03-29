<template>
    <div class="bg-white">
        <div class="mx-auto w-[1300px] py-16 sm:py-24">
            <p class="text-5xl mb-10 font-bold tracking-tight text-gray-900">
                Наши продукты
            </p>

            <div
                class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8"
            >
                <div
                    v-for="detail in details.data"
                    :key="detail.dt_id"
                    class="group relative"
                >
                    <div
                        class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80"
                    >
                        <img
                            v-if="detail.dt_foto.length == 0"
                            :src="`${detail.dt_code}`"
                            :alt="detail"
                            class="h-full w-full object-cover object-center lg:h-full lg:w-full"
                        />
                        <img
                            v-else=""
                            src="../../../../../public/build/no-photo--lg.png"
                            alt="#"
                            class="h-full w-full object-cover object-center lg:h-full lg:w-full"
                        />
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-gray-700">
                                <a :href="detail">
                                    <span
                                        aria-hidden="true"
                                        class="absolute inset-0"
                                    />
                                    {{ detail.dt_code }}
                                </a>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ detail.dt_cargo }}
                            </p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">
                            {{ detail.oem }}
                        </p>
                    </div>
                </div>
            </div>
            <Pagination :links="details.links" />
        </div>
    </div>
</template>

<script setup>
defineProps({
    details: Array,
});
import Pagination from "@/Pages/Admin/Components/Pagination.vue";
</script>
