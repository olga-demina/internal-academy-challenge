<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { create, edit, destroy } from '@/routes/admin/workshops';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { CalendarDays, Users } from 'lucide-vue-next';
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
            available_seats: number;
        }>;
    };
}>();

const workshopToDelete = ref<number | null>(null);

function confirmDelete(id: number) {
    workshopToDelete.value = id;
}

function executeDelete() {
    if (workshopToDelete.value === null) return;
    router.delete(destroy(workshopToDelete.value).url, {
        onFinish: () => { workshopToDelete.value = null; },
    });
}
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
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-semibold">{{ workshop.title }}</h2>
                            <Badge v-if="workshop.available_seats === 0" variant="destructive">Full</Badge>
                        </div>
                        <p class="text-sm text-gray-500">{{ workshop.description }}</p>
                        <p class="mt-2 flex items-center gap-1.5 text-sm">
                            <CalendarDays class="size-4 shrink-0" />
                            {{ formatRange(workshop.starts_at, workshop.ends_at) }}
                        </p>
                        <p class="flex items-center gap-1.5 text-sm">
                            <Users class="size-4 shrink-0" />
                            {{ workshop.available_seats }} / {{ workshop.capacity }} seats available
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <Link :href="edit(workshop.id)">
                            <Button variant="outline">Edit</Button>
                        </Link>

                        <Button
                            variant="destructive"
                            @click="confirmDelete(workshop.id)"
                        >
                            Delete
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-center text-gray-500">No workshops yet.</div>
    </div>

    <!-- Delete confirmation dialog -->
    <Dialog :open="workshopToDelete !== null" @update:open="workshopToDelete = null">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Delete workshop</DialogTitle>
                <DialogDescription>
                    This action cannot be undone. The workshop will be permanently deleted.
                </DialogDescription>
            </DialogHeader>
            <DialogFooter>
                <Button variant="outline" @click="workshopToDelete = null">
                    Cancel
                </Button>
                <Button variant="destructive" @click="executeDelete">
                    Delete
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
