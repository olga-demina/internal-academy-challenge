<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { login } from '@/routes';
import { useDashboardRoute } from '@/composables/useDashboardRoute';

const dashboardUrl = useDashboardRoute();
</script>

<template>
    <Head title="Welcome" />

    <div class="flex min-h-screen flex-col bg-background text-foreground">
        <!-- Nav -->
        <header class="flex items-center justify-between px-8 py-5 border-b">
            <div class="flex items-center gap-2 font-semibold text-lg">
                <div class="flex h-8 w-8 items-center justify-center rounded-md bg-foreground text-background text-sm font-bold">
                    IA
                </div>
                Internal Academy
            </div>

            <nav>
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboardUrl"
                    class="text-sm font-medium underline-offset-4 hover:underline"
                >
                    Go to dashboard
                </Link>
                <Link
                    v-else
                    :href="login()"
                    class="text-sm font-medium underline-offset-4 hover:underline"
                >
                    Log in
                </Link>
            </nav>
        </header>

        <!-- Hero -->
        <main class="flex flex-1 flex-col items-center justify-center px-6 text-center">
            <div class="max-w-2xl space-y-6">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-foreground text-background text-2xl font-bold mb-2">
                    IA
                </div>
                <h1 class="text-4xl font-semibold tracking-tight lg:text-5xl">
                    Internal Academy
                </h1>
                <p class="text-lg text-muted-foreground">
                    Discover and join internal training workshops.<br />
                    Grow your skills, one session at a time.
                </p>
                <div class="pt-2">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboardUrl"
                        class="inline-flex items-center justify-center rounded-md bg-foreground px-6 py-2.5 text-sm font-medium text-background hover:opacity-90 transition-opacity"
                    >
                        Go to dashboard
                    </Link>
                    <Link
                        v-else
                        :href="login()"
                        class="inline-flex items-center justify-center rounded-md bg-foreground px-6 py-2.5 text-sm font-medium text-background hover:opacity-90 transition-opacity"
                    >
                        Get started
                    </Link>
                </div>
            </div>
        </main>
    </div>
</template>
