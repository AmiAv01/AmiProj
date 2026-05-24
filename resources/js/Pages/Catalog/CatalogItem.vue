<template>
    <!-- Добавляем items-start, чтобы контент не «разъезжался» -->
    <div class="flex flex-row w-full pb-2 border-b border-gray-300 group mt-10 items-start">

        <!-- Левая часть: Изображение -->
        <img src="/no-photo--lg.png" alt="#" class="w-[120px] mr-4 shrink-0 object-cover" />

        <!-- Центральная часть: Информация (растет и занимает всё место) -->
        <div class="flex-grow min-w-0 pr-4">
            <h3 class="font-manrope font-semibold text-3xl leading-9 text-black mb-4 truncate">
                <a :href="`product/${detail.dt_invoice}`">
                    {{ editTitle(detail.dt_typec) }} {{ detail.dt_invoice }}
                </a>
            </h3>
            <p class="font-normal text-xl leading-8 text-gray-500">Cargo: {{ detail.dt_cargo }}</p>
            <p class="font-normal text-xl leading-8 text-gray-500">Бренд: {{ detail.fr_code }}</p>
        </div>

        <!-- Правая часть: Замочек и текст (фиксированная ширина) -->
        <div v-if="!$page.props.auth.user" class="shrink-0 w-[240px] text-right justify-end  mt-1">
            <a href="/login" class="inline-flex items-center text-gray-600 hover:text-green-700 font-medium">
                <span class="mr-2">🔒</span>
                Войти для просмотра цены
            </a>
        </div>
    </div>
</template>

<script setup>
import { editDetailTitle } from "@/Services/TitleService.js";
const props = defineProps({ detail: Object });
const editTitle = (res) => editDetailTitle(res);
</script>
