<script setup>
import StaffLayout from '@/Layouts/StaffLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    user: Object,
});
</script>

<template>
    <Head title="Staff Dashboard" />

    <StaffLayout>
        <div class="space-y-6">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-zinc-800">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                        Welcome, {{ user?.name }}!
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        You are logged in as <span class="font-semibold text-blue-600 dark:text-blue-400">Staff</span>.
                    </p>
                </div>
            </div>

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-zinc-800">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        User Dashboard
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        View the user panel.
                    </p>
                    <Link
                        :href="route('dashboard.user')"
                        class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400"
                    >
                        Go to User Dashboard
                        <svg class="ms-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                </div>
            </div>
        </div>
    </StaffLayout>
</template>
