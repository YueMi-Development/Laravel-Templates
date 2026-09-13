<script setup>
import StaffLayout from '@/Layouts/StaffLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    scheduler: {
        type: Object,
        default: () => ({
            name: 'Laravel Scheduler',
            status: 'STOPPED',
            is_running: false,
            last_heartbeat: null,
            last_heartbeat_ago: null,
            message: 'The scheduler is not heartbeating.',
        }),
    },
    queue: {
        type: Object,
        default: () => ({
            name: 'Queue Worker',
            status: 'STOPPED',
            is_running: false,
            last_heartbeat: null,
            last_heartbeat_ago: null,
            message: 'Queue workers are not responding or inactive.',
        }),
    },
});

const isRefreshing = ref(false);

const refreshStatus = () => {
    isRefreshing.value = true;
    router.reload({
        only: ['scheduler', 'queue'],
        onFinish: () => {
            isRefreshing.value = false;
        },
    });
};
</script>

<template>
    <Head title="System Monitoring" />

    <StaffLayout>
        <div class="space-y-4 sm:space-y-6">
            <!-- Header -->
            <div class="flex flex-col gap-3.5 sm:gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        System Monitoring
                    </h1>
                    <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">
                        Check background scheduler and queue worker health status.
                    </p>
                </div>
                <div class="w-full sm:w-auto">
                    <button
                        @click="refreshStatus"
                        :disabled="isRefreshing"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg border border-zinc-300 bg-white px-3.5 py-2.5 sm:py-2 text-xs sm:text-sm font-medium text-zinc-700 shadow-sm transition-colors hover:bg-zinc-50 hover:text-zinc-900 disabled:opacity-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700 dark:hover:text-white"
                    >
                        <svg class="h-4 w-4 shrink-0" :class="{ 'animate-spin': isRefreshing }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>

            <!-- Monitoring Cards Grid -->
            <div class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-2">
                <!-- Laravel Scheduler Card -->
                <div class="flex flex-col justify-between overflow-hidden rounded-xl border border-zinc-200 bg-white p-4 sm:p-6 shadow-sm dark:border-zinc-700/60 dark:bg-zinc-800">
                    <div class="space-y-4 sm:space-y-6">
                        <!-- Card Header -->
                        <div class="flex items-start justify-between gap-3 sm:gap-4">
                            <div class="flex items-center gap-3 sm:gap-3.5 min-w-0">
                                <div class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-xl bg-zinc-100 text-zinc-700 border border-zinc-200/80 dark:border-zinc-700 dark:bg-zinc-700/60 dark:text-zinc-200">
                                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h2 class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white truncate sm:whitespace-normal">
                                        Laravel Scheduler
                                    </h2>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate sm:whitespace-normal">
                                        Checks if the background task scheduler is active
                                    </p>
                                </div>
                            </div>

                            <span
                                v-if="scheduler.is_running"
                                class="shrink-0 inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 sm:px-3 sm:py-1 text-[11px] sm:text-xs font-bold tracking-wide text-emerald-700 border border-emerald-200 dark:bg-emerald-500/15 dark:text-emerald-400 dark:border-emerald-500/30"
                            >
                                {{ scheduler.status }}
                            </span>
                            <span
                                v-else
                                class="shrink-0 inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 sm:px-3 sm:py-1 text-[11px] sm:text-xs font-bold tracking-wide text-red-700 border border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/30"
                            >
                                {{ scheduler.status }}
                            </span>
                        </div>

                        <!-- Status Message -->
                        <div class="flex items-start gap-2.5">
                            <svg
                                v-if="scheduler.is_running"
                                class="mt-0.5 h-4 w-4 sm:h-5 sm:w-5 shrink-0 text-emerald-600 dark:text-emerald-400"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                            <svg
                                v-else
                                class="mt-0.5 h-4 w-4 sm:h-5 sm:w-5 shrink-0 text-amber-500 dark:text-amber-400"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>

                            <div class="space-y-0.5 min-w-0">
                                <p
                                    class="text-xs sm:text-sm font-medium leading-snug"
                                    :class="scheduler.is_running ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-700 dark:text-amber-400'"
                                >
                                    {{ scheduler.message }}
                                </p>
                                <p v-if="scheduler.last_heartbeat_ago" class="text-[11px] sm:text-xs text-zinc-500 dark:text-zinc-400">
                                    Last heartbeat: {{ scheduler.last_heartbeat_ago }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Did you know? -->
                    <div class="mt-4 sm:mt-6 rounded-xl border border-zinc-200/80 bg-zinc-50/80 p-3.5 sm:p-4 dark:border-zinc-700/60 dark:bg-zinc-900/50">
                        <div class="flex items-center gap-2 text-zinc-900 dark:text-zinc-100 font-semibold text-xs sm:text-sm">
                            <svg class="h-4 w-4 shrink-0 text-zinc-600 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            <span>Did you know?</span>
                        </div>
                        <p class="mt-1.5 sm:mt-2 text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">
                            Laravel requires a single cron entry that runs every minute. That single entry then allows Laravel to manage all other scheduled tasks via your code.
                        </p>
                    </div>
                </div>

                <!-- Queue Worker Card -->
                <div class="flex flex-col justify-between overflow-hidden rounded-xl border border-zinc-200 bg-white p-4 sm:p-6 shadow-sm dark:border-zinc-700/60 dark:bg-zinc-800">
                    <div class="space-y-4 sm:space-y-6">
                        <!-- Card Header -->
                        <div class="flex items-start justify-between gap-3 sm:gap-4">
                            <div class="flex items-center gap-3 sm:gap-3.5 min-w-0">
                                <div class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-xl bg-zinc-100 text-zinc-700 border border-zinc-200/80 dark:border-zinc-700 dark:bg-zinc-700/60 dark:text-zinc-200">
                                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h2 class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white truncate sm:whitespace-normal">
                                        Queue Worker
                                    </h2>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate sm:whitespace-normal">
                                        Monitors background queue job processing
                                    </p>
                                </div>
                            </div>

                            <span
                                v-if="queue.is_running"
                                class="shrink-0 inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 sm:px-3 sm:py-1 text-[11px] sm:text-xs font-bold tracking-wide text-emerald-700 border border-emerald-200 dark:bg-emerald-500/15 dark:text-emerald-400 dark:border-emerald-500/30"
                            >
                                {{ queue.status }}
                            </span>
                            <span
                                v-else
                                class="shrink-0 inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 sm:px-3 sm:py-1 text-[11px] sm:text-xs font-bold tracking-wide text-red-700 border border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/30"
                            >
                                {{ queue.status }}
                            </span>
                        </div>

                        <!-- Status Message -->
                        <div class="flex items-start gap-2.5">
                            <svg
                                v-if="queue.is_running"
                                class="mt-0.5 h-4 w-4 sm:h-5 sm:w-5 shrink-0 text-emerald-600 dark:text-emerald-400"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                            <svg
                                v-else
                                class="mt-0.5 h-4 w-4 sm:h-5 sm:w-5 shrink-0 text-amber-500 dark:text-amber-400"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>

                            <div class="space-y-0.5 min-w-0">
                                <p
                                    class="text-xs sm:text-sm font-medium leading-snug"
                                    :class="queue.is_running ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-700 dark:text-amber-400'"
                                >
                                    {{ queue.message }}
                                </p>
                                <p v-if="queue.last_heartbeat_ago" class="text-[11px] sm:text-xs text-zinc-500 dark:text-zinc-400">
                                    Last heartbeat: {{ queue.last_heartbeat_ago }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Did you know? -->
                    <div class="mt-4 sm:mt-6 rounded-xl border border-zinc-200/80 bg-zinc-50/80 p-3.5 sm:p-4 dark:border-zinc-700/60 dark:bg-zinc-900/50">
                        <div class="flex items-center gap-2 text-zinc-900 dark:text-zinc-100 font-semibold text-xs sm:text-sm">
                            <svg class="h-4 w-4 shrink-0 text-zinc-600 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            <span>Did you know?</span>
                        </div>
                        <p class="mt-1.5 sm:mt-2 text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">
                            Queue workers run asynchronously to handle time-consuming tasks like sending webhook callbacks, sending notifications, and syncing payment transactions.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </StaffLayout>
</template>
