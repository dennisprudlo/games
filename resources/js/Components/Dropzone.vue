<script lang="ts" setup>
import { ref } from 'vue';

const props = defineProps({
    id: String,
    icon: {
        type: String,
        default: 'fa-plus',
    },
    placeholder: {
        type: String,
        required: false,
    },
    text: String,
    layout: {
        type: String,
        default: 'size-32',
    },
    accept: Array<String>,
    multiple: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['filesDropped', 'fileDropped']);

const input = ref<HTMLInputElement|null>(null);

const draggingOver = ref(false);

const toggleActive = () => {
    draggingOver.value = !draggingOver.value;
}

const dropFiles = (event: DragEvent) => {
    draggingOver.value = false;

    const files = Array.from(event.dataTransfer?.files || []);

    if (props.multiple) {
        emit('filesDropped', files);
    } else if (files.length > 0) {
        emit('fileDropped', files[0]);
    }

    input.value!.value = '';
}

const selectFiles = (event: Event) => {
    const files = Array.from((event.target as HTMLInputElement).files || []);

    if (props.multiple) {
        emit('filesDropped', files);
    } else if (files.length > 0) {
        emit('fileDropped', files[0]);
    }

    input.value!.value = '';
}
</script>

<template>
    <div
        @dragenter.prevent="toggleActive()"
        @dragleave.prevent="toggleActive()"
        @dragover.prevent
        @drop.prevent="dropFiles($event)"
        :class="(draggingOver && 'bg-gray-200') + ' ' + layout"
        class="relative shrink-0 bg-gray-100 hover:bg-gray-200 transition-colors rounded-md flex items-center justify-center group cursor-pointer">
        <div v-if="placeholder">
            <img :src="placeholder" :alt="text">
        </div>
        <div v-else class="flex items-center space-x-3">
            <i :class="draggingOver ? 'fa-arrow-down-to-line text-label' : icon" class="fa-regular text-2xl text-gray-400 group-hover:text-label transition-colors"></i>
            <span v-if="text" class="text-label text-base">{{ text }}</span>
        </div>
        <label :for="id || 'dropzone-file-input'" class="absolute inset-0 cursor-pointer"></label>
        <input ref="input" :id="id || 'dropzone-file-input'" class="hidden" type="file" :multiple="multiple" @input="selectFiles($event)" :accept="accept?.join(',')" />
    </div>
</template>
