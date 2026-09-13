<template>
    <AdminLayout>
        <section class="admin-content">
            <header class="admin-page-header">
                <p class="text-sm font-semibold uppercase tracking-[0.14em] text-green-700">Обмен данными</p>
                <h1 class="admin-page-title">Импорт DBF</h1>
                <p class="admin-page-description">История обработки файлов и снимок пустых полей товаров после импорта ASS.DBF.</p>
            </header>

            <div class="admin-panel">
                <div class="admin-panel-header">
                    <div>
                        <h2 class="admin-section-title">Последние файлы</h2>
                        <p class="mt-1 text-sm text-slate-500">Время отображается в часовом поясе браузера.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table min-w-[1050px]">
                        <thead>
                            <tr>
                                <th>Файл</th>
                                <th>Начало</th>
                                <th>Результат</th>
                                <th>Прочитано</th>
                                <th>Записано</th>
                                <th>Фото</th>
                                <th>Пустые позиции</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="run in runs.data" :key="run.id">
                                <td class="font-mono font-semibold text-slate-900">{{ run.filename }}</td>
                                <td class="whitespace-nowrap">{{ formatDateTime(run.started_at) }}</td>
                                <td>
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold" :class="statusClass(run.status)">
                                        {{ statusLabel(run.status) }}
                                    </span>
                                    <p v-if="run.error" class="mt-2 max-w-md whitespace-normal text-sm text-red-700" :title="run.error">{{ run.error }}</p>
                                </td>
                                <td>{{ run.records_read }}</td>
                                <td>{{ run.records_written }}</td>
                                <td>{{ run.images_written }}</td>
                                <td>{{ run.filename.toUpperCase() === 'ASS.DBF' ? run.issues_count : '—' }}</td>
                                <td>
                                    <spa-link
                                        v-if="run.filename.toUpperCase() === 'ASS.DBF' && run.status === 'completed'"
                                        :href="`/admin/resource/imports?run=${run.id}`"
                                        class="font-semibold text-green-700 hover:text-green-900"
                                    >Показать отчёт</spa-link>
                                </td>
                            </tr>
                            <tr v-if="!runs.data.length">
                                <td colspan="8" class="admin-empty-state">Импорты ещё не запускались</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-slate-100 px-5 pb-6">
                    <Pagination :links="runs.links" />
                </div>
            </div>

            <div v-if="selectedRun" class="mt-6">
                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.14em] text-green-700">Контроль качества</p>
                        <h2 class="mt-1 text-2xl font-bold text-slate-900">Пустые поля после {{ selectedRun.filename }}</h2>
                    </div>
                    <p class="text-sm text-slate-500">Запуск: {{ formatDateTime(selectedRun.started_at) }}</p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
                    <div v-for="card in summaryCards" :key="card.key" class="admin-panel p-4">
                        <p class="text-sm text-slate-500">{{ card.label }}</p>
                        <p class="mt-1 text-2xl font-bold" :class="card.key === 'positions' ? 'text-slate-900' : 'text-amber-700'">{{ summary[card.key] }}</p>
                    </div>
                </div>

                <div class="admin-panel mt-4">
                    <div class="overflow-x-auto">
                        <table class="admin-table min-w-[1000px]">
                            <thead>
                                <tr>
                                    <th>Позиция</th>
                                    <th>Внутренний код</th>
                                    <th>Артикул</th>
                                    <th>Наименование</th>
                                    <th>Бренд</th>
                                    <th>Что пусто</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="issue in issues?.data ?? []" :key="issue.id">
                                    <td>
                                        <spa-link v-if="issue.invoice" :href="productUrl(issue.invoice)" class="font-semibold text-green-700 hover:text-green-900">
                                            #{{ issue.detail_id }}
                                        </spa-link>
                                        <span v-else class="font-semibold text-slate-900">#{{ issue.detail_id }}</span>
                                    </td>
                                    <td class="font-mono">{{ issue.detail_code || '—' }}</td>
                                    <td class="font-mono">{{ issue.invoice || '—' }}</td>
                                    <td>{{ issue.product_name || '—' }}</td>
                                    <td>{{ issue.brand || '—' }}</td>
                                    <td>
                                        <div class="flex flex-wrap gap-1.5">
                                            <span v-for="field in issue.missing_fields" :key="field" class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-900">{{ field }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!(issues?.data ?? []).length">
                                    <td colspan="6" class="admin-empty-state">Пустых контролируемых полей не найдено</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="issues" class="border-t border-slate-100 px-5 pb-6">
                        <Pagination :links="issues.links" />
                    </div>
                </div>
            </div>
        </section>
    </AdminLayout>
</template>

<script setup>
import { computed } from 'vue';
import AdminLayout from '@/Pages/Admin/Components/AdminLayout.vue';
import Pagination from '@/Shared/Pagination.vue';

const props = defineProps({
    runs: { type: Object, default: () => ({ data: [], links: [] }) },
    selectedRun: { type: Object, default: null },
    issues: { type: Object, default: null },
    summary: { type: Object, default: () => ({ positions: 0, internal_code: 0, invoice: 0, cargo: 0, oem: 0, photo: 0 }) },
});

const summaryCards = computed(() => [
    { key: 'positions', label: 'Позиций с проблемами' },
    { key: 'internal_code', label: 'Без внутреннего кода' },
    { key: 'invoice', label: 'Без артикула' },
    { key: 'cargo', label: 'Без CARGO' },
    { key: 'oem', label: 'Без OEM' },
    { key: 'photo', label: 'Без фото' },
]);

function formatDateTime(value) {
    if (!value) return '—';
    const normalized = value.includes('T') ? value : `${value.replace(' ', 'T')}Z`;
    return new Intl.DateTimeFormat('ru-RU', { dateStyle: 'short', timeStyle: 'medium' }).format(new Date(normalized));
}

function statusLabel(status) {
    return { completed: 'Успешно', failed: 'Ошибка', skipped: 'Без изменений', running: 'Выполняется' }[status] ?? status;
}

function statusClass(status) {
    return {
        completed: 'bg-green-100 text-green-800',
        failed: 'bg-red-100 text-red-800',
        skipped: 'bg-slate-100 text-slate-700',
        running: 'bg-blue-100 text-blue-800',
    }[status] ?? 'bg-slate-100 text-slate-700';
}

function productUrl(invoice) {
    return `/catalog/product/${encodeURIComponent(invoice)}`;
}
</script>
