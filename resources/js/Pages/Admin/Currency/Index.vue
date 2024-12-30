<template>
    <admin-layout>
        <p class="text-5xl font-bold tracking-tight text-gray-900 mb-10">
            Курс валют
        </p>
        <div class="flex">
            <input
                class="shadow appearance-none border rounded w-[300px] py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                type="number"
                step="0.1"
                min="1"
                v-model="currencyValue"
                placeholder="Ввести курс валют"
            />
            <button
                @click="changeCurrency"
                class="bg-green-700 ml-2 rounded-lg text-white px-5 py-2.5 text-lg "
            >
                Изменить
            </button>
        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";

    export default {
        components: {AdminLayout},
        data(){
            return {
                currencyValue: 0
            }
        },
        methods: {
            changeCurrency(){
                axios.post(`/admin/currency`, {currency: this.currencyValue})
                    .then(res => console.log(res.data))
                    .catch(err => console.error(err))
            }
        }
    }
</script>


<style scoped>

</style>
