<template>
    <layout>
        <section class="bg-slate-50 py-14 sm:py-20 lg:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <header class="max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-green-700">Ами-Авто</p>
                    <h1 class="mt-3 text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">О компании</h1>
                    <p class="mt-5 text-lg leading-8 text-slate-600">
                        Мы занимаемся автомобильными запчастями для стартеров и генераторов и помогаем подобрать нужную позицию из каталога.
                    </p>
                </header>

                <div class="mt-10 grid overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm lg:grid-cols-[minmax(0,1.25fr)_minmax(380px,0.75fr)]">
                    <div class="min-w-0 border-b border-slate-200 lg:border-b-0 lg:border-r">
                        <div class="h-[360px] overflow-hidden bg-slate-100 sm:h-[460px] lg:h-full lg:min-h-[580px]">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2348.766396578331!2d27.417182076996344!3d53.93589393044868!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46dbc4bc3e408231%3A0x56bdcf3265bbe0e8!2z0YPQuy4g0JzQuNGA0LAsINCW0LTQsNC90L7QstC40YfQuCwg0JzQuNC90YHQutCw0Y8g0L7QsdC70LDRgdGC0YwgMjIzMDI4!5e0!3m2!1sru!2sby!4v1711972088485!5m2!1sru!2sby"
                                title="Расположение склада Ами-Авто"
                                class="h-full w-full border-0"
                                allowfullscreen
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 lg:p-10">
                        <p class="text-sm font-semibold uppercase tracking-[0.14em] text-green-700">Связаться с нами</p>
                        <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Контактная информация</h2>

                        <dl class="mt-8 space-y-6">
                            <div v-for="contact in contacts" :key="contact.label" class="flex gap-4">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-700">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path :d="contact.icon" />
                                    </svg>
                                </span>
                                <div class="min-w-0 pt-0.5">
                                    <dt class="text-sm font-medium text-slate-500">{{ contact.label }}</dt>
                                    <dd class="mt-1 text-base font-semibold leading-6 text-slate-900">
                                        <a v-if="contact.href" :href="contact.href" class="break-words underline decoration-slate-300 underline-offset-4 transition hover:text-green-700 hover:decoration-green-500">{{ contact.value }}</a>
                                        <span v-else>{{ contact.value }}</span>
                                    </dd>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-700">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-12.25a.75.75 0 0 0-1.5 0v4.56c0 .2.08.39.22.53l2.75 2.75a.75.75 0 1 0 1.06-1.06l-2.53-2.53V5.75Z" clip-rule="evenodd" /></svg>
                                </span>
                                <div class="min-w-0 pt-0.5">
                                    <dt class="text-sm font-medium text-slate-500">Время приёма заявок</dt>
                                    <dd class="mt-1 space-y-1 text-base leading-6 text-slate-900">
                                        <p><span class="font-semibold">Пн–Пт:</span> 9:00–18:00</p>
                                        <p><span class="font-semibold">Сб–Вс:</span> выходной</p>
                                    </dd>
                                </div>
                            </div>
                        </dl>

                        <a
                            href="https://www.google.com/maps/search/?api=1&query=53.93589393044868,27.417182076996344"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-8 inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-green-700 px-5 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 sm:w-auto"
                        >
                            Открыть маршрут
                            <svg class="ml-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 0 1 .75-.75h10a.75.75 0 0 1 .75.75v10a.75.75 0 0 1-1.5 0V7.31L5.53 16.03a.75.75 0 0 1-1.06-1.06l8.72-8.72H5a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </layout>
</template>

<script setup>
import Layout from "@/Shared/UserLayout.vue";

const contacts = [
    {
        label: 'Телефон',
        value: '+375 29 630-85-35',
        href: 'tel:+375296308535',
        icon: 'M2 3.5A1.5 1.5 0 0 1 3.5 2h1.15c.69 0 1.29.47 1.46 1.14l.7 2.8a1.5 1.5 0 0 1-.42 1.44L5.31 8.46a11.04 11.04 0 0 0 6.23 6.23l1.08-1.08a1.5 1.5 0 0 1 1.44-.42l2.8.7A1.5 1.5 0 0 1 18 15.35v1.15a1.5 1.5 0 0 1-1.5 1.5H16C8.27 18 2 11.73 2 4v-.5Z',
    },
    {
        label: 'Email',
        value: 'amiauto.minsk@gmail.com',
        href: 'mailto:amiauto.minsk@gmail.com',
        icon: 'M2.94 5.5 9.2 9.98a1.4 1.4 0 0 0 1.6 0l6.26-4.48A2.5 2.5 0 0 0 14.5 3h-9a2.5 2.5 0 0 0-2.56 2.5ZM18 7.09l-6.33 4.52a2.9 2.9 0 0 1-3.34 0L2 7.09v7.41A2.5 2.5 0 0 0 4.5 17h11a2.5 2.5 0 0 0 2.5-2.5V7.09Z',
    },
    {
        label: 'Адрес склада',
        value: 'Минская область, Минский район, а/г Ждановичи, ул. Мира, 12',
        icon: 'M10 1.75A6.25 6.25 0 0 0 3.75 8c0 4.54 5.45 9.67 5.68 9.88a.84.84 0 0 0 1.14 0c.23-.21 5.68-5.34 5.68-9.88A6.25 6.25 0 0 0 10 1.75Zm0 8.5a2.25 2.25 0 1 1 0-4.5 2.25 2.25 0 0 1 0 4.5Z',
    },
];
</script>
