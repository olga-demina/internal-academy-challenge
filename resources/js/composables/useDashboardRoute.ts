import { usePage } from '@inertiajs/vue3';
import { dashboard as adminDashboard } from '@/routes/admin';
import { dashboard as employeeDashboard } from '@/routes/employee';

export function useDashboardRoute(): string {
    const page = usePage();
    const role = page.props.auth.user?.role;
    return role === 'admin' ? adminDashboard.url() : employeeDashboard.url();
}
