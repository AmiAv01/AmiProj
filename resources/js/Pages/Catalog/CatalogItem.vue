<template>
    <div class="flex flex-wrap w-full pb-2 border-b items-center justify-between  border-gray-300 group mt-10">
        <div
            class="flex flex-col sm:flex-row"
        >
            <img
                src="public/build/no-photo--lg.png"
                alt="#"
                class="w-[120px] mr-4 object-cover object-center"
            />
            <div class="flex flex-col">
                <h3
                    class="font-manrope  font-semibold text-4xl leading-9 text-black mb-4"
                >
                    <a :href="`product/${detail.dt_invoice}`">
                        <span aria-hidden="true" />
                        {{ editTitle(detail.dt_typec) }}
                        {{ detail.dt_invoice }}
                    </a>
                </h3>
                <p class="font-normal text-2xl leading-8 text-gray-500">
                    Cargo: {{ detail.dt_cargo }}
                </p>
                <p class="font-normal text-2xl leading-8 text-gray-500">
                    Бренд: {{ detail.fr_code }}
                </p>
            </div>
            <p
                class="font-manrope font-semibold text-3xl leading-10 text-black sm:text-right mt-3"
            >
                {{ detail.oem }}
            </p>
        </div>
        <div class="">
            <menu-button
                :attributes="`px-6 py-4 text-lg mt-4 lg:mt-0`"
                :href="`${routes.get('login')}`"
                v-if="!$page.props.auth.user"
            >
                <svg
                    class="w-5 h-5 mr-2 text-white dark:text-white"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 14 18"
                >
                    <path
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M7 8a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm-2 3h4a4 4 0 0 1 4 4v2H1v-2a4 4 0 0 1 4-4Z"
                    />
                </svg>
                Войти
            </menu-button>
        </div>
    </div>
</template>

<script setup>
import { editDetailTitle } from "@/Services/TitleService.js";
import {routes} from "@/Store/routes.js";

const props = defineProps({
    detail: Object,
})

const editTitle = (res) => editDetailTitle(res)

</script>



