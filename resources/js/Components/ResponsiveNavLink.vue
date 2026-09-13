<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-blue-500 text-start text-base font-medium text-blue-600 focus:outline-none focus:text-blue-700 focus:bg-blue-50 focus:border-blue-700 transition duration-150 ease-in-out dark:text-blue-400 dark:bg-zinc-800 dark:focus:bg-blue-950'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-zinc-700 dark:hover:border-zinc-600',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
