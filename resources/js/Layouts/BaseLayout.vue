<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ThemeSwitcher from '@/Components/ThemeSwitcher.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { censorEmail } from '@/Utils/censorship';

const props = defineProps({
    viewModes: {
        type: Array,
        default: () => [],
    },
    navItems: {
        type: Array,
        default: () => [],
    },
    config: {
        type: Object,
        default: () => ({}),
    },
    activeMode: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:activeMode', 'modeChange']);

const internalMode = ref(props.activeMode || props.config?.role || 'user');

function setMode(modeId) {
    internalMode.value = modeId;
    emit('update:activeMode', modeId);
    emit('modeChange', modeId);
}

const currentActiveMode = computed(() => props.activeMode || internalMode.value);

const modeStyles = {
    admin: {
        active: 'bg-red-600 text-white border-red-600 font-semibold shadow-sm dark:bg-red-600 dark:text-white dark:border-red-500',
        inactive: 'border-transparent text-zinc-500 hover:text-red-700 hover:bg-red-500/10 dark:text-zinc-400 dark:hover:text-red-300 dark:hover:bg-red-500/15',
    },
    staff: {
        active: 'bg-blue-600 text-white border-blue-600 font-semibold shadow-sm dark:bg-blue-600 dark:text-white dark:border-blue-500',
        inactive: 'border-transparent text-zinc-500 hover:text-blue-700 hover:bg-blue-500/10 dark:text-zinc-400 dark:hover:text-blue-300 dark:hover:bg-blue-500/15',
    },
    user: {
        active: 'bg-emerald-600 text-white border-emerald-600 font-semibold shadow-sm dark:bg-emerald-600 dark:text-white dark:border-emerald-500',
        inactive: 'border-transparent text-zinc-500 hover:text-emerald-700 hover:bg-emerald-500/10 dark:text-zinc-400 dark:hover:text-emerald-300 dark:hover:bg-emerald-500/15',
    },
};

const getModeClass = (modeId) => {
    const key = (modeId || '').toLowerCase();
    const isSelected = currentActiveMode.value === key;
    const style = modeStyles[key] || {
        active: 'bg-white text-zinc-900 border-zinc-200/80 font-semibold shadow-sm dark:bg-zinc-800 dark:text-white dark:border-zinc-700/60',
        inactive: 'border-transparent text-zinc-500 hover:text-zinc-900 hover:bg-zinc-200/50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/40',
    };
    return isSelected ? style.active : style.inactive;
};

const showingNav = ref(false);
</script>

<template>
    <div class="min-h-screen bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 antialiased">
        <!-- Mobile Topbar -->
        <div class="sticky top-0 z-40 flex h-16 items-center justify-between border-b border-zinc-200 bg-white/95 px-4 backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/95 lg:hidden">
            <div class="flex items-center gap-3">
                <slot name="logo">
                    <Link :href="route('dashboard')" class="flex items-center gap-3">
                        <ApplicationLogo class="h-8 w-auto fill-current text-zinc-900 dark:text-white" />
                        <span class="text-base font-bold tracking-tight text-zinc-900 dark:text-white">
                            {{ $page.props.appName || 'Laravel' }}
                        </span>
                    </Link>
                </slot>
            </div>
            <button
                @click="showingNav = !showingNav"
                type="button"
                class="rounded-lg p-2 text-zinc-600 hover:bg-zinc-100 focus:outline-none dark:text-zinc-400 dark:hover:bg-zinc-800"
                aria-label="Toggle navigation"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Sidebar -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-zinc-200 bg-white text-zinc-700 transition-transform duration-200 ease-in-out dark:border-zinc-800/80 dark:bg-zinc-950 dark:text-zinc-300',
                !showingNav ? '-translate-x-full lg:translate-x-0' : 'translate-x-0',
            ]"
        >
            <!-- Brand / Logo Header -->
            <div class="flex h-16 items-center justify-between border-b border-zinc-200 px-5 dark:border-zinc-800/80">
                <slot name="logo">
                    <Link :href="route('dashboard')" class="flex items-center gap-3">
                        <ApplicationLogo class="h-8 w-auto fill-current text-zinc-900 dark:text-white" />
                        <span class="text-base font-bold tracking-tight text-zinc-900 dark:text-white">
                            {{ $page.props.appName || 'Laravel' }}
                        </span>
                    </Link>
                </slot>
                <button
                    @click="showingNav = false"
                    type="button"
                    class="rounded-lg p-1.5 text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 lg:hidden dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white"
                    aria-label="Close sidebar"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Area -->
            <div class="flex-1 overflow-y-auto px-3 py-4 space-y-5">
                <!-- View Mode Switcher (Hidden if only 1 role available, switches list without page reload) -->
                <div v-if="viewModes && viewModes.length > 1">
                    <div class="mb-1.5 px-1 text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        View Mode
                    </div>
                    <div class="flex items-center rounded-xl bg-zinc-100 p-1 border border-zinc-200/90 shadow-inner gap-1 dark:bg-zinc-900/80 dark:border-zinc-800/80">
                        <button
                            v-for="mode in viewModes"
                            :key="mode.id || mode.label"
                            type="button"
                            @click="setMode(mode.id || mode.label.toLowerCase())"
                            :class="[
                                'flex-1 text-center py-1.5 px-2 text-xs font-medium rounded-lg border transition-colors duration-75 truncate',
                                getModeClass(mode.id || mode.label),
                            ]"
                            :title="mode.label"
                        >
                            {{ mode.label }}
                        </button>
                    </div>
                </div>

                <!-- Navigation Links Below View Mode Switcher -->
                <div v-if="navItems && navItems.length > 0">
                    <div class="mb-1.5 px-1 text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        Navigation
                    </div>
                    <nav class="space-y-1">
                        <Link
                            v-for="item in navItems"
                            :key="item.href"
                            :href="item.href"
                            :class="[
                                'flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium border transition-colors duration-75',
                                item.active
                                    ? 'bg-zinc-100 text-zinc-900 font-semibold border-zinc-200/80 shadow-sm dark:bg-zinc-800/90 dark:text-white dark:border-zinc-700/50'
                                    : 'border-transparent text-zinc-600 hover:bg-zinc-100/80 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/50 dark:hover:text-zinc-200',
                            ]"
                        >
                            <svg
                                class="h-4 w-4 shrink-0 text-zinc-500 dark:text-zinc-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                />
                            </svg>
                            <span>{{ item.label }}</span>
                        </Link>
                    </nav>
                </div>

                <!-- Slot for additional custom navigation if passed -->
                <slot name="nav" />
            </div>

            <!-- Footer: Theme Switcher & Profile Dropdown -->
            <div class="border-t border-zinc-200 p-3 space-y-2.5 dark:border-zinc-800/80">
                <!-- Theme Switcher (Same Row) -->
                <div>
                    <ThemeSwitcher />
                </div>

                <!-- Profile Dropdown -->
                <Dropdown align="top" width="full" content-classes="p-1.5 space-y-1">
                    <template #trigger>
                        <button
                            type="button"
                            class="group flex w-full items-center gap-3 rounded-xl p-2 text-start transition duration-150 hover:bg-zinc-100 border border-transparent hover:border-zinc-200/80 focus:outline-none dark:hover:bg-zinc-900/80 dark:hover:border-zinc-800"
                        >
                            <div
                                :class="[
                                    'flex h-9 w-9 shrink-0 items-center justify-center rounded-xl font-bold text-sm shadow-sm',
                                    config.avatarBg || 'bg-zinc-100 text-zinc-800 border border-zinc-200 dark:bg-zinc-800 dark:text-zinc-200 dark:border-zinc-700',
                                ]"
                            >
                                {{ $page.props.auth.user?.name?.charAt(0).toUpperCase() }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="truncate text-xs sm:text-sm font-semibold text-zinc-900 group-hover:text-black dark:text-zinc-100 dark:group-hover:text-white">
                                    {{ $page.props.auth.user?.name }}
                                </p>
                                <p class="truncate text-[11px] text-zinc-500 dark:text-zinc-400">
                                    {{ censorEmail($page.props.auth.user?.email) }}
                                </p>
                            </div>
                            <svg
                                class="h-4 w-4 shrink-0 text-zinc-400 transition-transform duration-150 group-hover:text-zinc-600 dark:group-hover:text-zinc-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                            </svg>
                        </button>
                    </template>

                    <template #content>
                        <!-- Dropdown User Header -->
                        <div class="px-3 py-2 border-b border-zinc-100 dark:border-zinc-800">
                            <div class="flex items-center justify-between gap-2">
                                <p class="truncate text-xs font-semibold text-zinc-900 dark:text-zinc-100">
                                    {{ $page.props.auth.user?.name }}
                                </p>
                                <span
                                    :class="[
                                        'px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider',
                                        config.badgeClass || 'bg-zinc-100 text-zinc-700 border border-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700',
                                    ]"
                                >
                                    {{ config.label || $page.props.auth.user?.role }}
                                </span>
                            </div>
                            <p class="truncate text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5">
                                {{ censorEmail($page.props.auth.user?.email) }}
                            </p>
                        </div>

                        <!-- Dropdown Action Links -->
                        <DropdownLink
                            :href="route('profile.edit')"
                            class="text-zinc-700 hover:text-zinc-900 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:text-white dark:hover:bg-zinc-800/80 focus:bg-zinc-100 dark:focus:bg-zinc-800/80"
                        >
                            <svg class="h-4 w-4 shrink-0 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profile & Account</span>
                        </DropdownLink>

                        <DropdownLink
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="!text-red-600 hover:!bg-red-50 hover:!text-red-700 focus:!bg-red-50 dark:!text-red-400 dark:hover:!bg-red-500/10 dark:hover:!text-red-300 dark:focus:!bg-red-500/10"
                        >
                            <svg class="h-4 w-4 shrink-0 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Log Out</span>
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
        </aside>

        <!-- Mobile Backdrop Overlay -->
        <div
            v-show="showingNav"
            class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"
            @click="showingNav = false"
        />

        <!-- Main Content -->
        <div class="lg:pl-64">
            <main class="p-4 sm:p-6 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>

