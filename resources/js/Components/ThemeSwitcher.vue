<script setup>
import { ref, onMounted } from 'vue';

defineProps({
    compact: {
        type: Boolean,
        default: false,
    },
});

const isDark = ref(false);

onMounted(() => {
    const stored = localStorage.getItem('theme');
    isDark.value = stored === 'dark' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches);
    applyTheme();
});

function setTheme(dark) {
    isDark.value = dark;
    localStorage.setItem('theme', dark ? 'dark' : 'light');
    applyTheme();
}

function applyTheme() {
    const css = document.createElement('style');
    css.appendChild(
        document.createTextNode(
            `*, *::before, *::after {
                -webkit-transition: none !important;
                -moz-transition: none !important;
                -o-transition: none !important;
                -ms-transition: none !important;
                transition: none !important;
            }`
        )
    );
    document.head.appendChild(css);

    document.documentElement.classList.toggle('dark', isDark.value);

    // Force reflow
    void window.getComputedStyle(document.body).opacity;

    requestAnimationFrame(() => {
        if (document.head.contains(css)) {
            document.head.removeChild(css);
        }
    });
}
</script>

<template>
    <div
        class="inline-flex w-full items-center rounded-xl bg-zinc-100 p-1 border border-zinc-200/90 shadow-inner dark:bg-zinc-950/80 dark:border-zinc-800/80"
        :class="{ 'w-auto': compact }"
    >
        <button
            type="button"
            @click="setTheme(false)"
            :class="[
                'flex flex-1 items-center justify-center gap-1.5 rounded-lg py-1.5 px-3 text-xs font-medium border transition-colors duration-75',
                !isDark
                    ? 'bg-white text-zinc-900 border-zinc-200/80 font-semibold shadow-sm dark:bg-zinc-800 dark:text-white dark:border-zinc-700/60'
                    : 'border-transparent text-zinc-500 hover:text-zinc-900 hover:bg-zinc-200/50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/40',
            ]"
            title="Light Mode"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="currentColor"
                class="h-4 w-4 shrink-0 text-amber-500 dark:text-amber-400"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                />
            </svg>
            <span v-if="!compact">Light</span>
        </button>

        <button
            type="button"
            @click="setTheme(true)"
            :class="[
                'flex flex-1 items-center justify-center gap-1.5 rounded-lg py-1.5 px-3 text-xs font-medium border transition-colors duration-75',
                isDark
                    ? 'bg-white text-zinc-900 border-zinc-200/80 font-semibold shadow-sm dark:bg-zinc-800 dark:text-white dark:border-zinc-700/60'
                    : 'border-transparent text-zinc-500 hover:text-zinc-900 hover:bg-zinc-200/50 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/40',
            ]"
            title="Dark Mode"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="currentColor"
                class="h-4 w-4 shrink-0 text-indigo-500 dark:text-indigo-400"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"
                />
            </svg>
            <span v-if="!compact">Dark</span>
        </button>
    </div>
</template>

