/// <reference types="vite/client" />

declare module '*.vue' {
  import type { DefineComponent } from 'vue';
  const component: DefineComponent<Record<string, unknown>, Record<string, unknown>, unknown>;
  export default component;
}

declare const Ziggy: Record<string, unknown>;

interface Window {
  axios: typeof import('axios').default;
}
declare module '../../vendor/tightenco/ziggy/dist/vue.m.js' {
  import type { Plugin } from 'vue';
  export const ZiggyVue: Plugin;
}
