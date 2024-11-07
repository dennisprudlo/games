<script lang="ts" setup>
defineProps({
    message: String,
    error: Boolean
})
</script>

<template>
    <span v-if="message" class="text-xs" :class="{ 'text-danger': error, 'text-primary-muted': !error }">
        {{ message }}
    </span>
</template>
