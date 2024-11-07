<script lang="ts" setup>
import { router } from '@inertiajs/vue3';

defineOptions({
    inheritAttrs: false
});

const props = defineProps({
    processing: Boolean,
    secondary: Boolean,
    href: String,
    large: Boolean,
    asLink: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
})

const colors = () => {
    if (props.secondary) return `text-gray-600 ring-gray-600 bg-gray-600/5 ${ ! props.disabled && ! props.processing && 'hover:bg-gray-600/10' }`;
    return `text-white ring-white bg-primary ${ ! props.disabled && ! props.processing && 'hover:bg-primary-600' }`;
};

const sizing = (() => {
    if (props.large) return 'px-4 py-2.5 text-base font-semibold';
    return 'px-3 py-2 text-sm font-semibold';
})();

const handleButtonClick = (event: Event) => {
    if (props.asLink) return;

    if (props.href) {
        event.preventDefault();
        router.visit(props.href);
    }
};
</script>

<template>
    <component :is="asLink ? 'a' : 'button'" :href="asLink ? href : null" @click="handleButtonClick($event)" v-bind="$attrs" :class="`rounded-md flex items-center justify-center select-none outline-none focus:ring-2 transition-colors ${ colors() } ${ sizing } ${ disabled ? 'cursor-not-allowed opacity-60' : (processing ? 'cursor-wait opacity-60' : 'cursor-pointer') }`" :disabled="disabled || processing">
        <svg v-if="processing" fill="none" viewBox="0 0 24 24" :class="`animate-spin ${ large ? '-ml-1 mr-2.5 size-4' : '-ml-0.5 mr-1.5 size-3' }`" xmlns="http://www.w3.org/2000/svg">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <slot />
    </component>
</template>
