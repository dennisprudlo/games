<script setup lang="ts">
import { IndexFiltersHandler } from '@/Lib/IndexFiltersHandler';
import { PropType } from 'vue';

defineProps({
    title: String,
    indexFilters: Object as PropType<IndexFiltersHandler>,
    sortable: String,
});
</script>

<template>
    <th :data-sortable="sortable" class="px-4 pt-0 pb-3 first:pl-0 last:pr-0 leading-none font-semibold text-xs text-gray-700 select-none whitespace-nowrap" :class="{
        'cursor-pointer': indexFilters,
        '!text-primary': indexFilters?.isSorted(sortable),
    }" @click="indexFilters?.toggleSort(sortable)">
        <span v-if="title">{{ title }}</span>
        <slot v-else />
        <span v-if="indexFilters" class="ml-1.5">
            <i v-if="indexFilters.isSorted(sortable, 'asc')" class="fa-solid fa-arrow-up"></i>
            <i v-else-if="indexFilters.isSorted(sortable, 'desc')" class="fa-solid fa-arrow-down"></i>
            <i v-else class="fa-regular fa-sort"></i>
        </span>
    </th>
</template>
