<script setup lang="ts"></script>

<template>
    <div class="overflow-x-auto">
        <table class="text-left text-sm w-full">
            <thead>
                <tr>
                    <slot name="head" />
                </tr>
            </thead>
            <tbody>
                <slot />
            </tbody>
        </table>
    </div>
</template>
