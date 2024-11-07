<script lang="ts" setup>
import { onMounted, PropType, ref } from 'vue';

const props = defineProps({
    content: {
        type: String as PropType<string|null|undefined>,
        required: true,
    },
    size: {
        type: String,
        default: 'text-xs'
    },
    icon: {
        type: String,
        default: 'fa-circle-question',
    },
    allowHtml: {
        type: Boolean,
        default: false,
    },
    alignLeft: {
        type: Boolean,
        default: false,
    },
    withIcon: {
        type: Boolean,
        default: false,
    },
});

const target = ref<HTMLElement|null>(null);

onMounted(() => {
    (window as any).tippy(target.value, {
        allowHTML: props.allowHtml,
        content: props.content,
    });
});
</script>

<template>
    <span ref="target" class="space-x-1" :class="alignLeft && 'tooltip-left'">
        <i v-if="withIcon || ! $slots.default" class="fa-regular" :class="`${ icon } ${ size }`"></i>
        <slot />
    </span>
</template>
