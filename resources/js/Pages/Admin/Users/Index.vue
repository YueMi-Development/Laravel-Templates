<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    users: Object,
    filters: Object,
    roles: Array,
});

const page = usePage();

const form = useForm({
    search: props.filters?.search || '',
    role: props.filters?.role || '',
});

const submitSearch = () => {
    form.get(route('admin.users.index'), {
        preserveScroll: true,
        replace: true,
    });
};

const clearFilters = () => {
    form.search = '';
    form.role = '';
    form.get(route('admin.users.index'), {
        preserveScroll: true,
        replace: true,
    });
};

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
    const found = props.roles?.find(r => r.value === role);
    return found ? found.label : role;
};

const successMessage = computed(() => page.props.flash?.success);
</script>

<template>
    <Head title="User Management" />

    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        User Management
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">
                        Manage all registered users and their roles.
                    </p>
                </div>
                <Link
                    :href="route('admin.users.create')"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 sm:py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-offset-zinc-900 w-full sm:w-auto"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add User
                </Link>
            </div>

            <!-- Success Message -->
            <div
                v-if="successMessage"
                class="rounded-lg bg-emerald-50 p-4 text-sm text-emerald-700 border border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20"
            >
                {{ successMessage }}
            </div>

            <!-- Filters -->
            <div class="rounded-xl border border-zinc-200/80 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <form @submit.prevent="submitSearch" class="grid grid-cols-1 gap-3 sm:grid-cols-12 sm:items-end">
                    <div class="sm:col-span-6 lg:col-span-5">
                        <label class="mb-1 block text-xs sm:text-sm font-medium text-zinc-700 dark:text-zinc-300">Search</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                v-model="form.search"
                                type="text"
                                placeholder="Search by name or email..."
                                class="w-full rounded-lg border-zinc-300 py-2 pl-9 pr-3 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500"
                            />
                        </div>
                    </div>
                    <div class="sm:col-span-3 lg:col-span-4">
                        <label class="mb-1 block text-xs sm:text-sm font-medium text-zinc-700 dark:text-zinc-300">Role</label>
                        <select
                            v-model="form.role"
                            class="w-full rounded-lg border-zinc-300 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                        >
                            <option value="">All Roles</option>
                            <option v-for="role in roles" :key="role.value" :value="role.value">
                                {{ role.label }}
                            </option>
                        </select>
                    </div>
                    <div class="flex gap-2 sm:col-span-3 lg:col-span-3">
                        <button
                            type="submit"
                            class="flex-1 inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-600 dark:hover:bg-blue-500 dark:focus:ring-offset-zinc-900"
                        >
                            Filter
                        </button>
                        <button
                            type="button"
                            @click="clearFilters"
                            class="flex-1 inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
                        >
                            Clear
                        </button>
                    </div>
                </form>
            </div>

            <!-- Users List (Card View on Mobile, Table on Desktop) -->
            <div class="overflow-hidden rounded-xl border border-zinc-200/80 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <!-- Mobile Card View (md:hidden) -->
                <div class="block md:hidden divide-y divide-zinc-200 dark:divide-zinc-800">
                    <div v-if="users.data.length === 0" class="px-4 py-10 text-center text-sm text-zinc-500 dark:text-zinc-400">
                        No users found.
                    </div>
                    <div
                        v-for="user in users.data"
                        :key="'mobile-' + user.id"
                        class="p-4 space-y-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-zinc-100 text-sm font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">
                                    {{ user.name.charAt(0).toUpperCase() }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-medium text-zinc-900 dark:text-white truncate">
                                        {{ user.name }}
                                    </div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">
                                        {{ user.email }}
                                    </div>
                                </div>
                            </div>
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium shrink-0"
                                :class="roleBadgeClass(user.role)"
                            >
                                {{ getRoleLabel(user.role) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between pt-1 text-xs text-zinc-500 dark:text-zinc-400 border-t border-zinc-100 dark:border-zinc-800/60">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Joined {{ new Date(user.created_at).toLocaleDateString() }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <Link
                                    :href="route('admin.users.show', user.id)"
                                    class="rounded-lg p-2 text-zinc-500 hover:text-zinc-700 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800"
                                    title="View"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </Link>
                                <Link
                                    :href="route('admin.users.edit', user.id)"
                                    class="rounded-lg p-2 text-zinc-500 hover:text-zinc-700 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800"
                                    title="Edit"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </Link>
                                <Link
                                    v-if="user.id !== $page.props.auth.user?.id"
                                    :href="route('admin.users.destroy', user.id)"
                                    method="delete"
                                    as="button"
                                    class="rounded-lg p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-500/10"
                                    title="Delete"
                                    preserve-scroll
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Desktop Table View (hidden md:block) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Joined</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-800 dark:bg-zinc-900">
                            <tr v-if="users.data.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                    No users found.
                                </td>
                            </tr>
                            <tr
                                v-for="user in users.data"
                                :key="user.id"
                                class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50"
                            >
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-zinc-100 text-sm font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">
                                            {{ user.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <span class="font-medium text-zinc-900 dark:text-white">{{ user.name }}</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-600 dark:text-zinc-400">{{ user.email }}</td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                        :class="roleBadgeClass(user.role)"
                                    >
                                        {{ getRoleLabel(user.role) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ new Date(user.created_at).toLocaleDateString() }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <Link
                                            :href="route('admin.users.show', user.id)"
                                            class="rounded-md p-1.5 text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 dark:hover:text-zinc-200 dark:hover:bg-zinc-800"
                                            title="View"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                        <Link
                                            :href="route('admin.users.edit', user.id)"
                                            class="rounded-md p-1.5 text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 dark:hover:text-zinc-200 dark:hover:bg-zinc-800"
                                            title="Edit"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </Link>
                                        <Link
                                            v-if="user.id !== $page.props.auth.user?.id"
                                            :href="route('admin.users.destroy', user.id)"
                                            method="delete"
                                            as="button"
                                            class="rounded-md p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:text-red-400 dark:hover:bg-red-500/10"
                                            title="Delete"
                                            preserve-scroll
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="users.data.length > 0" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-t border-zinc-200 bg-white px-4 py-3 sm:px-6 sm:py-4 dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400 text-center sm:text-left">
                        Showing {{ users.from || 0 }} to {{ users.to || 0 }} of {{ users.total || 0 }} results
                    </div>
                    <div class="flex flex-wrap justify-center sm:justify-end gap-1">
                        <Link
                            v-for="link in users.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            v-html="link.label"
                            class="rounded-md px-2.5 py-1.5 sm:px-3 text-xs sm:text-sm font-medium transition-colors"
                            :class="{
                                'bg-blue-600 text-white': link.active,
                                'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800': !link.active && link.url,
                                'text-zinc-300 dark:text-zinc-600 pointer-events-none': !link.url,
                            }"
                            :preserve-scroll="true"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
