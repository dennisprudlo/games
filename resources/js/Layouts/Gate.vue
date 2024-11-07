<script setup lang="ts">
import { route } from 'ziggy';
import { Link } from '@inertiajs/vue3';
import { Panel, NotificationHandler } from '@/Components';
</script>

<template>
    <main class="min-h-screen bg-gray-100 py-16 px-12">
        <Panel container="max-w-md mx-auto" class="p-8">
            <Link :href="route('landing')" class="select-none outline-none">
                <!-- <img src="@/../images/logo/logo-default.svg" alt="Pangoon Development" class="h-7"> -->
            </Link>

            <slot />
        </Panel>
    </main>

    <NotificationHandler />
</template>
