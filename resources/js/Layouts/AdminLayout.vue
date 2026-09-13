<script setup>
import { computed } from 'vue';
import BaseLayout from './BaseLayout.vue';
import { useRoleNav } from '@/Composables/useRoleNav';

defineOptions({ layout: null });

const { viewModes, activeMode, getNavItemsByRole } = useRoleNav('admin');
const navItems = computed(() => getNavItemsByRole(activeMode.value));

const config = {
    label: 'Admin',
    role: 'admin',
    avatarBg: 'bg-red-100 text-red-700 border border-red-200 dark:bg-red-500/20 dark:text-red-400 dark:border-red-500/30',
    badgeClass: 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-500/15 dark:text-red-400 dark:border-red-500/30',
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


