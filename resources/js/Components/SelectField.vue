<script lang="ts" setup>
import { SelectOptions } from '@/Types/inertia';
import { PropType } from 'vue';

defineProps({
    invalid: Boolean,
    small: Boolean,
    large: Boolean,
    modelValue: [String, Number],
    options: Array as PropType<SelectOptions>,
    htmlOptions: Boolean,
});

const emit = defineEmits(['update:modelValue', 'change'])

const emitChange = ($event: Event) => {
    const value = ($event.target as HTMLInputElement|undefined)?.value;
    emit('update:modelValue', value);
    emit('change', value);
}
</script>

<template>
    <select
        :value="modelValue"
        @change="emitChange($event)"
        style="background-position: right 0.5rem center; background-size: 1.5rem 1.5rem;"
        :class="`
            ${ large ? 'px-4 py-2.5 pr-10 rounded-md' : (small ? 'px-2.5 py-1 pr-8 text-sm rounded-md' : 'px-3 py-2 pr-8 text-sm rounded-md') }
            ${ invalid ? 'ring-2 ring-danger shadow-lg shadow-danger/10' : 'focus:ring-2 ring-primary focus:shadow-lg focus:shadow-primary/10' }
            bg-gray-100 outline-none transition-shadow bg-no-repeat`
    ">
        <template v-if="htmlOptions">
            <option v-for="option in options" :value="option.key" v-html="option.value"></option>
        </template>
        <template v-else>
            <option v-for="option in options" :value="option.key" v-text="option.value"></option>
        </template>
    </select>
</template>
