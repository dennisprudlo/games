<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps({
    href: String,
    icon: String,
    danger: Boolean,
});
</script>

<template>
    <Link v-if="href" :href="href" class="text-left px-2 -mx-2 py-1 rounded flex items-center space-x-2" :class="danger ? 'hover:text-danger-800 hover:bg-danger/5' : 'hover:bg-gray-400/10'">
        <div v-if="icon" class="w-5 flex items-center justify-center">
            <i class="fa-regular" :class="icon"></i>
        </div>
        <span>
            <slot />
        </span>
    </Link>
    <button v-else class="text-left px-2 -mx-2 py-1 rounded flex items-center space-x-2" :class="danger ? 'hover:text-danger-800 hover:bg-danger/5' : 'hover:bg-gray-400/10'" @click="$event.preventDefault()">
        <div v-if="icon" class="w-5 flex items-center justify-center">
            <i class="fa-regular" :class="icon"></i>
        </div>
        <span>
            <slot />
        </span>
    </button>
</template>
