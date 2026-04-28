import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { GraduationCap, LayoutGrid } from 'lucide-vue-next';
import { dashboard as adminDashboard } from '@/routes/admin';
import { index as adminWorkshopsIndex } from '@/routes/admin/workshops';
import { dashboard as employeeDashboard } from '@/routes/employee';
import { index as employeeWorkshopsIndex } from '@/routes/employee/workshops';
import type { NavItem } from '@/types';

const adminNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: adminDashboard.url(),
        icon: LayoutGrid,
    },
    {
        title: 'Workshops',
        href: adminWorkshopsIndex.url(),
        icon: GraduationCap,
    },
];

const employeeNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: employeeDashboard.url(),
        icon: LayoutGrid,
    },
    {
        title: 'Workshops',
        href: employeeWorkshopsIndex.url(),
        icon: GraduationCap,
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
