import { computed } from 'vue';

export function useAuth() {
    const userRole = computed(() => $page.props.auth.user?.role);
    const isAdmin = computed(() => userRole.value === 'admin');
    const isStaff = computed(() => userRole.value === 'staff');
    const isStaffOrAbove = computed(() => isAdmin.value || isStaff.value);

    const navItems = computed(() => {
        const items = [];

        if (isAdmin.value) {
            items.push({ label: 'Dashboard', href: route('dashboard'), active: route().current('dashboard'), icon: 'home' });
        }

        if (isStaff.value) {
            items.push({ label: 'Staff', href: route('dashboard.staff'), active: route().current('dashboard.staff'), icon: 'users' });
            items.push({ label: 'Admin', href: route('dashboard.admin'), active: route().current('dashboard.admin'), icon: 'shield' });
        }

        if (!isStaffOrAbove.value) {
            items.push({ label: 'Dashboard', href: route('dashboard'), active: route().current('dashboard'), icon: 'home' });
            items.push({ label: 'My Activity', href: route('dashboard.user'), active: route().current('dashboard.user'), icon: 'user' });
        }

        return items;
    });

    return { userRole, isAdmin, isStaff, isStaffOrAbove, navItems };
}
