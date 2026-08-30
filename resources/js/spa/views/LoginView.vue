<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/spa/stores/auth';
import { useApiForm } from '@/spa/composables/useApiForm';

const router = useRouter();
const route = useRoute();
const props = withDefaults(defineProps<{ admin?: boolean }>(), { admin: false });
const auth = useAuthStore();
const form = useApiForm({ email: '', password: '', remember: false });
async function login() {
  const action = props.admin ? auth.adminLogin(form.values) : auth.login(form.values);
  if (await form.submit(() => action)) {
    const fallback = props.admin ? '/admin/resource/details' : '/';
    await router.push(typeof route.query.redirect === 'string' ? route.query.redirect : fallback);
  }
}
</script>
<template>
  <main class="mx-auto max-w-md p-8">
    <h1 class="mb-6 text-2xl font-semibold">{{ admin ? 'Admin login' : 'Login' }}</h1>
    <form class="space-y-4" @submit.prevent="login">
      <label class="block">Email<input v-model="form.values.email" type="email" class="mt-1 w-full rounded border-gray-300" /></label>
      <p v-for="message in form.errors.value.email" :key="message" class="text-sm text-red-700">{{ message }}</p>
      <label class="block">Password<input v-model="form.values.password" type="password" class="mt-1 w-full rounded border-gray-300" /></label>
      <p v-if="form.error.value" class="text-sm text-red-700" role="alert">{{ form.error.value }}</p>
      <label class="flex gap-2"><input v-model="form.values.remember" type="checkbox" /> Remember me</label>
      <button class="rounded bg-blue-700 px-4 py-2 text-white" :disabled="form.processing.value">Login</button>
      <RouterLink v-if="!admin" class="ml-4 text-blue-700" to="/register">Register</RouterLink>
    </form>
  </main>
</template>
