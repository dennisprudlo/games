<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { PanelTitle } from '.';
import { PropType } from 'vue';
import { PaginationLinks, PaginationMeta } from '@/Types/resources';
import { Pagination } from '@/Components';

defineProps({
    title: String,
    href: String,
    container: String,
    paginationLinks: {
        type: Object as PropType<PaginationLinks>,
        required: false,
    },
    paginationMeta: {
        type: Object as PropType<PaginationMeta>,
        required: false,
    },
});

defineOptions({
    inheritAttrs: false
});

const classes = 'block -mx-4 xs:mx-0 bg-white xs:rounded-lg p-5 shadow shadow-label/10';
</script>

<template>
    <div class="space-y-2" :class="container">
        <div>
            <PanelTitle v-if="title">
                {{ title }}
                <template v-if="$slots.actions" #actions>
                    <slot name="actions"></slot>
                </template>
            </PanelTitle>
            <Link v-if="href" :href="href" v-bind="$attrs">
                <slot />
            </Link>
            <div v-else v-bind="$attrs" :class="classes">
                <slot />
            </div>
        </div>
        <Pagination v-if="paginationLinks && paginationMeta" :links="paginationLinks" :meta="paginationMeta" />
    </div>
</template>
