import axios, { type Method } from 'axios';
import { defineComponent, h, reactive, ref, watchEffect, type PropType } from 'vue';
import { RouterLink, useRoute, useRouter } from 'vue-router';
import { api } from '@/api/client';
import { ensureCsrfCookie } from '@/api/client';
import { useAuthStore } from '@/spa/stores/auth';

const sharedProps = reactive<Record<string, unknown>>({ auth: { user: null } });
export const pageContext = reactive({ props: sharedProps, url: window.location.pathname });

export function setPageProps(props: Record<string, unknown>): void {
  Object.keys(sharedProps).forEach(key => delete sharedProps[key]);
  Object.assign(sharedProps, props);
  const auth = useAuthStore();
  sharedProps.auth = { user: auth.user };
}

function spaPath(href: string): string {
  try {
    const url = new URL(href, window.location.origin);
    return `${url.pathname.replace(/^\/spa(?=\/|$)/, '') || '/'}${url.search}${url.hash}`;
  } catch { return href; }
}

function apiPath(url: string, method: Method): string {
  const path = new URL(url, window.location.origin).pathname;
  const exact: Record<string, string> = {
    '/login': '/api/v1/auth/login', '/register': '/api/v1/auth/register', '/logout': '/api/v1/auth/logout',
    '/forgot-password': '/api/v1/auth/forgot-password', '/reset-password': '/api/v1/auth/reset-password',
    '/confirm-password': '/api/v1/auth/confirm-password', '/email/verification-notification': '/api/v1/auth/verification-notification',
    '/password': '/api/v1/profile/password', '/profile': '/api/v1/profile',
    '/admin/resource/login': '/api/v1/auth/admin-login',
  };
  if (exact[path]) return exact[path];
  if (method !== 'get' && path.startsWith('/admin/resource/')) return `/api/v1/admin/${path.slice('/admin/resource/'.length)}`;
  return path;
}

export const Link = defineComponent({
  name: 'SpaLink',
  inheritAttrs: false,
  props: {
    href: { type: String, required: true },
    method: { type: String as PropType<Method>, default: 'get' },
    as: { type: String, default: 'a' },
  },
  setup(props, { attrs, slots }) {
    const router = useRouter();
    if (props.method.toLowerCase() === 'get') {
      return () => h(RouterLink, { ...attrs, to: spaPath(props.href) }, slots);
    }
    return () => h(props.as === 'button' ? 'button' : 'a', {
      ...attrs,
      href: props.href,
      onClick: async (event: Event) => {
        event.preventDefault();
        await ensureCsrfCookie();
        await axios.request({ url: apiPath(props.href, props.method), method: props.method, headers: { Accept: 'application/json' } });
        if (props.href.includes('logout')) {
          const auth = useAuthStore();
          auth.user = null;
          auth.loaded = true;
        }
        await router.push('/');
      },
    }, slots.default?.());
  },
});

export const Head = defineComponent({
  name: 'DocumentHead',
  props: { title: String },
  setup(props) { watchEffect(() => { if (props.title) document.title = props.title; }); return () => null; },
});

export function usePage() {
  const route = useRoute();
  pageContext.url = route.fullPath;
  return pageContext;
}

type FormOptions = { preserveScroll?: boolean; onSuccess?: () => void; onError?: (errors: Record<string, string>) => void; onFinish?: () => void };

export function useForm<T extends Record<string, unknown>>(initial: T) {
  const form = reactive({
    ...initial,
    errors: {} as Record<string, string>,
    processing: false,
    recentlySuccessful: false,
    reset: (...fields: string[]) => {
      const targets = fields.length ? fields : Object.keys(initial);
      targets.forEach(field => { (form as Record<string, unknown>)[field] = initial[field]; });
    },
    clearErrors: () => { form.errors = {}; },
    submit: async (method: Method, url: string, options: FormOptions = {}) => {
      form.processing = true;
      form.errors = {};
      const data = Object.fromEntries(Object.keys(initial).map(key => [key, (form as Record<string, unknown>)[key]]));
      try {
        if (method !== 'get') await ensureCsrfCookie();
        await axios.request({ method, url: apiPath(url, method), data, headers: { Accept: 'application/json' } });
        form.recentlySuccessful = true;
        options.onSuccess?.();
        setTimeout(() => { form.recentlySuccessful = false; }, 2000);
      } catch (reason) {
        if (axios.isAxiosError(reason) && reason.response?.status === 422) {
          form.errors = Object.fromEntries(Object.entries(reason.response.data.errors ?? {}).map(([key, value]) => [key, Array.isArray(value) ? String(value[0]) : String(value)]));
          options.onError?.(form.errors);
        } else { throw reason; }
      } finally { form.processing = false; options.onFinish?.(); }
    },
    get: (url: string, options?: FormOptions) => form.submit('get', url, options),
    post: (url: string, options?: FormOptions) => form.submit('post', url, options),
    put: (url: string, options?: FormOptions) => form.submit('put', url, options),
    patch: (url: string, options?: FormOptions) => form.submit('patch', url, options),
    delete: (url: string, options?: FormOptions) => form.submit('delete', url, options),
  });
  return form;
}

export const router = {
  visit: async (href: string) => { window.location.assign(spaPath(href)); },
};
