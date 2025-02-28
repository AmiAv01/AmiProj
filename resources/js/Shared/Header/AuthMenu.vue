<template>
    <button
        type="button"
        class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
        id="user-menu-button"
        aria-expanded="false"
        data-dropdown-toggle="user-dropdown"
        data-dropdown-placement="bottom"
    >
        <span class="sr-only">Open user menu</span>
        <i class="fa-solid fa-user text-white text-xl"></i>
    </button>
    <!-- Dropdown menu -->
    <div
        class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
        id="user-dropdown"
    >
        <div class="px-4 py-3">
                            <span
                                class="block text-sm text-gray-900 dark:text-white"
                            >{{ $page.props.auth.user.name }}</span
                            >
            <span
                class="block text-sm text-gray-500 truncate dark:text-gray-400"
            >{{ $page.props.auth.user.email }}</span
            >
        </div>
        <ul class="py-2" aria-labelledby="user-menu-button">
            <li>
                <inertia-link
                    :href="`${routes.get('profile.edit')}`"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                >Настройки</inertia-link
                >
            </li>
            <li>
                <inertia-link
                    :href="`${routes.get('logout')}`"
                    method="post"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                >Выход</inertia-link
                >
            </li>
        </ul>
    </div>
    <menu-button
        :attributes="`px-4 py-2 text-sm mr-4`"
        :href="route('cart.index')"
    >
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
</template>

<script setup>
    import {routes} from "@/Store/routes.js";
</script>


