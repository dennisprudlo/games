<script setup lang="ts">
import { PaginationLinks, PaginationMeta } from '@/Types/resources';
import { Link } from '@inertiajs/vue3';
import { PropType } from 'vue';

const props = defineProps({
    links: {
        type: Object as PropType<PaginationLinks>,
        required: true,
    },
    meta: {
        type: Object as PropType<PaginationMeta>,
        required: true,
    },
});

const threshold = 3;
const startPage = Math.max(1, props.meta.current_page - threshold);
const endPage = Math.min(props.meta.last_page, props.meta.current_page + threshold);
const pages = Array.from({ length: endPage - startPage + 1 }, (_, i) => startPage + i);

const generatePageUrl = (url: string, page?: number) => {
    let currentQuery = new URLSearchParams(window.location.search);
    let newUrl = new URL(url);
    currentQuery.forEach((value, key) => {
        if (key !== 'page') {
            newUrl.searchParams.append(key, value);
        }
    });

    if (page) {
        newUrl.searchParams.append('page', page.toString());
    }

    return newUrl.toString();
};
</script>

<template>
    <div v-if="meta.total > 0" class="flex items-center justify-between space-x-5">
        <div class="font-medium text-sm text-gray-400">
            Einträge
            <span class="text-gray-500">{{ meta.from }}</span>
            bis
            <span class="text-gray-500">{{ meta.to }}</span>
            von
            <span class="text-gray-500">{{ meta.total }}</span>
        </div>
        <div v-if="meta.total > meta.per_page" class="flex items-center text-gray-500 space-x-2">
            <div v-if="links.prev" class="flex items-center space-x-1 group">
                <i class="fa-regular fa-chevron-left text-sm" :class="links.prev && 'group-hover:text-primary'"></i>
                <Link :href="generatePageUrl(links.prev)" class="group-hover:text-primary">Zurück</Link>
            </div>
            <div class="flex items-center text-sm">
                <Link :href="generatePageUrl(meta.path, page)" :key="page" v-for="page of pages" class="p-2 size-7 flex items-center justify-center cursor-pointer hover:text-primary" :class="meta.current_page === page && 'bg-primary/20 text-primary rounded'">
                    <span>{{ page }}</span>
                </Link>
            </div>
            <div v-if="links.next" class="flex items-center space-x-1 group">
                <Link :href="generatePageUrl(links.next)" class="group-hover:text-primary">Nächste</Link>
                <i class="fa-regular fa-chevron-right text-sm" :class="links.next && 'group-hover:text-primary'"></i>
            </div>
        </div>
    </div>
</template>
