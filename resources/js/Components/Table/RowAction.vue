<script setup lang="ts">
import { Tooltip } from '@/Components/Application';

defineProps({
    icon: String,
    tooltip: String,
    href: String,
});
</script>

<template>
    <component :is="href ? 'a' : 'div'" :href="href" class="px-2 py-1 -my-1 rounded group hover:bg-gray-100 transition-colors cursor-pointer h-full">
        <Tooltip v-if="tooltip" :content="tooltip">
            <i class="fa-regular text-gray-400 group-hover:text-muted" :class="icon"></i>
        </Tooltip>
        <i v-else class="fa-regular text-gray-400 group-hover:text-muted" :class="icon"></i>
    </component>
</template>
