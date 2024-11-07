<script setup lang="ts">
import { router } from '@inertiajs/vue3';

defineProps({
    href: String,
    interactable: {
        type: Boolean,
        default: true,
    }
})

/**
 * Checks whether the click should actually navigate to the href or prevent it.
 * Basically if the <td> where the click happened has the attribute `[data-prevent-row-interaction="true]`, it should not navigate.
 */
const shouldNavigateHref = (event: MouseEvent) => {
    return (event.target as HTMLElement|null)?.closest('td[data-prevent-row-interaction="true"]') === null;
}
</script>

<template>
    <tr :class="interactable && 'hover:!text-primary cursor-pointer transition-colors focus:!text-primary'" class="group/table-row outline-none" tabindex="0" @keyup.enter="interactable && href && router.get(href)" @click="interactable && href && shouldNavigateHref($event) && router.get(href)">
        <slot />
    </tr>
</template>
