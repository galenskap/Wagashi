<template>
    <div class="game">
        <Header color="dark" :title="headTitle"></Header>
        <template v-if="gameStore.current_dealer == 0">
            <lobby></lobby>
        </template>
        <template v-else>
            <propositions v-if="Object.keys(gameStore.propositions).length != 0">
            </propositions>
            <template v-else>
                <dealer v-if="gameStore.current_dealer == playerStore.id">
                </dealer>
                <player v-if="gameStore.current_dealer != playerStore.id">
                </player>
            </template>
        </template>
    </div>
</template>

<script setup>
import { computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import Header from "../components/Header.vue";
import { useGameStore } from "../stores/gameStore";
import { usePlayerStore } from "../stores/playerStore";
import Lobby from "../components/Lobby.vue";
import Player from "../components/Player.vue";
import Dealer from "../components/Dealer.vue";
import Propositions from "../components/Propositions.vue";

    const route = useRoute();
    const gameStore = useGameStore();
    const playerStore = usePlayerStore();

    onMounted(() => {
        // console.log(route.params.gameslug);
        // TODO : get game slug from route and get game data
    });

    const headTitle = computed(() => {
        return (gameStore.current_dealer == 0) ? "Lobby" : null;
    });

</script>
