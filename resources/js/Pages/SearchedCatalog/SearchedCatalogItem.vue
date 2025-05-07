<template>
    <div class="grid grid-cols-7  w-full pb-2 border-b w-max-[900px] border-gray-300 group mt-10">
        <img

            src="public/build/no-photo--lg.png"
            alt="#"
            class="w-[120px] object-cover object-center"
        />
        <div
            class=" flex flex-col col-start-2 col-end-4"
        >
            <div>
                <h3
                    class="font-manrope  font-semibold text-4xl leading-9 text-black mb-4"
                >
                    <a :href="`/catalog/product/${detail.dt_code}`">
                        <span aria-hidden="true" />
                        {{ editTitle(detail.dt_typec) }}
                        {{ detail.dt_code }}
                    </a>
                </h3>
                <p class="font-normal text-2xl leading-8 text-gray-500">
                    Бренд: {{ detail.dt_firm }}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { editDetailTitle } from "@/Services/TitleService";

const props = defineProps({
    detail: Object,
})

const editTitle = (res) =>  editDetailTitle(res);
</script>
