<template>
    <div class="bg-white">
        <div class="mx-auto w-[1300px] py-16 sm:py-16">
            <p class="text-5xl mb-10 font-bold tracking-tight text-gray-900">
                Наши запчасти
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
                            src="../../../../public/build/no-photo--lg.png"
                            alt="#"
                            class="h-full w-full object-cover object-center lg:h-full lg:w-full"
                        />
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg text-gray-700 font-bold">
                                <a :href="detail">
                                    <span
                                        aria-hidden="true"
                                        class="absolute inset-0"
                                    />
                                    {{ editTitle(detail.dt_typec) }} {{ detail.dt_invoice }}
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">
                                Артикул: {{ detail.dt_cargo }}
                            </p>
                            <p class="mt-1 text-md text-gray-500">
                                Бренд: {{ detail.fr_code }}
                            </p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">
                            {{ detail.oem }}
                        </p>
                    </div>
                </div>
            </div>
            <pagination :links="details.links" />
        </div>
    </div>
</template>

<script>
import { editDetailTitle } from "@/Services/TitleService";
export default {
    methods: {
        editTitle(res) {
            return editDetailTitle(res);
        },
    }
}
</script>

<script setup>
defineProps({
    details: Object,
});

</script>
