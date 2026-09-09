<template>
    <admin-layout>
        <section class="admin-content">
            <header class="admin-page-header">
                <p class="text-sm font-semibold uppercase tracking-[0.14em] text-green-700">Настройки</p>
                <h1 class="admin-page-title">Курс валют</h1>
                <p class="admin-page-description">Значение используется при расчёте стоимости товаров в каталоге.</p>
            </header>
            <div class="admin-panel max-w-2xl p-5 sm:p-7">
                <form @submit.prevent="changeCurrency">
                    <label for="currency-value" class="block text-base font-semibold text-slate-900">Текущий курс</label>
                    <p class="mt-1 text-sm leading-6 text-slate-500">Введите новое значение и сохраните изменение.</p>
                    <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                        <input
                            id="currency-value"
                            class="admin-input sm:max-w-xs"
                            type="number"
                            step="0.1"
                            min="1"
                            v-model="currencyValue"
                            placeholder="Введите курс"
                        />
                        <button type="submit" class="admin-button-primary">Сохранить</button>
                    </div>
                    <p v-if="saveMessage" class="mt-4 text-sm font-medium" :class="saveError ? 'text-red-700' : 'text-green-700'" role="status">{{ saveMessage }}</p>
                </form>
            </div>
        </section>
    </admin-layout>
</template>

<script setup>
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";
import {onMounted, ref} from "vue";

const props = defineProps({
    currency: {
        type: String,
        default: 0
    }
});
let currencyValue = ref(0);
const saveMessage = ref('');
const saveError = ref(false);

function changeCurrency(){
    axios.post(`/api/v1/admin/currency`, {currency: currencyValue.value})
        .then(() => {
            saveError.value = false;
            saveMessage.value = 'Курс сохранён';
        })
        .catch(() => {
            saveError.value = true;
            saveMessage.value = 'Не удалось сохранить курс';
        })
}

onMounted(() => {
    currencyValue.value = props.currency;
})

</script>
