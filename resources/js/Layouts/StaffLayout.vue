<script setup>
import { computed } from 'vue';
import BaseLayout from './BaseLayout.vue';
import { useRoleNav } from '@/Composables/useRoleNav';

defineOptions({ layout: null });

const { viewModes, activeMode, getNavItemsByRole } = useRoleNav('staff');
const navItems = computed(() => getNavItemsByRole(activeMode.value));

const config = {
    label: 'Staff',
    role: 'staff',
    avatarBg: 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-500/20 dark:text-blue-400 dark:border-blue-500/30',
    badgeClass: 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-500/15 dark:text-blue-400 dark:border-blue-500/30',
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


