<script lang="ts" setup>
defineProps({
    placeholder: String,
    empty: Boolean,
    icon: {
        type: String,
        default: 'fa-edit'
    },
    editable: {
        type: Boolean,
        default: true
    }
});
</script>

<template>
    <component :is="editable ? 'button' : 'div'" :type="editable ? 'button' : null" class="flex justify-between space-x-5 text-left px-2 py-1 -mx-2 !mt-0.5 rounded" :class="editable && 'group hover:bg-gray-100 transition-colors'">
        <div v-if="! $slots.default || empty === true" class="text-gray-400 italic">
            {{ placeholder }}
        </div>
        <div v-else class="line-clamp-6">
            <slot />
        </div>
        <i v-if="editable" class="fa-regular ml-auto leading-6 text-gray-400 group-hover:text-label" :class="icon"></i>
    </component>
</template>
