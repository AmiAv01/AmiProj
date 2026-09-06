<script setup>
import { computed } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link, useForm } from '@/spa/bridge';
import { ref } from 'vue';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});
const sent = ref(false);

const submit = () => {
    form.post('/email/verification-notification', { onSuccess: () => { sent.value = true; } });
};

const verificationLinkSent = computed(
    () => props.status === "verification-link-sent" || sent.value
);
</script>

<template>
    <GuestLayout>
        <Head title="Подтверждение электронной почты" />

        <div class="mb-4 text-sm text-gray-600">
            Спасибо за регистрацию! Чтобы продолжить, перейдите по ссылке из письма,
            которое мы отправили на вашу электронную почту. Если письмо не пришло,
            запросите его повторно.
        </div>

        <div
            class="mb-4 font-medium text-sm text-green-600"
            v-if="verificationLinkSent"
        >
            Новая ссылка для подтверждения отправлена на адрес, указанный при регистрации.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Отправить письмо повторно
                </PrimaryButton>

                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    class="underline text-sm text-green-700 hover:text-green-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600"
                    >Выйти</Link
                >
            </div>
        </form>
    </GuestLayout>
</template>
