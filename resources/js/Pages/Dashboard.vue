<script setup lang="ts">
import { PropType} from 'vue';
import { route} from 'ziggy';
import { Link } from '@inertiajs/vue3';
import { GameResource } from '@/Types/resources';
import { Content, GamePanel, Section} from '@/Components';

defineProps({
    games: {
        type: Object as PropType<Array<GameResource>>,
        required: true,
    },
});
</script>

<template>
    <Content>
        <Section v-if="games.filter(game => game.starred).length" :title="$t('dashboard.favorites.title')">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <Link :href="route('game', game)" v-for="game in games.filter(game => game.starred)" :key="game.id">
                    <GamePanel class="h-full" :game="game" />
                </Link>
            </div>
        </Section>

        <Section :title="$t('dashboard.all-games.title')">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <Link :href="route('game', game)" v-for="game in games" :key="game.id">
                    <GamePanel class="h-full" :game="game" />
                </Link>
            </div>
        </Section>
    </Content>
</template>
