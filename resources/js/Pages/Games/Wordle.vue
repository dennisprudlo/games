<script setup lang="ts">
import { onBeforeUnmount, onMounted, PropType, ref} from 'vue';
import { GameResource } from '@/Types/resources';
import { Content } from '@/Components';
import { range } from '@/Lib/Helper';

defineProps({
    wordle: {
        type: Object as PropType<Array<GameResource>>,
        required: true,
    },
});

const rows = 6;
const currentRow = ref(0);
const words = ref(range(0, rows).map(() => ''));

const usedLetters = ref({
    correct: new Set<string>('A'),
    exist: new Set<string>('C'),
    wrong: new Set<string>(['I', 'O', 'E', 'R', 'F', 'G', 'X', 'V']),
});

/**
 * Submits a letter to the game
 * @param letter The letter to submit
 */
const submitLetter = (letter: string) => {
    if (! letter.match(/^[A-Z]$/)) return;
    if (words.value[currentRow.value].length >= 5) return;

    words.value[currentRow.value] += letter;
}

/**
 * Deletes the last letter from the word.
 */
const deleteLetter = () => words.value[currentRow.value] = words.value[currentRow.value].slice(0, -1);

/**
 * Submits the word to the game.
 */
const submitWord = () => {
    if (! words.value[currentRow.value].match(/^[A-Z]{5}$/)) return;

    console.log('Word submitted');

    currentRow.value = Math.max(0, Math.min(currentRow.value + 1, 5));
}

/**
 * Generates the classes for a given letter box.
 * @param row The row index
 * @param index The index of the letter
 */
const letterClasses = (row: number, index: number) => {
    let classes = 'size-16 border-2 flex items-center justify-center text-4xl font-semibold';

    const letter = words.value[row][index] || undefined;
    if (! letter) classes += ' border-gray-700';
    else classes += ' border-gray-500';

    return classes;
}

/**
 * Generates the classes for a given keyboard letter.
 * @param letter The letter to generate the classes for
 */
const keyboardKeyClasses = (letter?: string) => {
    let classes = 'bg-gray-700 flex items-center justify-center rounded-md font-semibold text-lg px-5 py-3.5 cursor-pointer hover:scale-110 transition-transform';
    if (! letter) return classes;

    if (usedLetters.value.correct.has(letter)) classes += ' bg-green-500/70';
    else if (usedLetters.value.exist.has(letter)) classes += ' bg-yellow-500/70';
    else if (usedLetters.value.wrong.has(letter)) classes += ' opacity-50';

    return classes;
}

/**
 * Handles the keyboard events.
 */
const keyboardListener = (event: KeyboardEvent) => {
    if (event.key === 'Backspace') {
        deleteLetter();
    } else if (event.key === 'Enter') {
        submitWord();
    } else if (event.key.toUpperCase().match(/^[A-Z]$/)) {
        submitLetter(event.key.toUpperCase());
    }
};

onMounted(() => document.addEventListener('keydown', keyboardListener));
onBeforeUnmount(() => document.removeEventListener('keydown', keyboardListener));
</script>

<template>
    <Content class="flex flex-col items-center">
        <div class="grid grid-cols-5 gap-1 select-none">
            <div v-for="row in range(0, rows - 1)" class="contents">
                <div v-for="index in range(0, 4)" :class="letterClasses(row, index)">
                    {{ words[row][index] }}
                </div>
            </div>
        </div>

        <div class="flex flex-col items-center space-y-2 select-none">
            <div class="flex space-x-2">
                <div v-for="letter in ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P']" @click="submitLetter(letter)" :class="keyboardKeyClasses(letter)">
                    {{ letter }}
                </div>
            </div>
            <div class="flex space-x-2">
                <div v-for="letter in ['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L']" @click="submitLetter(letter)" :class="keyboardKeyClasses(letter)">
                    {{ letter }}
                </div>
            </div>
            <div class="flex space-x-2">
                <div @click="submitWord()" class="!w-auto" :class="keyboardKeyClasses()">ENTER</div>
                <div v-for="letter in ['Z', 'X', 'C', 'V', 'B', 'N', 'M']" @click="submitLetter(letter)" :class="keyboardKeyClasses(letter)">
                    {{ letter }}
                </div>
                <div @click="deleteLetter()" class="w-auto" :class="keyboardKeyClasses()">
                    <i class="fa-regular fa-delete-left text-xl"></i>
                </div>
            </div>
        </div>
    </Content>
</template>
