<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { index as workshopsIndex } from '@/routes/employee/workshops';
import {
    store as registerStore,
    destroy as destroyRegistration,
} from '@/routes/employee/workshops/registrations';
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

type Workshop = {
    id: number;
    title: string;
    description: string;
    starts_at: string;
    ends_at: string;
    capacity: number;
    available_seats: number;
    registration_status: 'confirmed' | 'waiting' | null;
};

const props = defineProps<{ workshops: Workshop[] }>();

type Filter = 'all' | 'mine';
const filter = ref<Filter>('all');

const visibleWorkshops = computed(() =>
    filter.value === 'mine'
        ? props.workshops.filter((w) => w.registration_status !== null)
        : props.workshops,
);

const page = usePage();

function signUp(workshop: Workshop) {
    router.post(
        registerStore(workshop.id).url,
        {},
        {
            onSuccess: () => {
                const { success } = page.props.flash as { success?: string };
                if (success) toast.success(success);
            },
        },
    );
}

function cancelRegistration(workshop: Workshop) {
    router.delete(destroyRegistration(workshop.id).url, {
        onSuccess: () => {
            const { success } = page.props.flash as { success?: string };
            if (success) toast.success(success);
        },
    });
}
</script>

<template>
    <Head title="Upcoming Workshops" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <!-- Header + filter -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Upcoming Workshops</h1>

            <div class="flex gap-1 rounded-lg border p-1">
                <Button
                    :variant="filter === 'all' ? 'default' : 'ghost'"
                    size="sm"
                    @click="filter = 'all'"
                >
                    All
                </Button>
                <Button
                    :variant="filter === 'mine' ? 'default' : 'ghost'"
                    size="sm"
                    @click="filter = 'mine'"
                >
                    My registrations
                </Button>
            </div>
        </div>

        <!-- List -->
        <div v-if="visibleWorkshops.length" class="grid gap-4">
            <div
                v-for="workshop in visibleWorkshops"
                :key="workshop.id"
                class="rounded-2xl border p-4 shadow-sm transition-colors"
                :class="
                    workshop.registration_status === 'confirmed'
                        ? 'border-green-400/40 bg-primary/5'
                        : workshop.registration_status === 'waiting'
                          ? 'border-yellow-400/40 bg-primary/5'
                          : ''
                "
            >
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-semibold">
                                {{ workshop.title }}
                            </h2>
                            <Badge
                                v-if="
                                    workshop.registration_status === 'confirmed'
                                "
                                class="border-transparent bg-green-500 text-white"
                                >Registered</Badge
                            >
                            <Badge
                                v-if="
                                    workshop.registration_status === 'waiting'
                                "
                                variant="secondary"
                                >Waitlisted</Badge
                            >
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
                            v-if="workshop.registration_status === 'confirmed'"
                            variant="outline"
                            @click="cancelRegistration(workshop)"
                        >
                            Cancel registration
                        </Button>
                        <Button
                            v-else-if="
                                workshop.registration_status === 'waiting'
                            "
                            variant="outline"
                            @click="cancelRegistration(workshop)"
                        >
                            Leave waitlist
                        </Button>
                        <Button
                            v-else-if="workshop.available_seats > 0"
                            @click="signUp(workshop)"
                        >
                            Sign up
                        </Button>
                        <Button
                            v-else
                            variant="secondary"
                            @click="signUp(workshop)"
                        >
                            Join waitlist
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-center text-gray-500">
            {{
                filter === 'mine'
                    ? 'You are not registered for any workshop yet.'
                    : 'No upcoming workshops.'
            }}
        </div>
    </div>
</template>
