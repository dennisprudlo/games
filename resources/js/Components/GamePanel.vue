<script setup lang="ts">
import { baseUrl } from '@/Lib/Helper';
import { GameResource } from '@/Types/resources';
import { PropType } from 'vue';
import { Tooltip } from '@/Components';

const props = defineProps({
    game: {
        type: Object as PropType<GameResource>,
        required: true,
    },
});

const players = props.game.players.length === 1 ? props.game.players[0] : (props.game.players[0] + '-' + props.game.players[1]);

const toggleStar = (event: MouseEvent) => {
    event.preventDefault();
    props.game.starred = !props.game.starred;
};
</script>

<template>
    <div class="relative">
        <img :src="baseUrl(`images/games/${ game.id }/thumbnail.png`)" :alt="game.title" class="absolute size-full z-0 object-cover rounded-md">
        <div class="h-full flex flex-col bg-gray-900/80 backdrop-blur-lg rounded-md z-10 relative hover:scale-[1.02] transition-transform duration-300 cursor-pointer shadow-lg">
            <img :src="baseUrl(`images/games/${ game.id }/thumbnail.png`)" :alt="game.title" class="h-44 w-full object-cover origin-top-left rounded-t-md shadow-md">
            <div class="grow flex flex-col justify-between p-4 space-y-3">
                <div>
                    <h3 class="text-lg font-semibold">
                        {{ game.title }}
                    </h3>
                    <p class="text-sm opacity-70 mt-0.5">
                        {{ game.short_description }}
                    </p>
                </div>
                <div class="flex items-end justify-between select-none text-xs">
                    <div class="flex items-center space-x-4 opacity-70">
                        <Tooltip :content="game.players.length == 1 ? $choice('games.general.players.tooltip', game.players[0] === 1 ? 1 : 2, { min: game.players[0] }) : $choice('games.general.players.tooltip', 3, { min: game.players[0], max: game.players[1] })">
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid" :class="game.players.length === 1 && game.players[0] === 1 ? 'fa-user' : 'fa-users'"></i>
                                <span class="font-semibold">{{ players }}</span>
                            </div>
                        </Tooltip>
                        <Tooltip :content="game.type.title">
                            <i class="fa-solid" :class="game.type.icon"></i>
                        </Tooltip>
                        <Tooltip v-if="game.requires_authentication" :content="$t('games.general.requires-authentication')">
                            <i class="fa-solid fa-lock"></i>
                        </Tooltip>
                    </div>
                    <div class="group" @click="toggleStar($event)">
                        <i class="fa-star text-base" :class="game.starred ? 'fa-solid text-primary group-hover:fa-regular' : 'fa-regular opacity-70 group-hover:opacity-100 group-hover:text-primary'"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
