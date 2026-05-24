<template>
    <layout>
        <section class="py-8 bg-white antialiased">
            <div class="w-full max-w-7xl px-4 md:px-5 lg:px-6 mx-auto">
                <div class="lg:grid px-4 lg:grid-cols-3 lg:gap-8 xl:gap-16">
                    <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
                        <img
                            class="w-full"
                            src="/no-photo--lg.png"
                            alt="#"
                        />
                    </div>

                    <div class="mt-6 sm:mt-8 lg:mt-0 col-span-2">
                        <h1 class="text-4xl font-semibold text-gray-900">
                            {{ editTitle(detail.dt_typec) }}
                            {{ isEmpty ? detail.dt_code : detail.dt_invoice }}
                            {{ isEmpty ? detail.dt_firm : '' }}
                        </h1>
                        <div v-if="!isEmpty" class="mt-4">
                            <p class="font-normal text-2xl leading-8 text-gray-500">
                                CARGO: <strong>{{ detail.dt_cargo }}</strong>
                            </p>
                            <p class="font-normal text-2xl leading-8 text-gray-500">
                                Бренд: <strong>{{ detail.fr_code }}</strong>
                            </p>
                        </div>
                        <p class="text-3xl mt-6 text-green-400 font-semibold">Наличие уточнять</p>
                        <hr class="my-6 md:my-8 border-gray-200" />
                    </div>
                </div>

                <!-- Блок деталировки для гостей под формой -->
                <div class="mt-8 border-t pt-6" v-if="sameDetails && sameDetails.length">
                    <DetailLayout :details="sameDetails"/>
                </div>
            </div>
        </section>
    </layout>
</template>

<script setup>
import Layout from "@/Shared/UserLayout.vue";
import DetailLayout from "./DetailLayout.vue";
import { editDetailTitle } from "@/Services/TitleService";

const props = defineProps({
    detail: {
        type: Object,
    },
    isEmpty: {
        type: Boolean,
    },
    sameDetails: {
        type: Array,
        default: () => []
    }
});

const editTitle = (res) => editDetailTitle(res);
</script>
