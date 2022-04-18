<template>
    <ol>
        <li v-for="(player, index) in gameStore.players" :key="index">
            <span :class="{ blush : playerStore.id == player.id }">{{player.pseudo}}</span>
            <span v-if="player.id == gameStore.lobby_owner"><img class="crown" :src="Crown" alt="" /></span>
            <span class="score" v-if="score"> - {{player.current_score}}pts</span>
        </li>
    </ol>
</template>

<script setup>
    import { useGameStore } from "../stores/gameStore";
    import { usePlayerStore } from "../stores/playerStore";
    import Crown from "../../img/crown.svg";

    const gameStore = useGameStore();
    const playerStore = usePlayerStore();

    const props = defineProps(['score']);

</script>

<style scoped>
ol li {
  counter-increment: counter;
  font-weight: bold;
  font-size: 1.9rem;
  margin-bottom: 0.5rem;
}
ol li:before {
  content: counter(counter) ". ";
  color: var(--blush);
  width: 2rem;
  display: inline-block;
}
ol {
  list-style: none;
  counter-reset: counter;
}
.crown {
    width: 1.7rem;
    margin-left: 0.5rem;
}
.score {
    color: var(--blush);
}
</style>
