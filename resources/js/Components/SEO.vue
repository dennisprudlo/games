<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineProps({
    title: String,
    description: String,
    keywords: String,
    previewUrl: String,
});
</script>

<template>
    <Head>
        <title>{{ title ? `${title} | Pangoon Development` : 'Pangoon Development' }}</title>
        <meta v-if="description" head-key="description" name="description" :content="description">
        <meta v-if="keywords" head-key="keywords" name="keywords" :content="keywords">

        <meta head-key="og:site_name" property="og:site_name" content="Pangoon Development" />
        <meta v-if="title" head-key="og:title" property="og:title" :content="title" />
        <meta v-if="description" head-key="og:description" property="og:description" :content="description" />
        <meta v-if="previewUrl" head-key="og:image" property="og:image" :content="previewUrl" />
        <meta head-key="og:type" property="og:type" content="website" />

        <meta head-key="twitter:card" name="twitter:card" content="summary_large_image" />
        <meta v-if="title" head-key="twitter:title" name="twitter:title" :content="title" />
        <meta v-if="description" head-key="twitter:description" name="twitter:description" :content="description" />
        <meta v-if="previewUrl" head-key="twitter:image" name="twitter:image" :content="previewUrl" />
        <meta head-key="twitter:site" name="twitter:site" content="Pangoon Development" />
        <meta head-key="twitter:creator" name="twitter:creator" content="Pangoon Development" />

        <slot />
    </Head>
</template>
