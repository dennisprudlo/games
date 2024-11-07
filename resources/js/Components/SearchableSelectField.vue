<script lang="ts" setup>
import { SelectOptions } from '@/Types/inertia';
import { computed, PropType, ref, watch } from 'vue';

const props = defineProps({
    invalid: Boolean,
    large: Boolean,
    modelValue: Array as PropType<Array<string|number>>,
    options: Array as PropType<SelectOptions>
});

const padding = props.large ? 'px-4 py-2.5' : 'px-3 py-2';

const searchTerm = ref('');
const searchableOptions = computed(() => {
    if (! props.options) return [];

    return props.options
        .filter(option => props.modelValue?.includes(option.key.toString()) === false)
        .filter(option => option.value?.toLowerCase().includes(searchTerm.value.toLowerCase()));
});

const emit = defineEmits(['update:modelValue'])

const addSelectionToList = (event: MouseEvent, value: string) => {
    event.preventDefault();
    event.stopPropagation();

    if (! props.modelValue) return;

    if (! props.modelValue.includes(value)) {
        const set = new Set([...props.modelValue, value]);
        emit('update:modelValue', Array.from(set));
        searchTerm.value = '';
    }
}

const removeSelectionFromList = (event: MouseEvent, value: string|number) => {
    event.preventDefault();
    event.stopPropagation();

    if (! props.modelValue) return;

    const set = new Set(props.modelValue);
    set.delete(value);
    emit('update:modelValue', Array.from(set));
}
</script>

<template>
    <div class="relative">
        <div class="min-h-8 flex flex-wrap items-center gap-3 bg-gray-100 outline-none transition-shadow rounded-md grow cursor-pointer" :class="`
            ${ invalid ? 'ring-2 ring-danger shadow-lg shadow-danger/10' : 'focus-within:ring-2 ring-primary focus-within:shadow-lg focus-within:shadow-primary/10' }
            ${ large ? 'text-base' : 'text-sm' } ${ padding }`">
            <div v-if="(modelValue?.length ?? 0) > 0" class="gap-1 flex flex-wrap items-center -mx-2">
                <div v-for="entry of modelValue">
                    <div class="bg-primary/10 text-primary px-1.5 py-1 -my-1 rounded flex items-center space-x-1.5 whitespace-nowrap">
                        <span>{{ options?.find(option => option.key === entry)?.value }}</span>
                        <i class="fa-regular fa-xmark" @click="removeSelectionFromList($event, entry)"></i>
                    </div>
                </div>
            </div>
            <input v-model="searchTerm" class="appearance-none outline-none focus:outline-none placeholder-gray-400 grow min-w-0 bg-gray-100" placeholder="Suche…" />
        </div>
        <div v-if="searchTerm.length > 0 && searchableOptions.length > 0" class="absolute top-full bg-white shadow-lg py-2 px-3 rounded-md mt-1 w-full max-h-60 overflow-y-scroll z-10">
            <div v-for="option in searchableOptions" :value="option.key" class="hover:bg-gray-100 px-1.5 py-1 -mx-1.5 cursor-pointer rounded" @click="addSelectionToList($event, option.key.toString())">
                {{ option.value }}
            </div>
        </div>
    </div>
</template>
