<template>
    <layout>
        <section
            class="relative z-10 after:contents-[''] after:absolute after:z-0 after:h-full xl:after:w-1/3 after:top-0 after:right-0 after:bg-gray-50"
        >
            <div
                class="w-full max-w-7xl px-4 md:px-5 lg-6 mx-auto relative z-10"
            >
                <div class="grid grid-cols-12">
                    <div
                        class="col-span-12 xl:col-span-8 lg:pr-8 pt-14 pb-8 lg:py-24 w-full max-xl:max-w-3xl max-xl:mx-auto"
                    >
                        <div
                            class="flex items-center justify-between pb-8 border-b border-gray-300"
                        >
                            <h2
                                class="font-manrope font-bold text-3xl leading-10 text-black"
                            >
                                Корзина
                            </h2>
                            <h2
                                class="font-manrope font-bold text-xl leading-8 text-gray-600"
                            >
                                Товары, {{ count }} шт.
                            </h2>
                        </div>
                        <div class="h-[500px] overflow-y-auto">
                            <CartItem
                                v-for="(detail, index) in store.cartData"
                                :key="index"
                                :item="detail"
                            />
                        </div>
                    </div>
                    <CartOrder :count="count" :price="price" />
                </div>
            </div>
        </section>
    </layout>
</template>

<script setup>
import CartItem from "./CartItem.vue";
import CartOrder from "./CartOrder.vue";
import {computed} from "vue";
import { useCartStore} from "@/Store/cartStore.js";

const store = useCartStore();
const props = defineProps({
    items: Array,
});

store.setDetails(props.items);
console.log(props.items);

function getTotalQuantity(items) {
    console.log(items)
    return Object.values(items).reduce(
        (sum, obj) => sum + obj.quantity,
        0
    );
}
function getTotalPrice(items) {
    return Object.values(items).reduce(
        (sum, obj) => sum + parseFloat(obj.price).toFixed(3) * obj.quantity,
        0
    );
}

let count = computed(() => getTotalQuantity(store.cartData));
let price = computed(() => getTotalPrice(store.cartData));

</script>

