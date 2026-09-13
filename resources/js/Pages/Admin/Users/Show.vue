<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
});

const roleBadgeClass = (role) => {
    switch (role) {
        case 'admin':
            return 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400';
        case 'staff':
            return 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400';
        default:
            return 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400';
    }
};

const getRoleLabel = (role) => {
    switch (role) {
        case 'admin': return 'Admin';
        case 'staff': return 'Staff';
        default: return 'User';
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head title="User Details" />

    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <Link
                    :href="route('admin.users.index')"
                    class="rounded-lg p-2 text-zinc-400 hover:bg-zinc-100 hover:text-zinc-600 dark:hover:bg-zinc-800 dark:hover:text-zinc-200"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        User Details
                    </h1>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        View detailed information about this user.
                    </p>
                </div>
            </div>

            <!-- User Info Card -->
            <div class="overflow-hidden rounded-xl border border-zinc-200/80 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="border-b border-zinc-200 bg-zinc-50 px-4 py-4 sm:px-6 dark:border-zinc-800 dark:bg-zinc-800/50">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="flex h-12 w-12 sm:h-16 sm:w-16 items-center justify-center rounded-full bg-zinc-100 text-lg sm:text-xl font-bold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 shrink-0">
                            {{ user.name.charAt(0).toUpperCase() }}
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-lg sm:text-xl font-semibold text-zinc-900 dark:text-white truncate">
                                {{ user.name }}
                            </h2>
                            <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 truncate">{{ user.email }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6 md:p-8">
                    <dl class="grid gap-4 sm:gap-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-zinc-500 dark:text-zinc-400">Role</dt>
                            <dd class="mt-1">
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 sm:px-3 sm:py-1 text-xs sm:text-sm font-medium"
                                    :class="roleBadgeClass(user.role)"
                                >
                                    {{ getRoleLabel(user.role) }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-zinc-500 dark:text-zinc-400">Email</dt>
                            <dd class="mt-1 text-xs sm:text-sm text-zinc-900 dark:text-white break-all">{{ user.email }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-zinc-500 dark:text-zinc-400">Email Verified</dt>
                            <dd class="mt-1">
                                <span v-if="user.email_verified_at" class="inline-flex items-center gap-1 text-xs sm:text-sm text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Verified
                                </span>
                                <span v-else class="inline-flex items-center gap-1 text-xs sm:text-sm text-amber-600 dark:text-amber-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Not Verified
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-zinc-500 dark:text-zinc-400">Member Since</dt>
                            <dd class="mt-1 text-xs sm:text-sm text-zinc-900 dark:text-white">{{ formatDate(user.created_at) }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-zinc-500 dark:text-zinc-400">Last Updated</dt>
                            <dd class="mt-1 text-xs sm:text-sm text-zinc-900 dark:text-white">{{ formatDate(user.updated_at) }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs sm:text-sm font-medium text-zinc-500 dark:text-zinc-400">User ID</dt>
                            <dd class="mt-1 font-mono text-xs text-zinc-500 dark:text-zinc-400">#{{ user.id }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row gap-3 border-t border-zinc-200 pt-6 dark:border-zinc-800">
                        <Link
                            :href="route('admin.users.edit', user.id)"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 sm:py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-offset-zinc-900"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit User
                        </Link>
                        <Link
                            :href="route('admin.users.index')"
                            class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2.5 sm:py-2 text-sm font-semibold text-zinc-700 transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
                        >
                            Back to List
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
