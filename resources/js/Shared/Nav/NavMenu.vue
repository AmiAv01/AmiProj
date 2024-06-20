<template>
    <div class="bg-green-700 w-full">
        <ul class="flex w-[68%] relative mx-auto my-0">
            <li class="px-12 py-2 hover:bg-green-900 cursor-pointer">
                <Link href="/catalog/generators" class="text-white">
                    Генераторы
                </Link>
            </li>
            <li class="px-12 py-2 hover:bg-green-900 cursor-pointer">
                <Link href="/catalog/starters" class="text-white">
                    Стартеры
                </Link>
            </li>
            <li
                class="flex group/main group items-center px-12 py-2 hover:bg-green-900 cursor-pointer"
            >
                <p class="text-white">Запчасти стартера</p>
                <svg
                    class="w-2.5 h-2.5 ms-1 text-white group-hover:rotate-180"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 10 6"
                >
                    <path
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m1 1 4 4 4-4"
                    />
                </svg>
                <ul
                    class="absolute top-[100%] left-0 flex-col w-full justify-between bg-slate-900 rounded-[15px] border-b p-3 hidden z-50 group-hover/main:grid grid-cols-4 gap-4"
                >
                    <MenuItem
                        value="Вилки стартера"
                        link="/catalog/starter_parts/fork"
                    />
                    <MenuItem
                        value="Втулки стартера"
                        link="/catalog/starter_parts/sleeve"
                    />
                    <MenuItem
                        value="Втягивающие реле"
                        link="/catalog/starter_parts/relay"
                    />
                    <MenuItem
                        value="Детали втягивающего реле"
                        link="/catalog/starter_parts/relay_parts"
                    />
                    <MenuItem
                        value="Детали привода стартера"
                        link="/catalog/starter_parts/unit_parts"
                    />
                    <MenuItem
                        value="Крышки и головы"
                        link="/catalog/starter_parts/cover"
                    />
                    <MenuItem
                        value="Приводы"
                        link="/catalog/starter_parts/unit"
                    />
                    <MenuItem
                        value="Прочее для стартера"
                        link="/catalog/starter_parts/other"
                    />
                    <MenuItem
                        value="Редукторы и компоненты"
                        link="/catalog/starter_parts/reducer"
                    />
                    <MenuItem
                        value="Ремкомплекты"
                        link="/catalog/starter_parts/repair"
                    />
                    <MenuItem
                        value="Сальники стартера"
                        link="/catalog/starter_parts/glands"
                    />
                    <MenuItem
                        value="Статоры"
                        link="/catalog/starter_parts/stator"
                    />
                    <MenuItem
                        value="Щётки стартера"
                        link="/catalog/starter_parts/brush"
                    />
                    <MenuItem
                        value="Щёткодержатели стартера"
                        link="/catalog/starter_parts/holder"
                    />
                    <MenuItem
                        value="Якоря"
                        link="/catalog/starter_parts/anchor"
                    />
                </ul>
            </li>
            <li
                class="flex group/main group items-center px-12 py-2 hover:bg-green-900 cursor-pointer"
            >
                <p class="text-white">Запчасти генератора</p>
                <svg
                    class="w-2.5 h-2.5 ms-1 text-white group-hover:rotate-180"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 10 6"
                >
                    <path
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m1 1 4 4 4-4"
                    />
                </svg>
                <ul
                    class="absolute top-[100%] left-0 flex-col w-full justify-between bg-slate-900 rounded-[15px] border-b p-3 hidden z-50 group-hover/main:grid grid-cols-4 gap-4"
                >
                    <MenuItem
                        value="Втулки генератора"
                        link="/catalog/generator_parts/sleeve"
                    />
                    <MenuItem
                        value="Вакуумные насосы"
                        link="/catalog/generator_parts/pump"
                    />
                    <MenuItem
                        value="Выпрямители"
                        link="/catalog/generator_parts/rectifier"
                    />
                    <MenuItem
                        value="Диоды и триоды"
                        link="/catalog/generator_parts/diode"
                    />
                    <MenuItem
                        value="Изоляторы"
                        link="/catalog/generator_parts/insulator"
                    />
                    <MenuItem
                        value="Крышки"
                        link="/catalog/generator_parts/cover"
                    />
                    <MenuItem
                        value="Регуляторы"
                        link="/catalog/generator_parts/regulator"
                    />
                    <MenuItem
                        value="Роторы"
                        link="/catalog/generator_parts/rotor"
                    />
                    <MenuItem
                        value="Терминалы"
                        link="/catalog/generator_parts/terminal"
                    />
                    <MenuItem
                        value="Сальники"
                        link="/catalog/generator_parts/gland"
                    />
                    <MenuItem
                        value="Статорные обмотки"
                        link="/catalog/generator_parts/winding"
                    />
                    <MenuItem
                        value="Шкивы"
                        link="/catalog/generator_parts/pulley"
                    />
                    <MenuItem
                        value="Щётки генератора"
                        link="/catalog/generator_parts/brush"
                    />
                    <MenuItem
                        value="Щёточные узлы"
                        link="/catalog/generator_parts/holder"
                    />
                </ul>
            </li>
            <li
                class="flex group items-center px-12 py-2 hover:bg-green-900 cursor-pointer"
            >
                <Link href="/catalog/bearings" class="text-white"
                    >Подшипники</Link
                >
            </li>
            <li class="px-12 py-2 hover:bg-green-900 cursor-pointer">
                <Link href="/catalog/other" class="text-white"
                    >Прочие запчасти</Link
                >
            </li>
        </ul>
    </div>
</template>

<script setup>
import MenuItem from "@/Shared/Nav/NavMenuItem.vue";
import { Link } from "@inertiajs/vue3";
</script>
