<script setup lang="ts">
import { useDropdownStore } from '@/Lib/Stores/DropdownStore';
import { Ref, onBeforeUnmount, onMounted, ref } from 'vue';

defineOptions({
    inheritAttrs: false
});

defineProps({
    container: String,
    origin: String,
    content: String,
});

const dropdownStore = useDropdownStore();

const open = ref(false);
const dropdown = ref(null) as Ref<HTMLDivElement|null>;

const closeDropdownOnClickOutside = (event: MouseEvent) => {
    if (! (event.target instanceof Node)) return;
    if (dropdown.value && ! dropdown.value.contains(event.target)) {
        closeDropdown();

        document.removeEventListener('click', closeDropdownOnClickOutside);
    }
};

onMounted(() => dropdown.value = document.querySelector('.dropdown'));
onBeforeUnmount(() => document.removeEventListener('click', closeDropdownOnClickOutside));

const closeDropdown = () => {
    open.value = false;
    dropdownStore.closeAll();
};

const openDropdown = () => {
    open.value = true;
    dropdownStore.closeAll();
    dropdownStore.register(open);
};

const toggleDropdown = (event: Event|undefined = undefined) => {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    if (open.value) {
        closeDropdown();
    } else {
        openDropdown();
    }

    setTimeout(() => {
        if (open.value) {
            document.addEventListener('click', closeDropdownOnClickOutside);
        } else {
            document.removeEventListener('click', closeDropdownOnClickOutside);
        }
    }, 100);
};
</script>

<template>
    <div class="relative" :class="container">
        <button v-bind="$attrs" type="button" class="relative" @click="toggleDropdown($event)">
            <slot name="trigger" />
        </button>

        <Transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-50 scale-90"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-50 scale-90"
        >
            <div v-show="open" role="menu" aria-orientation="vertical" tabindex="-1" :class="`dropdown min-w-[180px] absolute mt-1.5 z-10 ${ origin === 'left' ? 'origin-top-left left-0' : 'origin-top-right right-0' } rounded-md bg-white/50 backdrop-blur border border-gray-100 shadow-lg shadow-label/10 whitespace-nowrap ${ content }`">
                <div class="px-3.5 py-1.5 text-sm space-y-1 text-label flex flex-col items-stretch">
                    <slot :toggle="toggleDropdown" :close="closeDropdown" />
                </div>
            </div>
        </Transition>
    </div>
</template>
