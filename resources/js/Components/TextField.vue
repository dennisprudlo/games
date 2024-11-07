<script lang="ts" setup>
defineOptions({
    inheritAttrs: false
});

const props = defineProps({
    invalid: Boolean,
    large: Boolean,
    modelValue: [String, Number],
});

defineEmits(['update:modelValue'])

const padding = props.large ? 'px-4 py-2.5' : 'px-3 py-2';
</script>

<template>
    <div :class="`
        ${ invalid ? 'ring-2 ring-danger shadow-lg shadow-danger/10' : 'focus-within:ring-2 ring-primary focus-within:shadow-lg focus-within:shadow-primary/10' }
        ${ large ? 'text-base' : 'text-sm' }
        flex bg-gray-100 outline-none transition-shadow rounded-md grow`"
    >
        <div v-if="$slots.before" :class="`shrink-0 ${ padding } pr-0 text-label rounded-l-md`">
            <slot name="before" />
        </div>
        <input
            v-bind="$attrs"
            :class="`focus:outline-none placeholder-gray-400 grow min-w-0 bg-gray-100 ${ padding } rounded-md disabled:text-primary-muted`"
            :value="modelValue"
            @input="$emit('update:modelValue', ($event.target as HTMLInputElement|undefined)?.value)"
        />
        <div v-if="$slots.after" :class="`shrink-0 ${ padding } pl-0 text-label rounded-r-md`">
            <slot name="after" />
        </div>
    </div>
</template>
