<script setup>
import { computed } from 'vue';
import BaseLayout from './BaseLayout.vue';
import { useRoleNav } from '@/Composables/useRoleNav';

defineOptions({ layout: null });

const { viewModes, activeMode, getNavItemsByRole } = useRoleNav('user');
const navItems = computed(() => getNavItemsByRole(activeMode.value));

const config = {
    label: 'User',
    role: 'user',
    avatarBg: 'bg-emerald-100 text-emerald-700 border border-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-400 dark:border-emerald-500/30',
    badgeClass: 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-500/15 dark:text-emerald-400 dark:border-emerald-500/30',
};
</script>

<template>
    <BaseLayout
        :view-modes="viewModes"
        :nav-items="navItems"
        :config="config"
        v-model:active-mode="activeMode"
    >
        <template v-if="$slots.logo" #logo>
            <slot name="logo" />
        </template>
        <template v-if="$slots.nav" #nav>
            <slot name="nav" />
        </template>
        <slot />
    </BaseLayout>
</template>


