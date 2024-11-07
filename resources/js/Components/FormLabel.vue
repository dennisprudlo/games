<script lang="ts" setup>
import { Tooltip } from '@/Components';

const props = defineProps({
    for: String,
    title: String,
    required: Boolean,
    tooltip: String,
    tooltipAllowHtml: Boolean,
    tooltipAlignLeft: Boolean,
});

const requiredMark = props.required ? 'after:content-[attr(required-mark)] after:text-primary' : '';
</script>

<template>
    <div class="flex items-end justify-between space-x-5">
        <span>
            <label :for="props.for" :class="`text-sm font-semibold ${ requiredMark } space-x-1`" required-mark="*">
                <span>{{ title }}</span>
            </label>
            <Tooltip v-if="tooltip" :content="tooltip" class="ml-1" :allow-html="tooltipAllowHtml" :align-left="tooltipAlignLeft" />
        </span>
        <span v-if="$slots.after" class="text-gray-400 text-xs font-medium">
            <slot name="after"></slot>
        </span>
    </div>
</template>
