<script setup lang="ts">
import axios from 'axios';
import { computed, defineAsyncComponent, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { api } from '@/api/client';
import type { ApiEnvelope, PageData } from '@/api/types';
import { setPageProps } from '@/spa/bridge';

const route = useRoute();
const loading = ref(true);
const error = ref<string | null>(null);
const props = ref<PageData>({});
const pages = import.meta.glob('../../Pages/**/*.vue');

const componentName = ref(String(route.meta.page));
const page = computed(() => componentName.value);
const view = computed(() => {
  const loader = pages[`../../Pages/${page.value}.vue`];
  if (!loader) throw new Error(`Unknown page component: ${page.value}`);
  return defineAsyncComponent(loader as () => Promise<{ default: object }>);
});

function endpoint(): string {
  let value = String(route.meta.endpoint);
  for (const [key, parameter] of Object.entries(route.params)) {
    value = value.replace(`:${key}?`, String(parameter ?? '')).replace(`:${key}`, String(parameter));
  }
  return value.replace(/\/$/, '');
}

let requestSequence = 0;

async function load(signal: AbortSignal): Promise<void> {
  const requestId = ++requestSequence;
  loading.value = true;
  error.value = null;
  try {
    const response = await api.get<ApiEnvelope<PageData>>(endpoint(), { params: route.query, signal });
    if (requestId !== requestSequence) return;
    props.value = response.data.data;
    componentName.value = typeof props.value._component === 'string' ? props.value._component : String(route.meta.page);
    delete props.value._component;
    setPageProps(props.value);
  } catch (reason) {
    if (axios.isCancel(reason) || requestId !== requestSequence) return;
    error.value = axios.isAxiosError<{ message?: string }>(reason)
      ? reason.response?.data.message ?? 'Unable to load this page.'
      : reason instanceof Error ? reason.message : 'Unable to load this page.';
  } finally {
    if (requestId === requestSequence) loading.value = false;
  }
}

watch(() => route.fullPath, (_path, _previousPath, onCleanup) => {
  const controller = new AbortController();
  onCleanup(() => controller.abort());
  void load(controller.signal);
}, { immediate: true });
</script>

<template>
  <div
    v-if="loading"
    class="flex min-h-screen items-center justify-center"
    role="status"
    aria-live="polite"
  >
    <svg
      class="h-12 w-12 animate-spin text-green-700"
      aria-hidden="true"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle
        class="opacity-20"
        cx="12"
        cy="12"
        r="10"
        stroke="currentColor"
        stroke-width="4"
      />
      <path
        class="opacity-90"
        fill="currentColor"
        d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"
      />
    </svg>
    <span class="sr-only">Загрузка страницы…</span>
  </div>
  <div v-else-if="error" class="p-8 text-center text-red-700">{{ error }}</div>
  <component :is="view" v-else v-bind="props" />
</template>
