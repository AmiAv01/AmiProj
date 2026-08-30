<template>
    <div>
        <div
            class="flex overflow-y-auto flex-col min-[500px]:flex-row min-[500px]:items-center gap-5 py-6 border-b border-gray-200 group"
        >
            <div class="w-full md:max-w-[126px]">
                <img
                    src="/no-photo--lg.png"
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
                            {{ formatMoney(item.price) }}
                        </p>
                    </div>
                </div>
                <InputQuantity
                    :quantity="Number(item.quantity)"
                    :detailId="Number(item.dt_id)"
                />
                <div
                    class="flex md:flex-col mt-6 md:mt-0 flex-row justify-center ml-[50px] items-center"
                >
                    <p
                        class="font-bold text-lg mr-2 md:mr-0 md:mb-2 text-gray-600 transition-all duration-300 group-hover:text-green-600"
                    >
                        {{ formatMoney(parseFloat(item.price) * item.quantity) }}
                    </p>
                    <button @click="showDeleteModal = true" class="cursor-pointer hover:text-red-600 transition-colors" title="Удалить товар из корзины">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
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

        <teleport to="body">
            <div
                v-if="showDeleteModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                role="dialog"
                aria-modal="true"
                aria-labelledby="delete-cart-item-title"
                @click.self="showDeleteModal = false"
            >
                <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4 shadow-xl">
                    <h3 id="delete-cart-item-title" class="text-lg font-semibold text-gray-900 mb-4 text-center">
                        Вы желаете удалить этот товар из корзины?
                    </h3>
                    <div class="flex justify-center gap-4">
                        <button
                            @click="deleteItem"
                            class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-lg transition-colors"
                        >
                            Да
                        </button>
                        <button
                            @click="showDeleteModal = false"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-6 rounded-lg transition-colors"
                        >
                            Нет
                        </button>
                    </div>
                </div>
            </div>
        </teleport>
    </div>
</template>

<script setup>
import { editDetailTitle } from "@/Services/TitleService";
import { useCartStore} from "@/Store/cartStore";
import InputQuantity from "@/Components/InputQuantity.vue";
import { formatMoney } from "@/Services/PriceFormatter";
import { ref } from "vue";

const store = useCartStore();
const showDeleteModal = ref(false);
const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

function editTitle(res) {
    return editDetailTitle(res);
}

function deleteItem() {
    showDeleteModal.value = false;
    store.deleteDetailFromCart(props.item.dt_id);
}

</script>
