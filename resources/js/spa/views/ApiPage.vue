<script setup lang="ts">
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

async function load(): Promise<void> {
  loading.value = true;
  error.value = null;
  try {
    const response = await api.get<ApiEnvelope<PageData>>(endpoint(), { params: route.query });
    props.value = response.data.data;
    componentName.value = typeof props.value._component === 'string' ? props.value._component : String(route.meta.page);
    delete props.value._component;
    setPageProps(props.value);
  } catch (reason) {
    error.value = reason instanceof Error ? reason.message : 'Unable to load this page.';
  } finally {
    loading.value = false;
  }
}

watch(() => route.fullPath, load, { immediate: true });
</script>

<template>
  <div v-if="loading" class="p-8 text-center">Loading…</div>
  <div v-else-if="error" class="p-8 text-center text-red-700">{{ error }}</div>
  <component :is="view" v-else v-bind="props" />
</template>
