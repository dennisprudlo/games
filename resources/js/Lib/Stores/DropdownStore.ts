import { defineStore } from 'pinia'
import { Ref, ref } from 'vue';

export const useDropdownStore = defineStore('dropdown', () => {
    const openDropdowns = ref<Array<Ref<boolean>>>([]);

    /**
     * Registers a new open dropdown reference
     * @param reference The "open" reference from the registered dropdown
     */
    function register(reference: Ref<boolean>) {
        openDropdowns.value.push(reference);
    }

    /**
     * Closes all open dropdowns.
     */
    function closeAll() {
        openDropdowns.value.forEach(reference => reference.value = false);
        openDropdowns.value = [];
    }

    return { register, closeAll };
})
