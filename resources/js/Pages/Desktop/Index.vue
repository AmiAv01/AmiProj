<template>
    <layout>
        <p class="text-5xl font-bold text-center p-6">Сервисная программа</p>
        <div class="flex justify-around p-8">
            <div class="flex flex-col">
                <p class="text-4xl font-bold text-center p-6">Инструкция по установке</p>
                <div class="flex justify-between">
                    <menu-button :href="`http://amiauto.by/updates/instruction.pdf`" :attributes="`px-16 py-6 mr-3 text-xl font-medium`">
                        pdf-версия
                    </menu-button>
                    <menu-button :href="`http://amiauto.by/updates/instruction.doc`" :attributes="`px-16 py-6 mr-3 text-xl font-medium`">
                        doc-версия
                    </menu-button>
                </div>
            </div>
            <div>
                <p class="text-4xl font-bold text-center p-6">Скачать для Windows</p>
                <menu-button :attributes="`px-16 py-6 mr-3 text-xl font-medium justify-center `" :href="`http://amiauto.by/updates/plist.exe`">Скачать</menu-button>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from "@/Shared/UserLayout.vue";
import MenuButton from "@/Components/MenuButton.vue";

export default {
    components: {MenuButton, Layout},
    setup() {

        return {}
    }
}
</script>
