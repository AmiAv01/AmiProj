<template>
    <div>
        <div
            class="flex overflow-y-auto flex-col min-[500px]:flex-row min-[500px]:items-center gap-5 py-6 border-b border-gray-200 group"
        >
            <div class="w-full md:max-w-[126px]">
                <img
                    src="../../../../public/build/no-photo--lg.png"
                    alt="perfume bottle image"
                    class="mx-auto"
                />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 w-full">
                <div class="md:col-span-2">
                    <div class="flex flex-col max-[500px]:items-center gap-3">
                        <p class="font-semibold text-base leading-7 text-black">
                            {{ editTitle(item.dt_typec)}} {{ item.dt_invoice }}
                        </p>
                        <p class="font-normal text-base text-gray-500">
                            Артикул: {{ item.dt_cargo }}
                        </p>
                        <p
                            class="font-normal text-base leading-7 text-gray-500"
                        >
                            Бренд:
                            {{ item.fr_code }}
                        </p>
                        <p
                            class="font-medium text-base leading-7 text-gray-600 transition-all duration-300 "
                        >
                            100 BYN
                        </p>
                    </div>
                </div>
                <input-number
                    @change="changeQuantity"
                    :quantity="`${item.quantity}`"
                />
                <div
                    class="flex flex-col justify-center ml-[50px] items-center"
                >
                    <p
                        class="font-bold text-lg mb-2 text-gray-600 transition-all duration-300 group-hover:text-green-600"
                    >
                        {{ 100 * item.quantity }} BYN
                    </p>
                    <button @click="deleteFromCart" class="cursor-pointer">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#000000"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path
                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                            ></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import InputNumber from "@/Components/InputNumber.vue";
import axios from "axios";
import { editDetailTitle } from "@/Services/TitleService";
export default {
    data() {
        return {
            quantity: this.item.quantity,
        };
    },
    components: {
        "input-number": InputNumber,
    },
    props: {
        item: {},
    },
    created() {
        console.log(this.item);
    },
    methods: {
        deleteFromCart() {
            axios
                .delete(`/cart/${this.item.dt_id}`)
                .then((res) => {
                    //console.log(res.data);
                    this.$emit("getItems", res.data);
                })
                .catch((err) => console.log(err));
        },
        changeQuantity(count) {
            console.log(`count: ${count}`);
            axios
                .put(`/cart/${this.item.dt_id}`, { quantity: count })
                .then((res) => {
                    console.log(res);
                    this.$emit("getItems", res.data);
                })
                .catch((err) => console.log(err));
        },
        editTitle(res) {
            return editDetailTitle(res);
        },
    },
};
</script>
