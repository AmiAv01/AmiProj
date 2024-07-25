<template>
    <div class="rounded-[15px] h-[350px] bg-green-300">
        <p
            class="text-3xl bg-green-700 text-white rounded-tr-[15px] rounded-tl-[15px] font-bold p-4"
        >
            Выбрать статус
        </p>
        <form action="" class="h-[250px] flex flex-col">
            <ul :class="`h-[200px] overflow-y-auto p-6 bg-green-300`">
                <li
                    v-for="(status, index) in statuses"
                    :key="index"
                    class="py-[4px]"
                >
                    <input
                        type="checkbox"
                        :value="status"
                        class="mr-2 rounded-[5px] w-5 h-5 focus:ring-0 focus:outline-none cursor-pointer"
                        v-model="checked"
                    />
                    <label class="text-lg">{{ status }}</label>
                </li>
            </ul>
            <menu-button
                :href="`${this.currentUrl}?filter[id]=${this.checked.join()}`"
                :attributes="`px-5 py-2.5 mx-auto text-lg mt-4`"
            >
                Подобрать
            </menu-button>
            <button class="text-lg mx-auto" @click="resetChecked">
                Сбросить фильтр
            </button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            statuses: ["Новый", "Принят", "Выполнен"],
            currentUrl: null,
            checked: [],
        };
    },
    created: function () {
        this.currentUrl = window.location.pathname;
        console.log(`Current URL => ${this.currentUrl}`);
        console.log(this.clientBrands);
        /*if (this.clientBrands !== null) {
            this.checked = this.clientBrands["id"].split(",");
        }*/
    },
};
</script>
