<template>
    <ul>
        <li v-for="(player, index) in playerList" :key="index">
            <span>{{ player.pseudo }}</span>
            <span v-if="gameStore.playersHavingPropositions.includes(player.id)"><img class="check" :src="Check" alt="" /></span>
        </li>
    </ul>
</template>

<script setup>
    import { useGameStore } from "../stores/gameStore";
    import { usePlayerStore } from "../stores/playerStore";
    import Check from "../../img/check.svg";
    import { computed } from "vue";

    const gameStore = useGameStore();
    const playerStore = usePlayerStore();

    const props = defineProps(['score']);

    const playerList = computed(() => {
        return gameStore.players.filter((player) => {
            return playerStore.id != player.id && gameStore.current_dealer != player.id
        });
    });


</script>

<style scoped>
li {
  counter-increment: counter;
  font-weight: bold;
  font-size: 1.9rem;
  margin-bottom: 0.5rem;
}


.check {
    width: 1.7rem;
    margin-left: 0.5rem;
}

</style>
