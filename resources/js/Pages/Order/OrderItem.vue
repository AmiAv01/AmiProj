<template>
    <div>
        <div
            class="flex overflow-y-auto flex-col min-[500px]:flex-row mx-auto min-[500px]:items-center gap-5 py-6 border-b border-gray-200 group"
        >
            <div class="w-full md:max-w-[126px]">
                <img
                    src="https://pagedone.io/asset/uploads/1701162850.png"
                    alt="perfume bottle image"
                    class="mx-auto"
                />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 w-full">
                <div class="md:col-span-2">
                    <div class="flex flex-col max-[500px]:items-center gap-3">
                        <p class="font-semibold text-xl leading-7 text-black">
                            {{ item.dt_typec }} {{ item.dt_invoice }}
                        </p>
                        <p class="font-normal text-xl text-gray-500">
                            Артикул: {{ item.cargo }}
                        </p>
                        <p class="font-normal text-xl leading-7 text-gray-500">
                            Бренд:
                            {{ item.fr_code }}
                        </p>
                        <p
                            class="font-medium text-xl leading-7 text-gray-600 transition-all duration-300 group-hover:text-indigo-600"
                        >
                            {{ item.unit_price }} BYN
                        </p>
                    </div>
                </div>
                <p class="text-center self-center text-2xl text-gray-600">
                    {{ item.quantity }} шт.
                </p>
                <div
                    class="flex flex-col justify-center ml-[50px] items-center"
                >
                    <p
                        class="font-bold text-2xl mb-2 text-gray-600 transition-all duration-300 group-hover:text-indigo-600"
                    >
                        {{ item.unit_price * item.quantity }} BYN
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import InputNumber from "@/Components/InputNumber.vue";
import axios from "axios";
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
};
</script>
