import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { GraduationCap, LayoutGrid } from 'lucide-vue-next';
import { dashboard as adminDashboard } from '@/routes/admin';
import { index as workshopsIndex } from '@/routes/admin/workshops';
import { dashboard as employeeDashboard } from '@/routes/employee';
import type { NavItem } from '@/types';

const adminNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: adminDashboard.url(),
        icon: LayoutGrid,
    },
    {
        title: 'Workshops',
        href: workshopsIndex.url(),
        icon: GraduationCap,
    },
];

const employeeNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: employeeDashboard.url(),
        icon: LayoutGrid,
    },
];

export function useNavItems() {
    const page = usePage();

    return computed<NavItem[]>(() =>
        page.props.auth.user?.role === 'admin'
            ? adminNavItems
            : employeeNavItems,
    );
}
