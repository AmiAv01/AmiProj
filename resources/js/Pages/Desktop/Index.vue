<template>
    <layout>
        <p class="text-5xl font-bold text-center p-6">Сервисная программа</p>
        <div class="flex justify-around flex-wrap p-8">
            <div class="flex flex-col">
                <p class="text-4xl font-bold text-center p-6">Инструкция по установке</p>
                <div class="flex sm:justify-between justify-center flex-wrap">
                    <a v-for="[key, value] of menuItems" :href="`${value}`" :class="`flex items-center text-white bg-green-700  hover:bg-green-800  font-medium rounded-lg  text-center dark:bg-green-600 px-16 py-6 mr-3 mt-2 sm:mt-0 text-xl font-medium`" download>
                        {{key}}
                    </a>
                </div>
            </div>
            <div>
                <p class="text-4xl font-bold text-center p-6">Скачать для Windows</p>
                <a :class="`flex items-center justify-center text-white bg-green-700  hover:bg-green-800  font-medium rounded-lg  text-center dark:bg-green-600 px-16 py-6 mr-3 mt-2 sm:mt-0 text-xl font-medium`" :href="`/plist.exe`" download>Скачать</a>
            </div>
        </div>
    </layout>
</template>

<script setup>
import Layout from "@/Shared/UserLayout.vue";
import MenuButton from "@/Components/MenuButton.vue";

const menuItems = new Map([
    ['pdf-версия', '/instruction.pdf'],
    ['doc-версия', '/instruction.doc']])

</script>
