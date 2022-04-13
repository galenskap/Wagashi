<template>
    <div class="game">
        <Header color="dark" :title="headTitle"></Header>
        <template v-if="gameStore.dealer == 0">
            <lobby></lobby>
        </template>
    </div>
</template>

<script setup>
import { computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import Header from "../components/Header.vue";
import { useGameStore } from "../stores/gameStore";
import Lobby from "../components/Lobby.vue";

    const route = useRoute();
    const gameStore = useGameStore();
    onMounted(() => {
        // console.log(route.params.gameslug);
        // TODO : get game slug from route and get game data
    });

    const headTitle = computed(() => {
        return (gameStore.dealer == 0) ? "Lobby" : null;
    });

</script>
