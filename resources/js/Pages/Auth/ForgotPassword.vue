<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@/spa/bridge';
import { ref } from 'vue';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});
const sent = ref(false);

const submit = () => {
    form.post('/forgot-password', { onSuccess: () => { sent.value = true; } });
};
</script>

<template>
    <GuestLayout>
        <Head title="Восстановление пароля" />

        <div class="mb-4 text-sm text-gray-600">
            Забыли пароль? Укажите адрес электронной почты, и мы отправим ссылку для создания нового пароля.
        </div>

        <div v-if="status || sent" class="mb-4 font-medium text-sm text-green-600">
            {{ status || 'Ссылка для восстановления пароля отправлена.' }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Электронная почта" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Отправить ссылку
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
