<template>
    <div
        class="bg-gray-50 w-full rounded-2xl p-6 sm:p-8 border border-gray-100"
    >
        <h2
            class="font-bold text-2xl leading-8 text-gray-900 pb-6 border-b border-gray-200"
        >
            Ваш заказ
        </h2>
        <div class="mt-6">
            <form @submit.prevent="makeOrder">
                <div class="flex items-center justify-between gap-4 pb-6 border-b border-gray-200">
                    <p class="font-medium text-lg leading-7 text-gray-700">
                        Товары, {{ count }} шт.
                    </p>
                    <p class="shrink-0 font-bold text-xl leading-8 text-gray-900">
                        {{ formatMoney(price) }}
                    </p>
                </div>

                <div class="my-6">
                    <label for="comment" class="block text-base font-medium text-gray-700 mb-2">
                        Комментарий к заказу (опционально)
                    </label>
                    <textarea
                        id="comment"
                        v-model="comment"
                        placeholder="Например, способ оплаты или пожелания к заказу"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent resize-none"
                        rows="3"
                        maxlength="1000"
                    ></textarea>
                    <p class="text-sm text-gray-500 mt-1">{{ comment.length }}/1000</p>
                </div>

                <button
                    type="submit"
                    :disabled="submitting || count === 0"
                    class="w-full text-center bg-green-700 rounded-lg py-3 px-6 font-semibold text-lg text-white transition-colors hover:bg-green-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    {{ submitting ? 'Оформление…' : 'Оформить заказ' }}
                </button>
                <p v-if="submitError" class="mt-3 text-sm text-red-600" role="alert">
                    {{ submitError }}
                </p>
            </form>
        </div>
    </div>
</template>

<script setup>
import axios from "axios";
import {ref} from "vue";
import {useCartStore} from "@/Store/cartStore";
import { formatMoney } from "@/Services/PriceFormatter";

const store = useCartStore();
const comment = ref('');
const submitting = ref(false);
const submitError = ref('');
const emit = defineEmits(['order-notification']);

const props = defineProps({
    count: {
        type: Number,
        default: 0,
    },
    price: {
        type: Number,
        default: 0,
    }
})

async function makeOrder() {
    if (submitting.value || props.count === 0) {
        return;
    }

    submitting.value = true;
    submitError.value = '';

    try {
        await axios.post("/api/v1/orders", {
            comment: comment.value || null
        });
        emit('order-notification', { type: 'success', message: 'Заказ успешно оформлен' });
        comment.value = '';
        store.setDetails([]);
        store.setCartCount(0);
    } catch (err) {
        submitError.value = err.response?.data?.message || 'Не удалось оформить заказ. Попробуйте ещё раз.';
        emit('order-notification', { type: 'error', message: submitError.value });
    } finally {
        submitting.value = false;
    }
}

</script>
