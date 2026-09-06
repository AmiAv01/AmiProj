<script setup lang="ts">
import { ref } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head } from '@/spa/bridge';
import { useAuthStore } from '@/spa/stores/auth';
import { useApiForm } from '@/spa/composables/useApiForm';

const auth = useAuthStore();
const complete = ref(false);
const form = useApiForm({ name: '', email: '', phoneNumber: '', password: '', password_confirmation: '' });

async function register() {
  if (await form.submit(() => auth.register(form.values))) complete.value = true;
}
</script>

<template>
  <GuestLayout>
    <Head title="Регистрация" />
    <h1 class="mb-6 text-center text-2xl font-semibold text-gray-800">Регистрация</h1>

    <div v-if="complete" class="rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-800">
      Заявка на регистрацию отправлена. Войти можно будет после одобрения учётной записи администратором.
      <RouterLink class="mt-3 block font-medium underline" to="/login">Вернуться ко входу</RouterLink>
    </div>

    <form v-else @submit.prevent="register">
      <div>
        <InputLabel for="name" value="Имя" />
        <TextInput id="name" v-model="form.values.name" class="mt-1 block w-full" required autofocus autocomplete="name" />
        <InputError class="mt-2" :message="form.errors.value.name?.[0]" />
      </div>

      <div class="mt-4">
        <InputLabel for="email" value="Электронная почта" />
        <TextInput id="email" v-model="form.values.email" type="email" class="mt-1 block w-full" required autocomplete="username" />
        <InputError class="mt-2" :message="form.errors.value.email?.[0]" />
      </div>

      <div class="mt-4">
        <InputLabel for="phoneNumber" value="Номер телефона" />
        <TextInput id="phoneNumber" v-model="form.values.phoneNumber" class="mt-1 block w-full" required autocomplete="tel" />
        <InputError class="mt-2" :message="form.errors.value.phoneNumber?.[0]" />
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="Пароль" />
        <TextInput id="password" v-model="form.values.password" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
        <InputError class="mt-2" :message="form.errors.value.password?.[0]" />
      </div>

      <div class="mt-4">
        <InputLabel for="password_confirmation" value="Подтвердите пароль" />
        <TextInput id="password_confirmation" v-model="form.values.password_confirmation" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
        <InputError class="mt-2" :message="form.errors.value.password_confirmation?.[0]" />
      </div>

      <InputError v-if="form.error.value" class="mt-4" :message="form.error.value" role="alert" />

      <div class="mt-6 flex items-center justify-end gap-4">
        <RouterLink class="text-sm text-green-700 underline decoration-green-300 underline-offset-2 hover:text-green-600" to="/login">
          Уже зарегистрированы?
        </RouterLink>
        <PrimaryButton :class="{ 'opacity-50': form.processing.value }" :disabled="form.processing.value">
          {{ form.processing.value ? 'Отправляем…' : 'Зарегистрироваться' }}
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
