<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        default: undefined,
    },
    as: {
        type: String,
        default: 'a',
    },
    method: {
        type: String,
        default: 'get',
    },
});
</script>

<template>
    <Link
        :href="href"
        :as="as"
        :method="method"
        class="flex w-full items-center gap-2.5 px-3 py-2 text-start text-sm font-medium text-zinc-700 transition duration-150 ease-in-out hover:bg-zinc-100 hover:text-zinc-900 focus:bg-zinc-100 focus:outline-none dark:text-zinc-200 dark:hover:bg-zinc-700/50 dark:hover:text-white rounded-lg"
    >
        <slot />
    </Link>
</template>

