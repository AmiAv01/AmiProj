<template>
    <div
        class="col-span-12 xl:col-span-4 bg-gray-50 w-full max-xl:px-6 max-w-3xl xl:max-w-lg mx-auto lg:pl-8 py-24"
    >
        <h2
            class="font-manrope font-bold text-3xl leading-10 text-black pb-8 border-b border-gray-300"
        >
            Ваш заказ
        </h2>
        <div class="mt-8">
            <form>
                <div class="flex pb-4 w-full"></div>

                <div class="flex items-center justify-between py-8">
                    <p class="font-medium text-xl leading-8 text-black">
                        Товары, {{ count }} шт.
                    </p>
                    <p class="font-semibold text-xl leading-8 text-indigo-600">
                        {{ price }} BYN
                    </p>
                </div>
                <button
                    class="w-full text-center bg-indigo-600 rounded-xl py-3 px-6 font-semibold text-lg text-white transition-all duration-500 hover:bg-indigo-700"
                >
                    Оформить заказ
                </button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        count: {
            type: Number,
            default: 0,
        },
        price: {
            type: Number,
            default: 0,
        },
    },
};
</script>
