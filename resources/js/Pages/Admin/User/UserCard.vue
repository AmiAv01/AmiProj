<template>
    <admin-layout>
        <section class="admin-content">
            <header class="admin-page-header">
                <p class="text-sm font-semibold uppercase tracking-[0.14em] text-green-700">Пользователь</p>
                <h1 class="admin-page-title">{{ user.name }}</h1>
                <p class="admin-page-description">Контактные данные, настройки расчёта и активность клиента.</p>
            </header>

            <div class="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
                <aside class="admin-panel p-5 sm:p-7">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100 text-xl font-bold text-green-800">
                        {{ userInitial }}
                    </div>
                    <dl class="mt-6 space-y-5">
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Email</dt>
                            <dd class="mt-1 break-all text-lg font-semibold text-slate-900">{{ user.email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Роль</dt>
                            <dd class="mt-2">
                                <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold" :class="user.isAdmin ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-600'">
                                    {{ user.isAdmin ? 'Администратор' : 'Пользователь' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </aside>

                <div class="admin-panel p-5 sm:p-7">
                    <h2 class="admin-section-title">Настройки</h2>
                    <div class="mt-6 grid gap-6 lg:grid-cols-2">
                        <form class="rounded-xl border border-slate-200 p-4" @submit.prevent="changeNotificationEmail">
                            <label for="notification-email" class="block text-base font-semibold text-slate-900">Email для уведомлений</label>
                            <p class="mt-1 text-sm leading-6 text-slate-500">На этот адрес будут приходить сообщения о заказах.</p>
                            <input id="notification-email" v-model="newNotificationEmail" class="admin-input mt-4" type="email" required />
                            <button class="admin-button-primary mt-3 w-full sm:w-auto" type="submit">Сохранить email</button>
                        </form>

                        <form class="rounded-xl border border-slate-200 p-4" @submit.prevent="changeFormula">
                            <label for="user-formula" class="block text-base font-semibold text-slate-900">Формула расчёта</label>
                            <p class="mt-1 text-sm leading-6 text-slate-500">Индивидуальное значение для расчёта цены клиента.</p>
                            <input id="user-formula" class="admin-input mt-4" type="text" v-model="newFormula" />
                            <button class="admin-button-primary mt-3 w-full sm:w-auto" type="submit">Сохранить формулу</button>
                        </form>
                    </div>

                    <p v-if="saveMessage" class="mt-5 rounded-xl px-4 py-3 text-sm font-medium" :class="saveError ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'" role="status">
                        {{ saveMessage }}
                    </p>
                </div>
            </div>

            <div class="admin-panel mt-6">
                <div class="admin-panel-header">
                    <div>
                        <h2 class="admin-section-title">Заказы</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ orders?.length ?? 0 }} заказов пользователя</p>
                    </div>
                </div>
                <UserOrderItems :orders="orders"/>
            </div>

            <div class="admin-panel mt-6">
                <div class="admin-panel-header">
                    <div>
                        <h2 class="admin-section-title">Корзина</h2>
                        <p class="mt-1 text-sm text-slate-500">Текущие товары пользователя</p>
                    </div>
                </div>
                <detail-list :details="cart"/>
            </div>
        </section>
    </admin-layout>
</template>

<script setup>
import UserOrderItems from "@/Pages/Admin/User/UserOrderItems.vue";
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";
import DetailList from "@/Shared/DetailList.vue";
import {computed, ref} from "vue";

const props = defineProps({
    orders: Array,
    cart: Array,
    user: Object,
    formula: String,
});

const newFormula = ref(props.formula);
const newNotificationEmail = ref(props.user.notification_email ?? props.user.email);
const saveMessage = ref("");
const saveError = ref(false);
const userInitial = computed(() => props.user.name?.trim()?.charAt(0)?.toUpperCase() || 'U');

const showSaved = () => {
    saveError.value = false;
    saveMessage.value = "Изменения сохранены";
};

const showError = (err) => {
    saveError.value = true;
    saveMessage.value = err.response?.data?.message ?? "Не удалось сохранить изменения";
};

const changeFormula = () => {
    axios
        .put(`/api/v1/admin/users/${props.user.id}`, {formula: newFormula.value})
        .then(showSaved)
        .catch(showError)
}

const changeNotificationEmail = () => {
    axios
        .put(`/api/v1/admin/users/${props.user.id}`, {notification_email: newNotificationEmail.value})
        .then(showSaved)
        .catch(showError)
}
</script>
