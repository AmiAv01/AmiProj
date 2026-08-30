import 'vue';
import type { pageContext } from '@/spa/bridge';

declare module 'vue' {
  interface ComponentCustomProperties {
    $page: typeof pageContext;
    route: (name?: string, params?: unknown) => string;
  }
}
