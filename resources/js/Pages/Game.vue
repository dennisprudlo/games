<script setup lang="ts">
import { computed, PropType } from 'vue';
import { route } from 'ziggy';
import { Link, usePage } from '@inertiajs/vue3';
import { GameResource } from '@/Types/resources';
import { baseUrl } from '@/Lib/Helper';
import { Button, Content, Section, SectionTitle, Tooltip } from '@/Components';

defineProps({
    game: {
        type: Object as PropType<GameResource>,
        required: true,
    },
});

const user = computed(() => usePage().props.auth);
</script>

<template>
    <Link :href="route('dashboard')" class="mb-8 group inline-flex items-start space-x-2 text-lg cursor-pointer hover:text-primary hover:text-xl tracking-wide hover:tracking-widest transition-all">
        <span class="translate-x-0 group-focus:-translate-x-3 group-hover:-translate-x-3 transition-transform duration-300">
            &larr;
        </span>
        <span>{{ $t('app.actions.back') }}</span>
    </Link>

    <Content>
        <div class="flex items-stretch space-x-12">
            <img :src="baseUrl(`images/games/${ game.id }/thumbnail.png`)" :alt="game.title" class="h-full w-60 object-cover rounded-md shadow-md">
            <div class="grow flex flex-col justify-between space-y-5">
                <div>
                    <SectionTitle>
                        {{ game.title }}
                    </SectionTitle>
                    <p class="opacity-70 text-base mt-2">
                        {{ game.description }}
                    </p>
                </div>
                <div class="flex items-end justify-between select-none">
                    <div class="flex items-end space-x-5 text-base">
                        <div class="group">
                            <i class="fa-star text-xl" :class="game.starred ? 'fa-solid text-primary group-hover:fa-regular' : 'fa-regular opacity-70 group-hover:opacity-100 group-hover:text-primary'"></i>
                        </div>
                        <Tooltip :content="game.players.length == 1 ? $choice('games.general.players.tooltip', game.players[0] === 1 ? 1 : 2, { min: game.players[0] }) : $choice('games.general.players.tooltip', 3, { min: game.players[0], max: game.players[1] })">
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid text-xs" :class="game.players.length === 1 && game.players[0] === 1 ? 'fa-user' : 'fa-users'"></i>
                                <span>{{ game.players.length === 1 ? game.players[0] : (game.players[0] + '-' + game.players[1]) }}</span>
                            </div>
                        </Tooltip>
                        <Tooltip :content="game.type.title">
                            <i class="fa-solid" :class="game.type.icon"></i>
                        </Tooltip>
                        <Tooltip v-if="game.requires_authentication" :content="$t('games.general.requires-authentication')">
                            <i class="fa-solid fa-lock"></i>
                        </Tooltip>
                    </div>
                    <div>
                        <Button v-if="game.requires_authentication && user === null">Sign up to play</Button>
                        <button v-else class="bg-primary size-16 rounded-full transition-transform duration-500 hover:scale-125 shadow-lg shadow-primary/30">
                            <i class="fa-solid fa-play text-3xl ml-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <Section v-if="game.trivia.length" title="Trivia" class="divide-y divide-gray-700">
            <div v-for="trivia in game.trivia" class="py-5 first:pt-0 last:pb-0 font-serif italic text-xl opacity-70 relative">
                <span class="text-5xl absolute opacity-50 -left-2">“&nbsp;</span>
                <span class="ml-5">{{ trivia }}</span>
                <span class="text-5xl absolute opacity-50">”&nbsp;</span>
            </div>
        </Section>
    </Content>
</template>
