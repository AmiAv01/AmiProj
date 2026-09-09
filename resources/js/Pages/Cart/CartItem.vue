<template>
    <div class="grid grid-cols-1 gap-5 py-6 first:pt-0 border-b border-gray-200 sm:grid-cols-[112px_minmax(0,1fr)] lg:grid-cols-[126px_minmax(0,1fr)_minmax(340px,372px)] lg:items-center group">
            <div class="flex h-28 w-28 items-center justify-center overflow-hidden rounded-xl bg-gray-50 lg:h-[126px] lg:w-[126px]">
                <img
                    src="/no-photo--lg.png"
                    :alt="`Изображение товара ${editTitle(item.dt_typec)} ${item.dt_invoice}`"
                    class="h-full w-full object-contain"
                />
            </div>

            <div class="min-w-0">
                <p class="font-semibold text-lg leading-7 text-gray-900">
                    {{ editTitle(item.dt_typec)}} {{ item.dt_invoice }}
                </p>
                <div class="mt-3 space-y-1.5 text-base leading-7 text-gray-500">
                    <p>Артикул: {{ item.dt_cargo }}</p>
                    <p>Бренд: {{ item.fr_code }}</p>
                    <p>{{ formatMoney(item.price) }} / шт.</p>
                </div>
            </div>

            <div class="grid grid-cols-[minmax(0,1fr)_44px] items-center gap-3 sm:col-start-2 min-[500px]:grid-cols-[188px_minmax(108px,1fr)_44px] lg:col-start-auto">
                <InputQuantity
                    class="col-span-2 min-[500px]:col-span-1"
                    :quantity="Number(item.quantity)"
                    :detailId="Number(item.dt_id)"
                />
                <p class="text-left font-bold text-lg text-gray-800 min-[500px]:text-right">
                    {{ formatMoney(parseFloat(item.price) * item.quantity) }}
                </p>
                    <button
                        type="button"
                        @click="showDeleteModal = true"
                        class="flex h-11 w-11 cursor-pointer items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-red-50 hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                        title="Удалить товар из корзины"
                        aria-label="Удалить товар из корзины"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
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
