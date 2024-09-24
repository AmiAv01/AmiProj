<template>
    <div class="grid grid-cols-7  w-full pb-2 border-b w-max-[900px] border-gray-300 group mt-10">
        <!--img
            v-if="detail.dt_foto.length === 0"
            :alt="detail"
            class="w-full object-cover object-center"
            src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front-dark.svg"
        /!-->
        <img

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
                    <a use:inertia-vue3 :href="`product/${detail.dt_invoice}`">
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
            <cart-button @click="addInCart" />
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
                    typec: this.detail.dt_typec,
                    invoice: this.detail.dt_invoice,
                    cargo: this.detail.dt_cargo,
                    fr_code: this.detail._code,
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
