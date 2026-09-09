<template>
    <admin-layout>
        <section class="admin-content">
            <header class="admin-page-header">
                <p class="text-sm font-semibold uppercase tracking-[0.14em] text-green-700">Заказ</p>
                <h1 class="admin-page-title [overflow-wrap:anywhere]">№ {{ order.order_number }}</h1>
                <p class="admin-page-description">Создан {{ new Date(order.created_at).toLocaleDateString() }}</p>
            </header>

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
                <div class="admin-panel p-5 sm:p-7">
                    <h2 class="admin-section-title">Данные клиента</h2>
                    <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <dt class="text-sm font-medium text-slate-500">Имя</dt>
                            <dd class="mt-1 text-lg font-semibold text-slate-900">{{ order.name }}</dd>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4">
                            <dt class="text-sm font-medium text-slate-500">Email</dt>
                            <dd class="mt-1 break-all text-lg font-semibold text-slate-900">{{ order.email }}</dd>
                        </div>
                    </dl>
                    <div v-if="order.comment" class="mt-5 rounded-xl border border-slate-200 p-4">
                        <p class="text-sm font-medium text-slate-500">Комментарий</p>
                        <p class="mt-2 whitespace-pre-line text-base leading-7 text-slate-800">{{ order.comment }}</p>
                    </div>
                </div>

                <aside class="admin-panel p-5 sm:p-7">
                    <h2 class="admin-section-title">Сводка</h2>
                    <div class="mt-6">
                        <label class="mb-2 block text-sm font-medium text-slate-500">Статус заказа</label>
                        <status-form :status="order.status" :order-id="order.id"/>
                    </div>
                    <div class="mt-6 border-t border-slate-200 pt-6">
                        <p class="text-sm font-medium text-slate-500">Итоговая стоимость</p>
                        <p class="mt-1 text-3xl font-bold tracking-tight text-slate-900">{{ formatMoney(order.total_price) }}</p>
                    </div>
                </aside>
            </div>

            <div class="admin-panel mt-6 min-w-0">
                <div class="admin-panel-header">
                    <div>
                        <h2 class="admin-section-title">Приобретённые детали</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ details.length }} позиций в заказе</p>
                    </div>
                </div>
                <div class="max-h-[620px] overflow-y-auto px-5 sm:px-7">
                    <order-item
                        v-for="(detail, index) in details"
                        :item="detail"
                        :key="index"
                    />
                </div>
            </div>
        </section>
    </admin-layout>
</template>

<script>
import OrderItem from "@/Pages/Order/OrderItem.vue";
import SelectOrderStatusForm from "@/Components/SelectOrderStatusForm.vue";
export default {
    components: {
        "order-item": OrderItem,
        "status-form": SelectOrderStatusForm
    },
};
</script>

<script setup>
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";
import { formatMoney } from "@/Services/PriceFormatter";

defineProps({
    order: Object,
    details: Array,
});
</script>
