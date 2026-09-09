<template>
    <layout>
        <section class="bg-slate-50 py-14 sm:py-20 lg:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <header class="max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-green-700">Для клиентов</p>
                    <h1 class="mt-3 text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">Сервисная программа</h1>
                    <p class="mt-5 text-lg leading-8 text-slate-600">Скачайте программу для Windows и выберите удобный формат инструкции по установке.</p>
                </header>

                <div class="mt-10 grid items-stretch gap-6 lg:grid-cols-2">
                    <article class="flex flex-col overflow-hidden rounded-2xl bg-slate-900 p-6 text-white shadow-sm sm:p-8">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-green-500/15 text-green-400">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="4" width="18" height="13" rx="2" /><path stroke-linecap="round" d="M8 21h8M12 17v4" /></svg>
                        </div>
                        <div class="mt-7">
                            <p class="text-sm font-semibold uppercase tracking-[0.14em] text-green-400">Windows · EXE</p>
                            <h2 class="mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">Программа для компьютера</h2>
                            <p class="mt-3 max-w-xl text-base leading-7 text-slate-300">Загрузите установочный файл и следуйте шагам из инструкции.</p>
                        </div>
                        <a href="/plist.exe" download class="mt-8 inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-green-600 px-5 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 focus:ring-offset-slate-900 sm:w-auto sm:self-start">
                            <svg class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.69L6.53 8.72a.75.75 0 0 0-1.06 1.06l4 4a.75.75 0 0 0 1.06 0l4-4a.75.75 0 1 0-1.06-1.06l-2.72 2.72V2.75Z" /><path d="M3.5 13.25a.75.75 0 0 0-1.5 0v2.25A2.5 2.5 0 0 0 4.5 18h11a2.5 2.5 0 0 0 2.5-2.5v-2.25a.75.75 0 0 0-1.5 0v2.25a1 1 0 0 1-1 1h-11a1 1 0 0 1-1-1v-2.25Z" /></svg>
                            Скачать для Windows
                        </a>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-green-50 text-green-700">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linejoin="round" d="M6 3h8l4 4v14H6z" /><path stroke-linejoin="round" d="M14 3v5h4M9 13h6M9 17h4" /></svg>
                        </div>
                        <h2 class="mt-7 text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">Инструкция по установке</h2>
                        <p class="mt-3 text-base leading-7 text-slate-600">Откройте инструкцию в подходящем для вас формате.</p>
                        <div class="mt-6 space-y-3">
                            <a v-for="item in documents" :key="item.href" :href="item.href" download class="group flex min-h-16 items-center justify-between gap-4 rounded-xl border border-slate-200 px-4 py-3 transition hover:border-green-300 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2">
                                <span class="flex min-w-0 items-center gap-4">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold uppercase text-slate-600 group-hover:bg-white group-hover:text-green-700">{{ item.extension }}</span>
                                    <span><span class="block text-base font-semibold text-slate-900">{{ item.title }}</span><span class="mt-0.5 block text-sm text-slate-500">Скачать файл</span></span>
                                </span>
                                <svg class="h-5 w-5 shrink-0 text-slate-400 transition group-hover:translate-y-0.5 group-hover:text-green-700" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.69L6.53 8.72a.75.75 0 0 0-1.06 1.06l4 4a.75.75 0 0 0 1.06 0l4-4a.75.75 0 1 0-1.06-1.06l-2.72 2.72V2.75Z" /><path d="M3.5 13.25a.75.75 0 0 0-1.5 0v2.25A2.5 2.5 0 0 0 4.5 18h11a2.5 2.5 0 0 0 2.5-2.5v-2.25a.75.75 0 0 0-1.5 0v2.25a1 1 0 0 1-1 1h-11a1 1 0 0 1-1-1v-2.25Z" /></svg>
                            </a>
                        </div>
                    </article>
                </div>

            </div>
        </section>
    </layout>
</template>

<script setup>
import Layout from "@/Shared/UserLayout.vue";

const documents = [
    { title: 'Инструкция в PDF', extension: 'PDF', href: '/instruction.pdf' },
    { title: 'Инструкция в DOC', extension: 'DOC', href: '/instruction.doc' },
];
</script>
