<script setup lang="ts">
defineProps({
    wrapText: {
        type: Boolean,
        default: false,
    },
    prevent: {
        type: null,
        default: false,
    },
    actions: Boolean,
});
</script>

<template>
    <td :data-prevent-row-interaction="prevent === null || prevent === undefined || prevent === false || (Array.isArray(prevent) && prevent.length === 0) ? 'false' : 'true'" class="[&[data-prevent-row-interaction='true']]:cursor-default px-4 py-2.5 border-t border-dashed first:pl-0 last:pr-0 align-top" :class="{
        'whitespace-nowrap': ! wrapText,
        'align-middle': actions,
    }">
        <slot v-if="! actions" />
        <div v-else="actions" class=" flex justify-end">
            <slot />
        </div>
    </td>
</template>
