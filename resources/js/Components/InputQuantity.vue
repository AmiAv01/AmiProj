<template>
    <div
        class="flex items-center max-[500px]:justify-center h-full max-md:mt-3"
    >
        <div class="flex items-center h-full">
            <button
                @click="decCount"
                :disabled="count <= CART_QUANTITY_MIN"
                class="group rounded-l-xl px-5 py-[12px] border border-gray-200 flex items-center justify-center shadow-sm shadow-transparent transition-all duration-500 hover:bg-gray-50 hover:border-gray-300 hover:shadow-gray-300 focus-within:outline-gray-300 disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:bg-transparent disabled:hover:shadow-none"
                :title="minimumQuantityTitle"
            >
                <svg
                    class="stroke-gray-900 transition-all duration-500 group-hover:stroke-black"
                    xmlns="http://www.w3.org/2000/svg"
                    width="22"
                    height="22"
                    viewBox="0 0 22 22"
                    fill="none"
                >
                    <path
                        d="M16.5 11H5.5"
                        stroke=""
                        stroke-width="1.6"
                        stroke-linecap="round"
                    />
                    <path
                        d="M16.5 11H5.5"
                        stroke=""
                        stroke-opacity="0.2"
                        stroke-width="1.6"
                        stroke-linecap="round"
                    />
                    <path
                        d="M16.5 11H5.5"
                        stroke=""
                        stroke-opacity="0.2"
                        stroke-width="1.6"
                        stroke-linecap="round"
                    />
                </svg>
            </button>
            <input
                type="number"
                v-model.number="count"
                :min="CART_QUANTITY_MIN"
                :max="CART_QUANTITY_MAX"
                @input="changeQuantity"
                @change="enforceMinimum"
                class="border-y border-gray-200 outline-none text-gray-900 font-semibold text-lg w-full max-w-[73px] min-w-[60px] placeholder:text-gray-900 py-[9px] text-center bg-transparent"
            />
            <button
                @click="incCount"
                :disabled="count >= CART_QUANTITY_MAX"
                class="group rounded-r-xl px-5 py-[12px] border border-gray-200 flex items-center justify-center shadow-sm shadow-transparent transition-all duration-500 hover:bg-gray-50 hover:border-gray-300 hover:shadow-gray-300 focus-within:outline-gray-300 disabled:cursor-not-allowed disabled:opacity-40"
            >
                <svg
                    class="stroke-gray-900 transition-all duration-500 group-hover:stroke-black"
                    xmlns="http://www.w3.org/2000/svg"
                    width="22"
                    height="22"
                    viewBox="0 0 22 22"
                    fill="none"
                >
                    <path
                        d="M11 5.5V16.5M16.5 11H5.5"
                        stroke=""
                        stroke-width="1.6"
                        stroke-linecap="round"
                    />
                    <path
                        d="M11 5.5V16.5M16.5 11H5.5"
                        stroke=""
                        stroke-opacity="0.2"
                        stroke-width="1.6"
                        stroke-linecap="round"
                    />
                    <path
                        d="M11 5.5V16.5M16.5 11H5.5"
                        stroke=""
                        stroke-opacity="0.2"
                        stroke-width="1.6"
                        stroke-linecap="round"
                    />
                </svg>
            </button>
        </div>

    </div>
</template>

<script setup>
import { useCartStore} from "@/Store/cartStore";
import { onUnmounted, ref, watch } from "vue";
import debounce from "lodash.debounce";
import { CART_QUANTITY_MAX, CART_QUANTITY_MIN } from "@/Config/AppConfig";

const store = useCartStore();
const props = defineProps({
    quantity: {
        type: Number,
        default: CART_QUANTITY_MIN,
    },
    detailId: {
        type: Number,
        required: true,
    }});
const count = ref(props.quantity);
const minimumQuantityTitle = `Минимальное количество — ${CART_QUANTITY_MIN}`;
const saveQuantity = debounce((quantity) => {
    store.changeDetailQuantity(props.detailId, quantity);
}, 300);

watch(() => props.quantity, (newVal) => {
    count.value = newVal;
});

function incCount() {
    if (count.value < CART_QUANTITY_MAX) {
        count.value++;
        store.changeDetailQuantity(props.detailId, count.value);
    }
}

function decCount() {
    if (count.value > CART_QUANTITY_MIN) {
        count.value--;
        store.changeDetailQuantity(props.detailId, count.value);
    }
}

function changeQuantity() {
    if (count.value === '' || count.value === null || count.value === undefined) {
        return;
    }
    if (count.value < CART_QUANTITY_MIN) {
        saveQuantity.cancel();
        count.value = CART_QUANTITY_MIN;
        return;
    }
    if (count.value > CART_QUANTITY_MAX) {
        saveQuantity.cancel();
        count.value = CART_QUANTITY_MAX;
        store.changeDetailQuantity(props.detailId, CART_QUANTITY_MAX);
        return;
    }
    saveQuantity(count.value);
}

function enforceMinimum() {
    if (count.value === '' || count.value === null || count.value === undefined || count.value < CART_QUANTITY_MIN) {
        count.value = CART_QUANTITY_MIN;
        store.changeDetailQuantity(props.detailId, CART_QUANTITY_MIN);
    }
}

onUnmounted(() => saveQuantity.cancel());
</script>
