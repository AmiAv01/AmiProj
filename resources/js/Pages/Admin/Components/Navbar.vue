<template>
    <nav
        class="fixed inset-x-0 top-0 z-50 h-[72px] border-b border-slate-200 bg-white/95 px-4 shadow-sm backdrop-blur sm:px-6"
    >
        <div class="mx-auto flex h-full items-center justify-between">
            <div class="flex min-w-0 items-center">
                <Link
                    :href="`${routes.get('admin.dashboard')}`"
                    class="flex items-center gap-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
                >
                    <img
                        src="/logo2.png"
                        class="h-9 w-auto"
                        alt="AmiAvto Logo"
                    />
                    <span class="hidden border-l border-slate-200 pl-3 text-sm font-semibold text-slate-600 sm:block">
                        Панель управления
                    </span>
                </Link>
            </div>
            <div
                class="flex items-center gap-2"
            >
                <div ref="menuContainer" class="relative">
                    <button
                        type="button"
                        class="flex max-w-52 items-center rounded-xl px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-green-600"
                        :aria-expanded="isUserMenuOpen"
                        aria-controls="admin-user-dropdown"
                        @click="toggleUserMenu"
                    >
                        <span class="sr-only">Открыть меню пользователя</span>
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-800">
                            <i class="fa-solid fa-user text-sm"></i>
                        </span>
                        <span class="ml-2 hidden truncate sm:block">{{ $page.props.auth.user.name }}</span>
                        <svg class="ml-2 h-4 w-4 shrink-0 transition-transform" :class="{ 'rotate-180': isUserMenuOpen }" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div
                        v-show="isUserMenuOpen"
                        class="absolute right-0 top-full z-50 mt-2 w-64 overflow-hidden rounded-xl border border-slate-200 bg-white text-base shadow-xl"
                        id="admin-user-dropdown"
                    >
                    <div class="border-b border-slate-100 px-4 py-3">
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
                                class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50"
                                @click="closeUserMenu"
                            >Редактировать профиль</spa-link
                            >
                        </li>
                        <li>
                            <spa-link
                                :href="`${routes.get('logout')}`"
                                method="post"
                                as="button"
                                class="block w-full px-4 py-2.5 text-left text-sm text-red-700 hover:bg-red-50"
                                @click="closeUserMenu"
                            >Выход</spa-link
                            >
                        </li>
                    </ul>
                    </div>
                </div>
                <div v-if="!isMenuOpen" class="relative flex justify-end">
                    <button @click="toggleBurgerMenu" value="hamburger" class="group relative h-10 w-10 rounded-xl hover:bg-slate-100" aria-label="Открыть меню">
                        <span :class="!isBurgerMenuOpen ? 'block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-white translate-y-[-8px]'
                            : 'block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-slate-700 translate-y-0 -rotate-45'" class="!bg-slate-700"></span>
                        <span :class="!isBurgerMenuOpen ? 'block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-white'
                            : 'rotate-360 -translate-x-20px opacity-0 block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-slate-700' " class="!bg-slate-700"></span>
                        <span :class="!isBurgerMenuOpen ? 'block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-white translate-y-[8px]'
                            : 'block h-[2px] w-[25px] m-auto absolute top-0 left-0 right-0 bottom-0 transition-all delay-400 ease-in-out rounded bg-slate-700 translate-y-0 rotate-45'" class="!bg-slate-700"></span>
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
    isMenuOpen.value = (innerWidth.value > 1024)
    if (isMenuOpen.value) {
        isBurgerMenuOpen.value = false;
    }
}

const toggleBurgerMenu = () => {
    isBurgerMenuOpen.value = !isBurgerMenuOpen.value
}
</script>
