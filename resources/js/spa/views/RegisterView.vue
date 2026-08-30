<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '@/spa/stores/auth';
import { useApiForm } from '@/spa/composables/useApiForm';
const auth = useAuthStore();
const complete = ref(false);
const form = useApiForm({ name: '', email: '', phoneNumber: '', password: '', password_confirmation: '' });
async function register() { if (await form.submit(() => auth.register(form.values))) complete.value = true; }
</script>
<template>
  <main class="mx-auto max-w-md p-8">
    <h1 class="mb-6 text-2xl font-semibold">Register</h1>
    <p v-if="complete" class="rounded bg-green-100 p-4">Registration submitted. An administrator must approve the account before login.</p>
    <form v-else class="space-y-4" @submit.prevent="register">
      <label class="block">Name<input v-model="form.values.name" class="mt-1 w-full rounded border-gray-300" /></label>
      <label class="block">Email<input v-model="form.values.email" type="email" class="mt-1 w-full rounded border-gray-300" /></label>
      <label class="block">Phone<input v-model="form.values.phoneNumber" class="mt-1 w-full rounded border-gray-300" /></label>
      <label class="block">Password<input v-model="form.values.password" type="password" class="mt-1 w-full rounded border-gray-300" /></label>
      <label class="block">Confirm password<input v-model="form.values.password_confirmation" type="password" class="mt-1 w-full rounded border-gray-300" /></label>
      <template v-for="messages in form.errors.value" :key="messages[0]">
        <p v-for="message in messages" :key="message" class="text-sm text-red-700">{{ message }}</p>
      </template>
      <p v-if="form.error.value" class="text-sm text-red-700" role="alert">{{ form.error.value }}</p>
      <button class="rounded bg-blue-700 px-4 py-2 text-white" :disabled="form.processing.value">Register</button>
    </form>
  </main>
</template>
