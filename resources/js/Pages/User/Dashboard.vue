<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    user: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <UserLayout>
        <div class="space-y-6">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-zinc-800">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                        Welcome, {{ user?.name }}!
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        You are logged in as <span class="font-semibold text-gray-600 dark:text-gray-400">User</span>.
                    </p>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
