<template>
    <layout>
        <div class="bg-white py-24 sm:py-32">
            <div class="mx-auto max-w-7xl">
                <div class="mx-auto max-w-2xl lg:mx-0">
                    <p class="text-5xl font-bold tracking-tight text-gray-900">
                        {{ title }}
                    </p>
                    <div
                        class="mt-12 grid grid-cols-1 gap-x-6 gap-y-10 border-t w-[1300px] border-gray-200"
                    >
                        <div
                            v-for="detail in details.data"
                            :key="detail.dt_id"
                            class="grid grid-cols-7 w-full pb-6 border-b border-gray-100 group mt-12"
                        >
                            <div
                                class="col-span-7 min-[500px]:col-span-2 md:col-span-1"
                            >
                                <img
                                    v-if="detail.dt_foto.length == 0"
                                    :src="`${detail.dt_code}`"
                                    :alt="detail"
                                    class="w-full object-cover object-center"
                                />
                                <img
                                    v-else=""
                                    src="../../../../public/build/no-photo--lg.png"
                                    alt="#"
                                    class="w-full object-cover object-center"
                                />
                            </div>
                            <div
                                class="col-span-7 md:col-span-6 min-[500px]:pl-10 max-sm:mt-5 flex flex-col justify-center"
                            >
                                <div>
                                    <h3
                                        class="font-manrope font-semibold text-4xl leading-9 text-black mb-12"
                                    >
                                        <a :href="detail">
                                            <span
                                                aria-hidden="true"
                                                class="absolute inset-0"
                                            />
                                            {{ detail.dt_invoice }}
                                        </a>
                                    </h3>
                                    <p
                                        class="font-normal text-xl leading-8 text-gray-500"
                                    >
                                        Артикул: {{ detail.dt_cargo }}
                                    </p>
                                    <p
                                        class="font-normal text-xl leading-8 text-gray-500"
                                    >
                                        Бренд: {{ detail.fr_code }}
                                    </p>
                                    <!--p
                                        class="font-normal text-xl leading-8 text-gray-500"
                                    >
                                        Бренд: {{ detail.dt_type }}
                                    </p!-->
                                </div>
                                <p
                                    class="font-manrope font-semibold text-3xl leading-10 text-black sm:text-right mt-3"
                                >
                                    {{ detail.oem }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <Pagination :links="details.links" />
            </div>
        </div>
    </layout>
</template>

<script>
import UserLayout from "@/Shared/UserLayout.vue";
import Pagination from "@/Shared/Pagination.vue";

export default {
    components: {
        layout: UserLayout,
    },
};
</script>

<script setup>
defineProps({
    details: Object,
    title: String,
});
</script>
