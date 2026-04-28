<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { dashboard } from '@/routes/admin';
import { CalendarDays, Trophy, Users } from 'lucide-vue-next';
import { formatRange } from '@/lib/date';
import { onUnmounted, ref } from 'vue';
import { useEcho } from '@laravel/echo-vue';

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

const props = defineProps<{
    totalConfirmed: number;
    mostPopular: {
        title: string;
        starts_at: string;
        ends_at: string;
        capacity: number;
        total: number;
        confirmed: number;
        waiting: number;
    } | null;
}>();

const count = ref(props.totalConfirmed);

const page = usePage();
const adminId = page.props.auth.user.id;

const { leaveChannel } = useEcho(
    `admin.${adminId}`,
    '.RegistrationCountChanged',
    (e: { totalConfirmed: number }) => {
        count.value = e.totalConfirmed;
    },
);

onUnmounted(() => {
    leaveChannel();
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <!-- Most popular workshop -->
            <div v-if="mostPopular" class="rounded-2xl border p-6 shadow-sm">
                <div
                    class="mb-4 flex items-center gap-2 text-sm font-medium text-muted-foreground"
                >
                    <Trophy class="size-4" />
                    Most popular workshop
                </div>

                <h2 class="text-xl font-semibold">{{ mostPopular.title }}</h2>

                <p
                    class="mt-1 flex items-center gap-1.5 text-sm text-muted-foreground"
                >
                    <CalendarDays class="size-4 shrink-0" />
                    {{
                        formatRange(mostPopular.starts_at, mostPopular.ends_at)
                    }}
                </p>

                <div class="mt-4 flex items-center gap-6">
                    <div class="flex items-center gap-1.5 text-sm">
                        <Users class="size-4 shrink-0 text-muted-foreground" />
                        <span>
                            <span class="font-semibold">{{
                                mostPopular.total
                            }}</span>
                            / {{ mostPopular.capacity }} registrations
                        </span>
                    </div>

                    <div
                        v-if="mostPopular.waiting > 0"
                        class="text-sm text-muted-foreground"
                    >
                        {{ mostPopular.confirmed }} confirmed
                        <span class="mx-1">·</span>
                        <span
                            class="font-medium text-amber-600 dark:text-amber-400"
                        >
                            {{ mostPopular.waiting }} on waitlist
                        </span>
                    </div>
                    <div v-else class="text-sm text-muted-foreground">
                        {{ mostPopular.confirmed }} confirmed
                    </div>
                </div>

                <p
                    v-if="mostPopular.waiting > 0"
                    class="mt-3 text-xs text-muted-foreground"
                >
                    High demand — consider running this workshop again.
                </p>
            </div>
            <div
                v-else
                class="rounded-2xl border border-dashed p-6 text-center text-sm text-muted-foreground"
            >
                No workshops yet.
            </div>

            <!-- Total subscriptions count -->
            <div
                v-if="totalConfirmed > 0"
                class="rounded-2xl border p-6 shadow-sm"
            >
                <div
                    class="mb-4 flex items-center gap-2 text-sm font-medium text-muted-foreground"
                >
                    <Users class="size-4" />
                    Total confirmed registrations
                </div>
                <p class="text-4xl font-bold">{{ count }}</p>
            </div>
        </div>
    </div>
</template>
