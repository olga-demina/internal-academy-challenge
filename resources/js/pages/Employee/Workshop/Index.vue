<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { index as workshopsIndex } from '@/routes/employee/workshops';
import { CalendarDays, Users } from 'lucide-vue-next';
import { formatRange } from '@/lib/date';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Workshops',
                href: workshopsIndex.url(),
            },
        ],
    },
});

defineProps<{
    workshops: Array<{
        id: number;
        title: string;
        description: string;
        starts_at: string;
        ends_at: string;
        capacity: number;
    }>;
}>();
</script>

<template>
    <Head title="Upcoming Workshops" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <h1 class="text-2xl font-semibold">Upcoming Workshops</h1>

        <div v-if="workshops.length" class="grid gap-4">
            <div
                v-for="workshop in workshops"
                :key="workshop.id"
                class="rounded-2xl border p-4 shadow-sm"
            >
                <div class="flex flex-col gap-2">
                    <h2 class="text-lg font-semibold">{{ workshop.title }}</h2>
                    <p class="text-sm text-gray-500">{{ workshop.description }}</p>
                    <p class="mt-1 flex items-center gap-1.5 text-sm">
                        <CalendarDays class="size-4 shrink-0" />
                        {{ formatRange(workshop.starts_at, workshop.ends_at) }}
                    </p>
                    <p class="flex items-center gap-1.5 text-sm">
                        <Users class="size-4 shrink-0" />
                        Max seats: {{ workshop.capacity }}
                    </p>
                </div>
            </div>
        </div>

        <div v-else class="text-center text-gray-500">
            No upcoming workshops.
        </div>
    </div>
</template>
