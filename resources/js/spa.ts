import './bootstrap';
import '../css/app.css';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './spa/App.vue';
import { router } from './spa/router';
import { Link, pageContext } from './spa/bridge';
import DetailList from '@/Shared/DetailList.vue';
import UserLayout from '@/Shared/UserLayout.vue';
import MenuButton from '@/Components/MenuButton.vue';
import CartButton from '@/Components/CartButton.vue';
import Pagination from '@/Shared/Pagination.vue';
import Modal from '@/Components/Modal.vue';
import PushNotification from '@/Components/PushNotification.vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m.js';

const app = createApp(App);
app.config.globalProperties.$page = pageContext;
app.component('spa-link', Link)
  .component('detail-list', DetailList)
  .component('layout', UserLayout)
  .component('menu-button', MenuButton)
  .component('cart-button', CartButton)
  .component('pagination', Pagination)
  .component('modal', Modal)
  .component('push', PushNotification);
app.use(createPinia()).use(router).use(ZiggyVue, Ziggy).mount('#app');
