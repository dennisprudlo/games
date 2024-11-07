<script setup lang="ts">
defineOptions({
    inheritAttrs: false
});
</script>

<template>
    <div class="mb-2 flex items-end space-x-5 justify-between">
        <h2 v-bind="$attrs" class="text-lg font-medium">
            <slot />
        </h2>
        <div v-if="$slots.actions">
            <slot name="actions" />
        </div>
    </div>
</template>
