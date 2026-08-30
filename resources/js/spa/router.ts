import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router';
import ApiPage from './views/ApiPage.vue';
import { useAuthStore } from './stores/auth';
import { setPageProps } from './bridge';

const routes: RouteRecordRaw[] = [
  { path: '/', component: ApiPage, meta: { endpoint: '/home', page: 'User/Index' } },
  { path: '/news', component: ApiPage, meta: { endpoint: '/news', page: 'News/News' } },
  { path: '/info', component: () => import('@/Pages/Info/Index.vue') },
  { path: '/desktop', component: () => import('@/Pages/Desktop/Index.vue') },
  { path: '/catalog/search', component: ApiPage, meta: { endpoint: '/catalog/search', page: 'SearchedCatalog/SearchedCatalog' } },
  { path: '/catalog/generators', component: ApiPage, meta: { endpoint: '/catalog/generator', page: 'Catalog/Index' } },
  { path: '/catalog/starters', component: ApiPage, meta: { endpoint: '/catalog/starter', page: 'Catalog/Index' } },
  { path: '/catalog/bearings', component: ApiPage, meta: { endpoint: '/catalog/bearing', page: 'Catalog/Index' } },
  { path: '/catalog/other', component: ApiPage, meta: { endpoint: '/catalog/other_details', page: 'Catalog/Index' } },
  { path: '/catalog/starter_parts/:category?', component: ApiPage, meta: { endpoint: '/catalog/starter_parts/:category?', page: 'Catalog/Index' } },
  { path: '/catalog/generator_parts/:category', component: ApiPage, meta: { endpoint: '/catalog/generator_parts/:category', page: 'Catalog/Index' } },
  { path: '/catalog/product/:id', component: ApiPage, meta: { endpoint: '/products/:id', page: 'Card/Index' } },
  { path: '/catalog/starter_parts/product/:id', component: ApiPage, meta: { endpoint: '/products/:id', page: 'Card/Index' } },
  { path: '/catalog/generator_parts/product/:id', component: ApiPage, meta: { endpoint: '/products/:id', page: 'Card/Index' } },
  { path: '/login', component: () => import('./views/LoginView.vue'), meta: { guest: true } },
  { path: '/register', component: () => import('./views/RegisterView.vue'), meta: { guest: true } },
  { path: '/forgot-password', component: () => import('@/Pages/Auth/ForgotPassword.vue'), meta: { guest: true } },
  { path: '/reset-password/:token', component: () => import('@/Pages/Auth/ResetPassword.vue'), props: route => ({ token: route.params.token, email: route.query.email }), meta: { guest: true } },
  { path: '/verify-email', component: () => import('@/Pages/Auth/VerifyEmail.vue'), meta: { auth: true } },
  { path: '/confirm-password', component: () => import('@/Pages/Auth/ConfirmPassword.vue'), meta: { auth: true } },
  { path: '/profile', component: ApiPage, meta: { endpoint: '/profile', page: 'Profile/Edit', auth: true } },
  { path: '/dashboard', component: () => import('@/Pages/Dashboard.vue'), meta: { auth: true } },
  { path: '/admin/resource/login', component: () => import('./views/LoginView.vue'), props: { admin: true }, meta: { guest: true } },
  { path: '/cart', component: ApiPage, meta: { endpoint: '/cart', page: 'Cart/Cart', auth: true } },
  { path: '/order', component: ApiPage, meta: { endpoint: '/orders', page: 'Order/OrderList', auth: true } },
  { path: '/order/:id', component: ApiPage, meta: { endpoint: '/orders/:id', page: 'Order/OrderCard', auth: true } },
  { path: '/admin/resource/dashboard', component: ApiPage, meta: { endpoint: '/admin/dashboard', page: 'Admin/Dashboard', auth: true, admin: true } },
  { path: '/admin/resource/details', component: ApiPage, meta: { endpoint: '/admin/details', page: 'Admin/Detail/DetailList', auth: true, admin: true } },
  { path: '/admin/resource/news', component: ApiPage, meta: { endpoint: '/admin/news', page: 'Admin/News/NewsList', auth: true, admin: true } },
  { path: '/admin/resource/orders', component: ApiPage, meta: { endpoint: '/admin/orders', page: 'Admin/Orders/OrderList', auth: true, admin: true } },
  { path: '/admin/resource/orders/:id', component: ApiPage, meta: { endpoint: '/admin/orders/:id', page: 'Admin/Orders/OrderCard', auth: true, admin: true } },
  { path: '/admin/resource/users', component: ApiPage, meta: { endpoint: '/admin/users', page: 'Admin/User/UserList', auth: true, admin: true } },
  { path: '/admin/resource/users/:id', component: ApiPage, meta: { endpoint: '/admin/users/:id', page: 'Admin/User/UserCard', auth: true, admin: true } },
  { path: '/admin/resource/currency', component: ApiPage, meta: { endpoint: '/admin/currency', page: 'Admin/Currency/Index', auth: true, admin: true } },
];

export const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior: () => ({ top: 0 }),
});

router.beforeEach(async to => {
  const auth = useAuthStore();
  await auth.load();
  setPageProps({ auth: { user: auth.user } });
  if (to.meta.auth && !auth.authenticated) return { path: '/login', query: { redirect: to.fullPath } };
  if (to.meta.admin && !auth.admin) return '/';
  if (to.meta.guest && auth.authenticated) return '/';
});
