<template>
    <div>
        <div
            class="flex overflow-y-auto flex-col min-[500px]:flex-row min-[500px]:items-center gap-5 py-6 border-b border-gray-200 group"
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
                        <p class="font-semibold text-base leading-7 text-black">
                            {{ item.name }} {{ item.attributes.invoice }}
                        </p>
                        <p class="font-normal text-base text-gray-500">
                            Артикул: {{ item.attributes.cargo }}
                        </p>
                        <p
                            class="font-normal text-base leading-7 text-gray-500"
                        >
                            Бренд:
                            {{ item.attributes.fr_code }}
                        </p>
                        <p
                            class="font-medium text-base leading-7 text-gray-600 transition-all duration-300 group-hover:text-indigo-600"
                        >
                            {{ item.price }} BYN
                        </p>
                    </div>
                </div>
                <input-number />
                <div
                    class="flex flex-col justify-center ml-[50px] items-center"
                >
                    <p
                        class="font-bold text-lg mb-2 text-gray-600 transition-all duration-300 group-hover:text-indigo-600"
                    >
                        {{ item.price }} BYN
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
export default {
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
                .delete(`/cart/${this.item.id}`)
                .then((res) => {
                    //console.log(res.data);
                    this.$emit("getItems", res.data);
                })
                .catch((err) => console.log(err));
        },
    },
};
</script>
