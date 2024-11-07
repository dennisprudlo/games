import { defineStore } from 'pinia'
import { ref } from 'vue';

// TODO
// (() => {
//     window.addEventListener('keydown', event => {
//         if (event.key === 'Escape') {
//             if (openModals.length) {
//                 event.preventDefault();
//                 openModals.pop()?.discard();
//             }
//         }
//     });
// })();

type OpenModalState = {
    discard: () => void;
};

export const useModalStore = defineStore('modal', () => {
    const openModals = ref<Array<OpenModalState>>([]);

    /**
     * Registers a new open dropdown reference
     * @param reference The "open" reference from the registered dropdown
     */
    function push(state: OpenModalState) {
        openModals.value.push(state);
    }

    return { push };
})
