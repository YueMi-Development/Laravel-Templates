// Shared layout config for role-based layouts
export const layoutConfig = {
    admin: {
        sidebar: {
            bg: 'bg-red-600 dark:bg-red-900',
            border: 'border-red-200 dark:border-red-900',
            hoverBg: 'hover:bg-red-500 dark:hover:bg-red-800',
            activeBg: 'bg-red-700 dark:bg-red-800',
            text: 'text-red-100 dark:text-red-200',
            borderLine: 'border-red-500 dark:border-red-800',
            avatarBg: 'bg-red-800',
            label: 'Admin',
        },
    },
    staff: {
        sidebar: {
            bg: 'bg-blue-600 dark:bg-blue-900',
            border: 'border-blue-200 dark:border-blue-900',
            hoverBg: 'hover:bg-blue-500 dark:hover:bg-blue-800',
            activeBg: 'bg-blue-700 dark:bg-blue-800',
            text: 'text-blue-100 dark:text-blue-200',
            borderLine: 'border-blue-500 dark:border-blue-800',
            avatarBg: 'bg-blue-800',
            label: 'Staff',
        },
    },
    user: {
        sidebar: {
            bg: 'bg-gray-600 dark:bg-gray-800',
            border: 'border-gray-200 dark:border-gray-700',
            hoverBg: 'hover:bg-gray-500 dark:hover:bg-gray-700',
            activeBg: 'bg-gray-700 dark:bg-gray-700',
            text: 'text-gray-100 dark:text-gray-200',
            borderLine: 'border-gray-500 dark:border-gray-700',
            avatarBg: 'bg-gray-800',
            label: 'User',
        },
    },
};
