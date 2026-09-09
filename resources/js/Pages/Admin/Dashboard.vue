<template>
    <AdminLayout>
        <section class="admin-content">
            <div class="overflow-hidden rounded-3xl bg-slate-900 px-6 py-8 text-white shadow-sm sm:px-10 sm:py-10">
                <p class="text-sm font-semibold uppercase tracking-[0.16em] text-green-400">AmiAvto Admin</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Добро пожаловать, {{ $page.props.auth.user.name }}</h1>
                <p class="mt-3 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">Здесь собраны основные инструменты для работы с каталогом, заказами и клиентами.</p>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <spa-link
                    v-for="item in quickActions"
                    :key="item.href"
                    :href="item.href"
                    class="group admin-panel flex min-h-40 flex-col justify-between p-6 transition hover:-translate-y-0.5 hover:border-green-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
                >
                    <div class="flex items-start justify-between gap-4">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-xl font-bold text-green-700">{{ item.symbol }}</span>
                        <svg class="h-5 w-5 text-slate-300 transition group-hover:translate-x-1 group-hover:text-green-700" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3.25 10a.75.75 0 0 1 .75-.75h10.19l-3.22-3.22a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H4a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" /></svg>
                    </div>
                    <div class="mt-6">
                        <h2 class="text-xl font-semibold text-slate-900">{{ item.title }}</h2>
                        <p class="mt-1 text-base leading-6 text-slate-500">{{ item.description }}</p>
                    </div>
                </spa-link>
            </div>
        </section>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from './Components/AdminLayout.vue'

const quickActions = [
    { title: 'Запчасти', description: 'Поиск и проверка складских позиций', href: '/admin/resource/details', symbol: 'З' },
    { title: 'Заказы', description: 'Статусы и данные новых заказов', href: '/admin/resource/orders', symbol: 'З' },
    { title: 'Новости', description: 'Публикации на сайте компании', href: '/admin/resource/news', symbol: 'Н' },
    { title: 'Пользователи', description: 'Доступы и клиентские данные', href: '/admin/resource/users', symbol: 'П' },
    { title: 'Курс валют', description: 'Актуальный курс для расчёта цен', href: '/admin/resource/currency', symbol: 'К' },
];
</script>
