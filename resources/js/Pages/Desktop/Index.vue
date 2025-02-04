<template>
    <layout>
        <p class="text-5xl font-bold text-center p-6">Сервисная программа</p>
        <div class="flex justify-around flex-wrap p-8">
            <div class="flex flex-col">
                <p class="text-4xl font-bold text-center p-6">Инструкция по установке</p>
                <div class="flex sm:justify-between justify-center flex-wrap">
                    <MenuButton v-for="[key, value] of menuItems" :href="`${value}`" :attributes="`px-16 py-6 mr-3 mt-2 sm:mt-0 text-xl font-medium`">
                        {{key}}
                    </MenuButton>
                </div>
            </div>
            <div>
                <p class="text-4xl font-bold text-center p-6">Скачать для Windows</p>
                <MenuButton :attributes="`px-16 py-6 mr-3 text-xl font-medium justify-center `" :href="`http://amiauto.by/updates/plist.exe`">Скачать</MenuButton>
            </div>
        </div>
    </layout>
</template>

<script setup>
import Layout from "@/Shared/UserLayout.vue";
import MenuButton from "@/Components/MenuButton.vue";

const menuItems = new Map([
    ['pdf-версия', 'http://amiauto.by/updates/instruction.pdf'],
    ['doc-версия', 'http://amiauto.by/updates/instruction.doc']])

</script>
