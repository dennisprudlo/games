<script setup lang="ts">
defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
})
</script>

<template>
    <div class="max-w-lg mx-auto text-center text-md py-12">
        <h2 class="font-semibold text-3xl">{{ title }}</h2>
        <p class="mt-2 text-primary-muted">
            <span v-html="description" />
        </p>
        <div class="mt-8 flex items-center justify-center">
            <slot />
        </div>
    </div>
</template>
