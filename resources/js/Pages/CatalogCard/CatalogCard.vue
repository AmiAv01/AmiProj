<template>
    <div class="flex">
        <div class="col-span-7 min-[500px]:col-span-2 md:col-span-1">
            <img
                v-if="detail.dt_foto.length == 0"
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
                    <a use:inertia-vue3 :href="`product/${detail.dt_id}`">
                        <span aria-hidden="true" />
                        {{ editString(detail.dt_typec) }}
                        {{ detail.dt_invoice }}
                    </a>
                </h3>
                <p class="font-normal text-xl leading-8 text-gray-500">
                    Артикул: {{ detail.dt_invoice }}
                </p>
                <p class="font-normal text-xl leading-8 text-gray-500">
                    Cargo: {{ detail.dt_cargo }}
                </p>
                <p class="font-normal text-xl leading-8 text-gray-500">
                    Бренд: {{ detail.fr_code }}
                </p>
            </div>
            <p
                class="font-manrope font-semibold text-3xl leading-10 text-black sm:text-right mt-3"
            >
                {{ detail.oem }}
            </p>
            <div>
                <cart-button @click="addInCart" />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        detail: Object,
    },
    methods: {
        editString(str, brand) {
            if (!str) {
                return str;
            }
            let resultString = "";
            if (brand) {
            }
            return str[0].toUpperCase() + str.slice(1).toLowerCase();
        },
        addInCart() {
            axios
                .post("/cart", {
                    id: this.detail.dt_id,
                    typec: this.detail.dt_typec,
                    invoice: this.detail.dt_invoice,
                    cargo: this.detail.dt_cargo,
                    fr_code: this.detail._code,
                })
                .then((res) => console.log(res))
                .catch((err) => console.log(err));
        },
    },
};
</script>
