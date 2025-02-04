<template>
    <footer class="w-full bg-slate-900 mb-[-10px]">
        <div class=" mx-auto w-full flex flex-wrap justify-around pb-32 pt-16">
            <div class="">
                <p class="text-white text-2xl mb-2">Ами-Авто</p>
                <p class="mb-2 text-white">
                    <i class="fa-solid fa-location-dot text-white mr-4"> </i
                    >Минская область , Минский район , а/г. Ждановичи , ул. Мира
                    12
                </p>
                <p class="text-white mb-2">
                    <i class="fa-solid fa-envelope text-white mr-2"></i>+375 29
                    630-85-35
                </p>
                <p class="text-white mb-2">
                    <i class="fa-solid fa-phone text-white mr-2"></i
                    >amiauto.minsk@gmail.com
                </p>
                <p class="text-white">
                    <i class="fa-brands fa-skype text-white mr-2"></i>ami.auto
                </p>
            </div>
            <div class="flex flex-col">
                <p class="text-2xl text-white mb-2">О компании</p>
                <Link
                    :href="route('news.index')"
                    class="text-white mb-2 hover:text-green-700"
                    >Новости</Link
                >
                <Link
                    :href="route('info.index')"
                    class="text-white mb-2 hover:text-green-700"
                    >О нас</Link
                >
                <Link class="text-white mb-2 hover:text-green-700"
                    >Контакты</Link
                >
            </div>
            <div class="flex flex-col">
                <p class="text-white text-2xl mb-2">Категории</p>
                <Link class="text-white mb-2 hover:text-green-700"
                    >Генераторы</Link
                >
                <Link class="text-white mb-2 hover:text-green-700"
                    >Стартеры</Link
                >
                <Link class="text-white hover:text-green-700"
                    >Остальные запчасти</Link
                >
            </div>
        </div>
        <p class="text-white text-center pb-2">
            <i class="fa-regular fa-copyright"></i> Ами-Авто 2024-...
        </p>
    </footer>
</template>

<script setup>
import { Link } from "@inertiajs/inertia-vue3";
</script>
