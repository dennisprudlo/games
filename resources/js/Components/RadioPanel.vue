<script setup lang="ts">
import { PropType } from 'vue';

defineProps({
    id: String,
    name: String,
    danger: Boolean,
    value: [String, Number, Boolean],
    modelValue: [String, Number, Boolean] as unknown as PropType<string|number|boolean|null>,
});

defineEmits(['update:modelValue'])
</script>

<template>
    <div class="font-semibold">
        <input :id="id" type="radio" :name="name" class="hidden peer" :value="value" :checked="modelValue === value" @input="$emit('update:modelValue', ($event.target as HTMLInputElement|undefined)?.value)">
        <label :for="id" class="block rounded-md transition border-2 cursor-pointer select-none px-4 py-2.5" :class="{
            'hover:border-primary hover:text-primary hover:bg-primary/10 peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary': !danger,
            'hover:border-danger hover:text-danger hover:bg-danger/10 peer-checked:border-danger peer-checked:bg-danger/10 peer-checked:text-danger': danger,
        }">
            <slot />
        </label>
    </div>
</template>
