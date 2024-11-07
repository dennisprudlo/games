<script setup lang="ts">
import { Button } from '@/Components';
import { useModalStore } from '@/Lib/Stores/ModalStore';
import { ref, watch } from 'vue';

const props = defineProps({
    open: Boolean,
    title: String,
    size: {
        type: String,
        default: 'max-w-md',
    },
    returning: Function,
    returningLabel: String,
});

const emit = defineEmits(['close']);

const modalStore = useModalStore();

const modal = ref<HTMLElement|null>(null);
watch(() => props.open, value => {
    if (value) {
        document.body.style.overflow = 'hidden';
        modalStore.push({
            discard: () => {
                emit('close');
                if (props.returning) props.returning();
            },
        });
    } else {
        document.body.style.overflow = '';
    }
});

const triggerCloseModal = (event: Event) => {
    const target = event.target as HTMLElement|null;
    if (! target) return;

    if (target.closest('[data-modal-child]') === null) {
        emit('close');
    }
};
</script>

<template>
    <Transition enter-active-class="ease-out duration-300" leave-active-class="ease-in duration-200">
        <div class="fixed z-50 inset-0 overflow-y-scroll overflow-x-hidden" v-show="open" @mousedown="triggerCloseModal">
            <Transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0">
                <div v-show="open" class="fixed inset-0 bg-black/40 z-40"></div>
            </Transition>
            <Transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0 translate-y-full scale-90"
                enter-to-class="opacity-100 translate-y-0 scale-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100 translate-y-0 scale-100"
                leave-to-class="opacity-0 translate-y-full scale-95">
                <div v-show="open" class="relative z-50 inset-x-0 sm:inset-x-3 top-10 sm:bottom-10 flex flex-col justify-end sm:block">
                    <div ref="modal" :class="size" class="h-full sm:mx-auto below-sm:max-w-full flex flex-col justify-end sm:justify-start">
                        <div data-modal-child class="bg-white border-gray-100 p-4 pb-0 sm:px-5 rounded-t-md shadow-lg">
                            <h3 class="font-semibold text-lg leading-none mt-1" v-text="title"></h3>
                        </div>
                        <div data-modal-child class="bg-white p-3 sm:p-5 text-sm space-y-2">
                            <slot />
                        </div>
                        <div data-modal-child class="bg-white border-gray-100 p-4 pt-0 sm:px-5 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 sm:justify-end sm:rounded-b-md">
                            <Button v-if="returning" appear secondary @click="$emit('close'); returning()">
                                {{ returningLabel || 'Zurück' }}
                            </Button>
                            <Button v-else appear secondary @click="$emit('close')">
                                Schließen
                            </Button>
                            <slot name="actions" />
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </Transition>
</template>
