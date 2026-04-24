<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { dashboard } from '@/routes/employee';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard.url(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Employee Dashboard" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        Employee Dashboard
    </div>
</template>
