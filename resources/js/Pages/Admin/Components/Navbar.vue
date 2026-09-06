<template>
    <nav
        class="bg-black border-b border-gray-200 px-4 py-2.5 fixed left-0 right-0 top-0 z-50"
    >
        <div class="flex flex-wrap justify-between items-center">
            <div class="flex justify-start items-center">
                <Link
                    :href="`${routes.get('admin.dashboard')}`"
                    class="flex items-center justify-between mr-4"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-6 h-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                        />
                    </svg>
                    <img
                        src="/logo2.png"
                        class="h-8"
                        alt="AmiAvto Logo"
                    />
                </Link>
            </div>
            <div
                class="flex w-[260px] justify-between items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse"
            >
                <div ref="menuContainer" class="relative">
                    <button
                        type="button"
                        class="flex max-w-48 items-center rounded-lg px-2 py-2 text-sm text-white transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-green-500"
                        :aria-expanded="isUserMenuOpen"
                        aria-controls="admin-user-dropdown"
                        @click="toggleUserMenu"
                    >
                        <span class="sr-only">Открыть меню пользователя</span>
                        <i class="fa-solid fa-user text-white text-xl"></i>
                        <span class="ml-2 truncate">{{ $page.props.auth.user.name }}</span>
                        <svg class="ml-2 h-4 w-4 shrink-0 transition-transform" :class="{ 'rotate-180': isUserMenuOpen }" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div
                        v-show="isUserMenuOpen"
                        class="absolute right-0 top-full z-50 mt-2 w-56 overflow-hidden rounded-lg bg-white text-base shadow-xl ring-1 ring-black/5"
                        id="admin-user-dropdown"
                    >
                    <div class="px-4 py-3">
                            <span
                                class="block text-sm text-gray-900"
                            >{{ $page.props.auth.user.name }}</span
                            >
                        <span
                            class="block text-sm text-gray-500 truncate"
                        >{{ $page.props.auth.user.email }}</span
                        >
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <spa-link
                                :href="`${routes.get('profile.edit')}`"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                @click="closeUserMenu"
                            >Редактировать профиль</spa-link
                            >
                        </li>
                        <li>
                            <spa-link
                                :href="`${routes.get('logout')}`"
                                method="post"
                                as="button"
                                class="block w-full px-4 py-2 text-left text-sm text-red-700 hover:bg-red-50"
                                @click="closeUserMenu"
                            >Выход</spa-link
                            >
                        </li>
                    </ul>
                    </div>
                </div>
                <div v-if="!isMenuOpen" class="w-full flex justify-end relative">
                    <button @click="toggleBurgerMenu" value="hamburger" class="group relative h-[30px] w-[30px] rounded mr-4 hover:bg-gray-400 ">
                        <span :class="!isBurgerMenuOpen ? 'block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-white translate-y-[-8px]'
                            : 'block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-white  translate-y-0 -rotate-45'"></span>
                        <span :class="!isBurgerMenuOpen ? 'block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-white'
                            : 'rotate-360 -translate-x-20px opacity-0 block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-white' "></span>
                        <span :class="!isBurgerMenuOpen ? 'block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-white translate-y-[8px]'
                            : 'block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-white  translate-y-0 rotate-45'"></span>
                    </button>
                </div>
                <AdminBurgerMenu :is-show="isBurgerMenuOpen"/>
            </div>
        </div>
    </nav>
</template>

<script setup>
import {Link} from '@/spa/bridge';
import {onBeforeUnmount, onMounted, ref} from "vue";
import {routes} from "@/Store/routes";
import AdminBurgerMenu from "@/Pages/Admin/Components/AdminBurgerMenu.vue";

const isMenuOpen = ref(true);
const isBurgerMenuOpen = ref(false);
const isUserMenuOpen = ref(false);
const menuContainer = ref(null);
const innerWidth = ref(window.innerWidth);

onMounted(() => {
    window.addEventListener('resize', handleWindowResize);
    document.addEventListener('click', handleOutsideClick);
    document.addEventListener('keydown', handleEscape);
    handleWindowResize();
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', handleWindowResize);
    document.removeEventListener('click', handleOutsideClick);
    document.removeEventListener('keydown', handleEscape);
});

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

const handleWindowResize = () => {
    innerWidth.value = window.innerWidth;
    console.log(window.innerWidth)
    isMenuOpen.value = (innerWidth.value > 1024)
}

const toggleBurgerMenu = () => {
    isBurgerMenuOpen.value = !isBurgerMenuOpen.value
}
</script>
