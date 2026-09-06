<template>
    <menu-button
        :attributes="`px-4 py-2 text-sm mr-4`"
        :href="route('cart.index')"
    >
        <div class="flex items-center">
            <svg
                class="w-4 h-4 mr-2 fill-white"
                viewBox="0 0 576 512"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"
                ></path>
            </svg>
            Корзина
            <span v-if="cartStore.cartCount > 0" class="ml-2 w-5 h-5 flex items-center justify-center rounded-full bg-red-500 text-white text-xs">
            {{ cartStore.cartCount }}
        </span>
        </div>
    </menu-button>
    <menu-button
        :attributes="`px-4 py-2 text-sm ml-4`"
        :href="route('order.index')"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-4 h-4 mr-2 fill-white"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="#000000"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <path
                d="M6 2L3 6v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V6l-3-4H6zM3.8 6h16.4M16 10a4 4 0 1 1-8 0"
            />
        </svg>
        Заказы
    </menu-button>
    <div ref="menuContainer" class="relative flex items-center">
        <button
            type="button"
            class="flex items-center rounded-lg px-2 py-2 text-sm text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-green-500"
            :aria-expanded="isUserMenuOpen"
            aria-controls="user-dropdown"
            @click="toggleUserMenu"
        >
                <span class="sr-only">Открыть меню пользователя</span>
                <i class="fa-solid fa-user text-white text-xl"></i>
                <span class="ml-2 max-w-36 truncate">{{ $page.props.auth.user.name }}</span>
                <svg class="ml-2 h-4 w-4 transition-transform" :class="{ 'rotate-180': isUserMenuOpen }" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                </svg>
        </button>

        <div
            v-show="isUserMenuOpen"
            id="user-dropdown"
            class="absolute right-0 top-full z-50 mt-2 w-56 overflow-hidden rounded-lg bg-white text-base shadow-xl ring-1 ring-black/5"
        >
            <div class="border-b border-gray-100 px-4 py-3">
                <span class="block truncate text-sm text-gray-500">{{ $page.props.auth.user.email }}</span>
            </div>
            <ul class="py-2">
                <li>
                    <spa-link
                        :href="`${routes.get('profile.edit')}`"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        @click="closeUserMenu"
                    >Редактировать профиль</spa-link>
                </li>
                <li>
                    <spa-link
                        :href="`${routes.get('logout')}`"
                        method="post"
                        as="button"
                        class="block w-full px-4 py-2 text-left text-sm text-red-700 hover:bg-red-50"
                        @click="closeUserMenu"
                    >Выход</spa-link>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { routes } from "@/Store/routes";
import { useCartStore } from "@/Store/cartStore";

const cartStore = useCartStore();
const isUserMenuOpen = ref(false);
const menuContainer = ref(null);

const toggleUserMenu = () => {
    isUserMenuOpen.value = !isUserMenuOpen.value;
};

const closeUserMenu = () => {
    isUserMenuOpen.value = false;
};

const handleOutsideClick = (event) => {
    if (!menuContainer.value?.contains(event.target)) {
        closeUserMenu();
    }
};

const handleEscape = (event) => {
    if (event.key === 'Escape') {
        closeUserMenu();
    }
};

onMounted(() => {
    document.addEventListener('click', handleOutsideClick);
    document.addEventListener('keydown', handleEscape);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleOutsideClick);
    document.removeEventListener('keydown', handleEscape);
});
</script>

