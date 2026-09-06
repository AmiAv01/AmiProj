<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head } from '@/spa/bridge';
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
  <GuestLayout>
    <Head :title="admin ? 'Вход для администратора' : 'Вход'" />

    <h1 class="mb-6 text-center text-2xl font-semibold text-gray-800">
      {{ admin ? 'Вход для администратора' : 'Вход' }}
    </h1>

    <form @submit.prevent="login">
      <div>
        <InputLabel for="email" value="Электронная почта" />
        <TextInput id="email" v-model="form.values.email" type="email" class="mt-1 block w-full" required autofocus autocomplete="username" />
        <InputError class="mt-2" :message="form.errors.value.email?.[0]" />
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="Пароль" />
        <TextInput id="password" v-model="form.values.password" type="password" class="mt-1 block w-full" required autocomplete="current-password" />
        <InputError class="mt-2" :message="form.errors.value.password?.[0]" />
      </div>

      <InputError v-if="form.error.value" class="mt-4" :message="form.error.value" role="alert" />

      <div class="mt-4">
        <label class="flex items-center">
          <Checkbox v-model:checked="form.values.remember" name="remember" />
          <span class="ms-2 text-sm text-gray-600">Запомнить меня</span>
        </label>
      </div>

      <div class="mt-6 flex flex-wrap items-center justify-end gap-4">
        <RouterLink v-if="!admin" class="auth-link" to="/forgot-password">Забыли пароль?</RouterLink>
        <RouterLink v-if="!admin" class="auth-link" to="/register">Регистрация</RouterLink>
        <PrimaryButton :class="{ 'opacity-50': form.processing.value }" :disabled="form.processing.value">
          {{ form.processing.value ? 'Входим…' : 'Войти' }}
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>

<style scoped>
.auth-link {
  @apply text-sm text-green-700 underline decoration-green-300 underline-offset-2 transition hover:text-green-600 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2;
}
</style>
