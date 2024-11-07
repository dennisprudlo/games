<script lang="ts" setup>
import FormLabel from './FormLabel.vue';

defineOptions({
    inheritAttrs: false
});

const props = defineProps({
    id: String,
    title: String,
    invalid: Boolean,
    disabled: Boolean,
    small: Boolean,
    modelValue: [Array, Boolean],
    neutral: Boolean,
    value: String,
    checked: Boolean,
    noLabel: {
        type: Boolean,
        default: false,
    }
});

const emit = defineEmits(['update:modelValue']);

const handleCheckboxChange = (event: Event) => {
    const target = event.target as HTMLInputElement;

    if (typeof props.modelValue === 'boolean') {
        emit('update:modelValue', target.checked);
        return;
    }

    const set = new Set(props.modelValue || []) as Set<any>;

    if (target.checked) {
        set.add(target.value);
    } else {
        set.delete(target.value);
    }

    emit('update:modelValue', Array.from(set));
};
</script>

<template>
    <div class="inline-flex space-x-2" :class="disabled && 'pointer-event-none opacity-50'">
        <input v-bind="$attrs" :id="id" type="checkbox" checkbox-type="toggle" @change="handleCheckboxChange" :value="value" :checked="checked !== undefined ? checked : (typeof modelValue === 'boolean' ? modelValue : modelValue?.includes(value))" :disabled="disabled" :class="`
            ${ small ? 'h-5 w-8 checked:after:translate-x-3' : 'h-6 w-10 checked:after:translate-x-4' }
            cursor-pointer rounded-full appearance-none p-0.5 inline-block align-middle select-none flex-shrink-0
            ${ invalid ? 'ring-2 ring-danger shadow-lg shadow-danger/10' : 'focus:ring-2 ring-primary focus:shadow-lg focus:shadow-primary/10' }
            bg-gray-100 outline-none transition bg-origin-border bg-cover bg-center bg-no-repeat bg-primary/10 checked:bg-primary after:block after:rounded-full after:size-5 after:bg-white after:shadow-md after:transition-transform`"
        />
        <FormLabel v-if="! noLabel" :for="id" :class="{'[&>label]:font-normal': neutral, '[&>label]:leading-5': small, '[&>label]:leading-6': !small}" :title="title" />
    </div>
</template>
