<script lang="ts" setup>
import { PropType } from 'vue';

defineProps({
    invalid: Boolean,
    large: Boolean,
    modelValue: [String, null] as unknown as PropType<string|null>
})

defineEmits(['update:modelValue'])
</script>

<template>
    <textarea
        rows="4"
        autosize="true"
        :value="modelValue"
        @input="$emit('update:modelValue', ($event.target as HTMLInputElement|undefined)?.value)"
        :class="`
            ${ large ? 'px-4 py-2.5' : 'px-3 py-2 text-sm' }
            ${ invalid ? 'ring-2 ring-danger shadow-lg shadow-danger/10' : 'focus:ring-2 ring-primary focus:shadow-lg focus:shadow-primary/10' }
            bg-gray-100 outline-none placeholder-gray-400 transition-shadow rounded-md
        `"></textarea>
</template>
