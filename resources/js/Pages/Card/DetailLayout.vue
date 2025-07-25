<template>
    <div class="rounded-lg border-2 w-full">
        <p class="text-lg font-bold px-4 py-4 text-center border-b-2">
            <strong>Деталировка</strong>
        </p>
        
        <div>
            <div class="grid grid-cols-3 bg-gray-100 p-3 font-semibold border-b">
                <div class="text-left">Артикул</div>
                <div class="text-left">Название</div>
                <div class="text-left">Бренд</div>
            </div>
            
            <div 
                v-for="(item, index) in details" 
                :key="index"
                class="grid grid-cols-3 p-3 border-b hover:bg-gray-50"
            >
                <div class="flex items-center">
                    <a 
                        :href="`../../catalog/product/${item.dt_invoice}`"
                        class="text-blue-600 hover:underline text-sm sm:text-base"
                    >
                        {{ item.dt_invoice }}
                    </a>
                </div>
                
                <div class="flex items-center text-sm sm:text-base">
                    {{ item.dt_typec }}
                </div>
                
                <div class="flex items-center text-sm sm:text-base">
                    {{ item.fr_code }}
                </div>
            </div>
            
            <div class="bg-white w-full p-4 flex text-lg text-gray-400 items-center justify-center" v-if="!details.length">
                <p>Детализация отсутствует</p>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    details: {
        type: Array,
        required: true
    }
});
</script>