import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { Link } from '@inertiajs/inertia-vue3';
import DetailList from "@/Shared/DetailList.vue";
import UserLayout from "@/Shared/UserLayout.vue";
import MenuButton from "@/Components/MenuButton.vue"
import CartButton from "@/Components/CartButton.vue";
import Pagination from "@/Shared/Pagination.vue"
import Search from "@/Shared/Search.vue";
import Modal from "@/Components/Modal.vue";
import PushNotification from "@/Components/PushNotification.vue";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.component('inertia-link', Link).component('detail-list', DetailList).component('layout', UserLayout).
            component('menu-button', MenuButton).component('cart-button', CartButton).component('pagination', Pagination)
            .component('modal', Modal).component('push', PushNotification);
        return app.use(plugin)
        .use(ZiggyVue, Ziggy)
        .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
