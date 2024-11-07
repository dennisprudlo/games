<script lang="ts" setup>
import { Ref, ref } from 'vue';

defineProps({
    id: String,
    invalid: Boolean,
    modelValue: String,
});

defineOptions({
    inheritAttrs: false
});

const emit = defineEmits(['update:modelValue', 'change', 'paste']);

const container = ref(null) as Ref<HTMLDivElement|null>;
const code = ['', '', '', '', '', ''];
const validCharacters = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

/**
 * Handles the paste event when the user pastes a code into any of the given input fields.
 * @param event The paste event.
 */
const handlePaste = (event: Event) => {
    if (! (event instanceof ClipboardEvent)) return;
    event.preventDefault();

    //
    // A paste happened, so we clear the code array (source of truth)
    code.forEach((_, index) => code[index] = '');

    //
    // Iterate throught the characters in the clipboard and replace the code array (source of truth)
    event.clipboardData?.getData('text/plain').trim().substring(0, code.length).split('').forEach((character, index) => {
        if (index >= code.length) return;
        if (! validCharacters.includes(character)) return;

        code[index] = character;
    });

    //
    // Update the input fields in the UI
    updateInputs();

    dispatchCode('paste');
}

/**
 * Handles a keyup event when the user types a character into any of the given input fields.
 * @param event The keyup event.
 * @param index The index of the input field in the UI.
 */
const handleKeyup = (event: Event, index: number) => {
    if (! (event instanceof KeyboardEvent)) return;
    event.preventDefault();

    const isBackspace = event.key === 'Backspace';
    if (! validCharacters.includes(event.key) && ! isBackspace) return;

    const input = event.target as HTMLInputElement;

    if (! isBackspace) {
        input.value = event.key;

        if (index < code.length - 1) {
            focusInput(index + 1);
        }
    }

    if (isBackspace && index > 0) {
        input.value = '';
        focusInput(index - 1);
    }

    dispatchCode('change');
}

/**
 * Collects the code from the input fields in the UI and updates the code array (source of truth).
 */
const collectInputs = (event: Event|undefined = undefined) => {
    container.value?.querySelectorAll('input').forEach((input, index) => {
        if (index >= code.length) return;

        code[index] = input.value.trim().substring(0, 1);
    });

    //
    // An untrusted event means that something else changed the value of the input field (not the user, but probably a authenticator app).
    if (event && ! event.isTrusted && code.join('').length === 6) {
        dispatchCode('paste');
    }
}

/**
 * Updates the input fields in the UI with the code array (source of truth).
 */
const updateInputs = () => {

    //
    // Checks at which index the code array has the first invalid character
    const invalidCharAt = code.findIndex((character) => ! validCharacters.includes(character));

    container.value?.querySelectorAll('input').forEach((input, index) => {
        if (index >= code.length) return;

        input.value = validCharacters.includes(code[index]) ? code[index] : '';

        if (invalidCharAt === -1 || invalidCharAt > index) {
            input.focus();
        }
    });
}

/**
 * Focuses a specific input field at a given index.
 * @param index The index of the input field in the UI.
 */
const focusInput = (index: number) => {
    (container.value?.querySelector(`input[data-index="${ index }"]`) as HTMLInputElement|null)?.focus();
}

/**
 * Dispatches the code to the parent component.
 * @param event The event name to use when emitting.
 */
const dispatchCode = (event: 'change'|'paste') => {
    collectInputs();

    const joined = code.join('');
    emit('update:modelValue', joined);
    emit(event, joined);
}
</script>

<template>
    <div class="grid grid-cols-6 gap-2" ref="container">
        <input v-for="(_, index) in code.length" :key="index"
            type="text"
            maxlength="1"
            :id="`${ id }_${ index }`"
            v-bind="$attrs"
            :data-index="index"
            :autofocus="index === 0"
            autocomplete="one-time-code"
            @paste="handlePaste($event)"
            @keyup="handleKeyup($event, index)"
            @input="collectInputs($event)"
            class="h-16 text-2xl text-center rounded-md"
            :class="`
                ${ invalid ? 'ring-2 ring-danger shadow-lg shadow-danger/10' : 'focus:ring-2 ring-primary focus:shadow-lg focus:shadow-primary/10' }
                bg-gray-100 outline-none placeholder-gray-400 transition-shadow`"
            />
    </div>
</template>
