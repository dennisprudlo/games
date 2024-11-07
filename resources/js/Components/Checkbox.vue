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
        <input v-bind="$attrs" :id="id" type="checkbox" @change="handleCheckboxChange" :value="value" :checked="typeof modelValue === 'boolean' ? modelValue : modelValue?.includes(value)" :disabled="disabled" :class="`
            ${ small ? 'size-5' : 'size-6' }
            rounded-md appearance-none p-0 inline-block align-middle select-none flex-shrink-0
            ${ invalid ? 'ring-2 ring-danger shadow-lg shadow-danger/10' : 'focus:ring-2 ring-primary focus:shadow-lg focus:shadow-primary/10' }
            bg-gray-100 outline-none transition bg-origin-border bg-cover bg-center bg-no-repeat checked:bg-primary/10`"
        />
        <FormLabel v-if="! noLabel" :for="id" :class="{'[&>label]:font-normal': neutral, '[&>label]:leading-5': small, '[&>label]:leading-6': !small}" :title="title" />
    </div>
</template>
