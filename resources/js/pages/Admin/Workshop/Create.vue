<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { store } from '@/routes/admin/workshops';
import { useWorkshopSchedule } from '@/composables/useWorkshopSchedule';

import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Spinner } from '@/components/ui/spinner';

const form = useForm({
    title: '',
    description: '',
    starts_at: '',
    ends_at: '',
    capacity: 1,
});

const { duration } = useWorkshopSchedule(form);

function submit() {
    form.post(store().url, {
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <Head title="Create Workshop" />

        <div class="mx-auto flex max-w-2xl flex-col gap-6">
            <h1 class="text-2xl font-semibold">Create Workshop</h1>

            <form @submit.prevent="submit" class="flex flex-col gap-6">
                <!-- Title -->
                <div class="grid gap-2">
                    <Label for="title">Title</Label>
                    <Input id="title" v-model="form.title" required />
                    <InputError :message="form.errors.title" />
                </div>

                <!-- Description -->
                <div class="grid gap-2">
                    <Label for="description">Description</Label>
                    <Textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                    />
                    <InputError :message="form.errors.description" />
                </div>

                <!-- Dates -->
                <div class="grid gap-2">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <Label for="starts_at">Start</Label>
                            <Input
                                id="starts_at"
                                type="datetime-local"
                                step="900"
                                v-model="form.starts_at"
                                required
                            />
                            <InputError :message="form.errors.starts_at" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="ends_at">End</Label>
                            <Input
                                id="ends_at"
                                type="datetime-local"
                                step="900"
                                v-model="form.ends_at"
                                required
                            />
                            <InputError :message="form.errors.ends_at" />
                        </div>
                    </div>

                    <div v-if="duration" class="rounded-md bg-muted px-3 py-2 text-sm text-muted-foreground">
                        Duration: <span class="font-medium text-foreground">{{ duration }}</span>
                    </div>
                </div>

                <!-- Capacity -->
                <div class="grid gap-2">
                    <Label for="capacity">Capacity</Label>
                    <Input
                        id="capacity"
                        type="number"
                        min="1"
                        v-model="form.capacity"
                        required
                    />
                    <InputError :message="form.errors.capacity" />
                </div>

                <!-- Submit -->
                <Button type="submit" :disabled="form.processing">
                    <Spinner v-if="form.processing" />
                    Create Workshop
                </Button>
            </form>
        </div>
    </div>
</template>
