<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { index as workshopsIndex } from '@/routes/employee/workshops';
import { store as registerStore } from '@/routes/employee/workshops/registrations';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { CalendarDays, Users } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
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
        available_seats: number;
    }>;
}>();

function signUp(workshop: { id: number; title: string }) {
    router.post(registerStore(workshop.id).url, {}, {
        onSuccess: () => toast.success(`Congratulations, you are going to attend "${workshop.title}"!`),
    });
}
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
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-semibold">
                                {{ workshop.title }}
                            </h2>
                            <Badge
                                v-if="workshop.available_seats === 0"
                                variant="destructive"
                                >Full</Badge
                            >
                        </div>
                        <p class="text-sm text-gray-500">
                            {{ workshop.description }}
                        </p>
                        <p class="mt-1 flex items-center gap-1.5 text-sm">
                            <CalendarDays class="size-4 shrink-0" />
                            {{
                                formatRange(
                                    workshop.starts_at,
                                    workshop.ends_at,
                                )
                            }}
                        </p>
                        <p class="flex items-center gap-1.5 text-sm">
                            <Users class="size-4 shrink-0" />
                            {{ workshop.available_seats }} /
                            {{ workshop.capacity }} seats available
                        </p>
                    </div>

                    <div class="flex items-center">
                        <Button
                            :disabled="workshop.available_seats === 0"
                            @click="signUp(workshop)"
                        >
                            Sign up
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-center text-gray-500">
            No upcoming workshops.
        </div>
    </div>
</template>
