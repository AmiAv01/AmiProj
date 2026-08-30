<template>
    <push v-if="isShow" :isShow="isShow" @hide="hideModal" :title="`Заказ успешно оформлен`">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"  stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    </push>
    <div
        class="bg-gray-50 w-full rounded-2xl py-12 px-6 sm:px-8 shadow-sm border border-gray-100"
    >
        <h2
            class="font-manrope font-bold text-3xl leading-10 text-black pb-8 border-b border-gray-300"
        >
            Ваш заказ
        </h2>
        <div class="mt-8">
            <form @submit.prevent="makeOrder">
                <div class="flex pb-4 w-full"></div>

                <div class="flex items-center justify-between py-8 border-b border-gray-200">
                    <p class="font-medium text-xl leading-8 text-black">
                        Товары, {{ count }} шт.
                    </p>
                    <p class="font-semibold text-xl leading-8 text-green-500">
                        {{ parseFloat(price).toFixed(2) }} BYN
                    </p>
                </div>

                <div class="my-6">
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                        Комментарий к заказу (опционально)
                    </label>
                    <textarea
                        id="comment"
                        v-model="comment"
                        placeholder="Например: безнал, наличные, и т.д."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"
                        rows="3"
                        maxlength="1000"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">{{ comment.length }}/1000</p>
                </div>

                <button
                    type="submit"
                    :disabled="submitting || count === 0"
                    class="w-full text-center bg-green-700 rounded-xl py-3 px-6 font-semibold text-lg text-white transition-all duration-500 hover:bg-green-600 disabled:cursor-not-allowed disabled:opacity-60"
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
import {useCartStore} from "@/Store/cartStore.js";

const store = useCartStore();
const isShow = ref(false);
const comment = ref('');
const submitting = ref(false);
const submitError = ref('');

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
        await axios.post("/order", {
            comment: comment.value || null
        });
        isShow.value = true;
        comment.value = '';
        store.setDetails([]);
        store.setCartCount(0);
    } catch (err) {
        submitError.value = err.response?.data?.message || 'Не удалось оформить заказ. Попробуйте ещё раз.';
    } finally {
        submitting.value = false;
    }
}

function hideModal(param){
    isShow.value = param;
}

</script>
