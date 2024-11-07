<script lang="ts" setup>
import { PropType } from 'vue';

defineProps({
    fromBoolean: {
        type: Boolean as PropType<boolean|null>,
        required: false,
        default: null,
    },
    state: {
        type: String as PropType<'active'|'inactive'|'danger'>,
        required: false,
        default: 'active',
    },
});

const color = {
    'active': 'bg-primary/10 text-primary',
    'inactive': 'bg-gray-500/10 text-gray-500',
    'danger': 'bg-red-500/10 text-red-500',
};
</script>

<template>
    <span class="text-xs px-2 py-0.5 rounded" :class="fromBoolean === null ? color[state] : (color[fromBoolean ? 'active' : 'inactive'])">
        <span v-if="fromBoolean !== null">{{ fromBoolean ? 'Ja' : 'Nein' }}</span>
        <slot v-else />
    </span>
</template>
