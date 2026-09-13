<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <UserLayout>
        <div class="space-y-6">
            <div class="border-b border-zinc-200 pb-4 dark:border-zinc-800">
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
                    Profile Settings
                </h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Manage your account details and password settings.
                </p>
            </div>

            <div class="space-y-6 max-w-4xl">
                <div class="overflow-hidden rounded-xl border border-zinc-200/80 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 sm:p-8">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="overflow-hidden rounded-xl border border-zinc-200/80 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 sm:p-8">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="overflow-hidden rounded-xl border border-zinc-200/80 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 sm:p-8">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </UserLayout>
</template>

