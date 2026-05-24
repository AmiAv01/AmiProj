<template>
    <layout>
        <section class="py-8 bg-white antialiased ">
            <div class="w-full px-4 2xl:px-0">
                <div class="lg:grid px-4 lg:grid-cols-3 lg:gap-8 xl:gap-16">
                    <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
                        <img
                            class="w-full"
                            src="/no-photo--lg.png"
                            alt="#"
                        />
                    </div>

                    <div  class="mt-6 sm:mt-8 lg:mt-0">
                        <h1
                            class="text-4xl font-semibold text-gray-900"
                        >
                            {{ editTitle(detail.dt_typec) }}
                            {{ isEmpty ? detail.dt_code : detail.dt_invoice }}
                            {{ isEmpty ? detail.dt_firm : '' }}
                        </h1>
                        <div v-if="!isEmpty">
                            <p class="font-normal text-2xl leading-8 text-gray-500">
                                CARGO: <strong>{{ detail.dt_cargo }}</strong>
                            </p>
                            <p class="font-normal text-2xl leading-8 text-gray-500" >
                                Бренд: <strong>{{ detail.fr_code }}</strong>
                            </p>
                        </div>
                        <p class="text-3xl mt-6 text-green-400">Наличие уточнять</p>
                        <hr
                            class="my-6 md:my-8 border-gray-200 dark:border-gray-800"
                        />
                    </div>
                </div>
            </div>
        </section>
    </layout>
</template>

<script setup>
import { editDetailTitle } from "@/Services/TitleService";

const props = defineProps({
    detail: {
        type: Object,
    },
    isEmpty:{
        type: Boolean,
    },
});
const editTitle = (res) => editDetailTitle(res)
console.log(props.detail)

</script>

