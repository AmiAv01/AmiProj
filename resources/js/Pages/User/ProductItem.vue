<template>
    <div
        :key="detail.dt_id"
        class="group relative"
    >
        <div
            class=" w-[80%] rounded-md bg-gray-200 group-hover:opacity-75"
        >
            <img
                src="/public/no-photo--lg.png"
                alt="#"
                class=" object-cover object-center"
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
</template>

<script setup>
    import {editDetailTitle} from "@/Services/TitleService.js";

    const props = defineProps({
        detail: Object,
    })

    const editTitle = (res) => editDetailTitle(res);
</script>
