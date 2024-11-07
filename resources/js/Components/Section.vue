<script setup lang="ts">
import { SectionTitle } from '@/Components';

defineProps({
    title: String,
});

defineOptions({
    inheritAttrs: false
});
</script>

<template>
    <div class="space-y-5">
        <SectionTitle v-if="title">
            {{ title }}
        </SectionTitle>
        <div v-bind="$attrs">
            <slot />
        </div>
    </div>
</template>
