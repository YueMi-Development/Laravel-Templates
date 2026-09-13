import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useRoleNav(initialRole = null) {
    const page = usePage();
    const userRole = computed(() => page.props.auth?.user?.role || 'user');
    const isAdmin = computed(() => userRole.value === 'admin');
    const isStaff = computed(() => userRole.value === 'staff');
    const isStaffOrAbove = computed(() => isAdmin.value || isStaff.value);

    // Active mode state that only switches the sidebar view locally without navigating
    const activeMode = ref(initialRole || userRole.value);

    const viewModes = computed(() => {
        const modes = [];

        if (isAdmin.value) {
            modes.push({ id: 'admin', label: 'Admin' });
        }
        if (isStaffOrAbove.value) {
            modes.push({ id: 'staff', label: 'Staff' });
        }
        modes.push({ id: 'user', label: 'User' });

        return modes;
    });

    const getNavItemsByRole = (role) => {
        const items = [];

        if (role === 'admin') {
            items.push({
                label: 'Dashboard',
                href: route('dashboard.admin'),
                active: route().current('dashboard.admin'),
                icon: 'dashboard',
            });
        } else if (role === 'staff') {
            items.push({
                label: 'Dashboard',
                href: route('dashboard.staff'),
                active: route().current('dashboard.staff'),
                icon: 'dashboard',
            });
        } else {
            items.push({
                label: 'Dashboard',
                href: route('dashboard.user'),
                active: route().current('dashboard.user'),
                icon: 'dashboard',
            });
        }

        return items;
    };

    const currentNavItems = computed(() => getNavItemsByRole(activeMode.value));

    return {
        userRole,
        isAdmin,
        isStaff,
        isStaffOrAbove,
        activeMode,
        viewModes,
        getNavItemsByRole,
        currentNavItems,
    };
}


