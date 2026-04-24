<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { create, edit, destroy } from '@/routes/admin/workshops';
import { Button } from '@/components/ui/button';
import { formatRange } from '@/lib/date';

defineProps<{
    workshops: {
        data: Array<{
            id: number;
            title: string;
            description: string;
            starts_at: string;
            ends_at: string;
            capacity: number;
        }>;
    };
}>();
</script>

<template>
    <Head title="My Workshops" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">My Workshops</h1>

            <Link :href="create()">
                <Button>Create Workshop</Button>
            </Link>
        </div>

        <!-- List -->
        <div v-if="workshops.data.length" class="grid gap-4">
            <div
                v-for="workshop in workshops.data"
                :key="workshop.id"
                class="rounded-2xl border p-4 shadow-sm"
            >
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col gap-2">
                        <h2 class="text-lg font-semibold">
                            {{ workshop.title }}
                        </h2>
                        <p class="text-sm text-gray-500">
                            {{ workshop.description }}
                        </p>
                        <p class="mt-2 text-sm">
                            📅
                            {{
                                formatRange(
                                    workshop.starts_at,
                                    workshop.ends_at,
                                )
                            }}
                        </p>
                        <p class="text-sm">
                            👥 Max seats: {{ workshop.capacity }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <Link :href="edit(workshop.id)">
                            <Button variant="outline">Edit</Button>
                        </Link>

                        <Link
                            as="button"
                            method="delete"
                            :href="destroy(workshop.id)"
                        >
                            <Button variant="destructive"> Delete </Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-center text-gray-500">No workshops yet.</div>
    </div>
</template>
