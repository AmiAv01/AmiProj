<template class="w-full">
    <div class="flex flex-col min-h-screen bg-white">
        <Header />
        <main class="flex-grow w-full">
            <slot />
        </main>
        <Footer />
    </div>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useCartStore } from "@/Store/cartStore.js";
import axios from 'axios';
import Header from "./Header/Header.vue";
import Footer from "./Footer.vue";

defineProps({
    details: Object,
    auth: {
        type: Object,
    },
});

const page = usePage();
const store = useCartStore();

const fetchCartData = () => {
    if (page.props.auth.user) {
        axios.get('/cart-data')
            .then(res => {
                if (res.data) {
                    store.setDetails(res.data.items || []);
                    store.setCartCount(res.data.cartCount || 0);
                }
            })
            .catch(err => {
                console.error("Не удалось загрузить данные корзины:", err);
            });
    }
};

const handlePageReload = () => {
    fetchCartData();
};

const handlePageShow = (event) => {
    if (event.persisted) {
        fetchCartData();
    }
};

onMounted(() => {
    fetchCartData();

    // 1. Отслеживаем нажатие кнопки "Назад/Вперед" в браузере
    window.addEventListener('popstate', handlePageReload);

    window.addEventListener('pageshow', handlePageShow);
});

onUnmounted(() => {
    window.removeEventListener('popstate', handlePageReload);
    window.removeEventListener('pageshow', handlePageShow);
});
</script>
