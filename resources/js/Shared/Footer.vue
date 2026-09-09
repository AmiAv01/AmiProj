<template>
    <footer class="w-full bg-slate-900">
        <div class="mx-auto grid w-full max-w-7xl gap-10 px-4 py-12 text-gray-300 md:grid-cols-2 md:px-5 lg:grid-cols-[1.5fr_0.75fr_0.75fr] lg:px-6 lg:py-16">
            <div class="max-w-xl">
                <p class="mb-4 text-2xl font-semibold text-white">Ами-Авто</p>
                <p class="mb-3 flex items-start leading-7">
                    <i class="fa-solid fa-location-dot mr-3 mt-1 text-green-500"></i>
                    <span>Минская область, Минский район, а/г Ждановичи, ул. Мира, 12</span>
                </p>
                <p class="mb-3 flex items-center">
                    <i class="fa-solid fa-phone mr-3 text-green-500"></i>
                    <a href="tel:+375296308535" class="hover:text-white">+375 29 630-85-35</a>
                </p>
                <p class="mb-3 flex items-center">
                    <i class="fa-solid fa-envelope mr-3 text-green-500"></i>
                    <a href="mailto:amiauto.minsk@gmail.com" class="hover:text-white">amiauto.minsk@gmail.com</a>
                </p>
                <p class="flex items-center">
                    <i class="fa-brands fa-skype mr-3 text-green-500"></i>
                    <span>ami.auto</span>
                </p>
            </div>
            <div class="flex flex-col items-start gap-3">
                <p class="mb-1 text-lg font-semibold text-white">О компании</p>
                <Link
                    :href="route('news.index')"
                    class="hover:text-white"
                    >Новости</Link
                >
                <Link
                    :href="route('info.index')"
                    class="hover:text-white"
                    >О нас</Link
                >
                <Link href="#" class="hover:text-white"
                    >Контакты</Link
                >
            </div>
            <div class="flex flex-col items-start gap-3">
                <p class="mb-1 text-lg font-semibold text-white">Категории</p>
                <Link href="/catalog/generators" class="hover:text-white"
                    >Генераторы</Link
                >
                <Link href="/catalog/starters" class="hover:text-white"
                    >Стартеры</Link
                >
                <Link href="/catalog/other" class="hover:text-white"
                    >Остальные запчасти</Link
                >
            </div>
        </div>
        <div class="border-t border-white/10">
            <p class="mx-auto max-w-7xl px-4 py-5 text-sm text-gray-400 md:px-5 lg:px-6">
                © Ами-Авто, 2024–{{ new Date().getFullYear() }}
            </p>
        </div>
    </footer>
</template>

<script setup>
import { Link } from '@/spa/bridge';
</script>
