<script setup lang="ts">
import { NotificationHandler } from '@/Components';
</script>

<template>
    <main class="max-w-7xl mx-auto p-4 lg:p-12 pt-12 md:pt-16 lg:pt-24">
        <slot />
    </main>

    <footer class="max-w-7xl mx-auto p-4 lg:p-12 text-center space-y-5">
        <div class="space-y-1 text-xs">
            <div>
                <span class="opacity-70">{{ $t('app.footer.implementation') }}</span>
                <a href="https://dennisprudlo.com" target="_blank" class="link">dennisprudlo.com</a>
            </div>
            <div class="opacity-70">&copy; {{ new Date().getFullYear() }} Dennis Prudlo. {{ $t('app.footer.copyright') }}</div>
        </div>
        <div class="flex items-center justify-center space-x-3 text-base opacity-70">
            <a href="https://github.com/dennisprudlo" target="_blank" class="hover:text-primary transition-colors">
                <i class="fa-brands fa-github"></i>
            </a>
            <a href="https://instagram.com/dennisprudlo" target="_blank" class="hover:text-primary transition-colors">
                <i class="fa-brands fa-instagram"></i>
            </a>
            <a href="https://x.com/dennisprudlo" target="_blank" class="hover:text-primary transition-colors">
                <i class="fa-brands fa-x-twitter"></i>
            </a>
        </div>
    </footer>

    <NotificationHandler />
</template>
