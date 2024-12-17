<template>
    <div class="grid grid-cols-7  w-full pb-2 border-b w-max-[900px] border-gray-300 group mt-10">
        <img
            v-if="detail.dt_cargo"
            :alt="detail"
            class="w-full object-cover object-center"
            :src="`../../../../storage/images/${detail.dt_cargo}.jpg`"
        />
        <img
            v-if="detail.dt_cargo === null"
            src="../../../../public/build/no-photo--lg.png"
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
                    <a :href="`product/${detail.dt_invoice}`">
                        <span aria-hidden="true" />
                        {{ editTitle(detail.dt_typec) }}
                        {{ detail.dt_invoice }}
                    </a>
                </h3>
                <!--p class="font-normal text-xl leading-8 text-gray-500">
                    Артикул: {{ detail.dt_invoice }}
                </p!-->
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
        <div class="col-start-5 col-end-7 row-start-1 row-end-2">
            <menu-button
                :attributes="`px-6 py-4 text-lg`"
                :href="route('login')"
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
                Войти для оформления заказа
            </menu-button>
            <cart-button v-else @click="addInCart" />
        </div>
    </div>
</template>

<script>
import { editDetailTitle } from "@/Services/TitleService";

export default {
    props: {
        detail: Object,
    },
    methods: {
        addInCart() {
            axios
                .post("/cart", {
                    id: this.detail.dt_id,
                })
                .then((res) => {
                    console.log(res);
                    this.$emit('showPush', true);
                })
                .catch((err) => console.log(err));
        },
        editTitle(res) {
            return editDetailTitle(res);
        },
    },
};
</script>
