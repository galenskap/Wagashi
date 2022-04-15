<template>
    <h2>Tu es <span>dealer</span></h2>

    <div class="question-card empty card" v-html="gameStore.getHTMLQuestion"></div>

    <h2>Réponses :</h2>

    <player-list-waiting></player-list-waiting>

</template>

<script setup>
    import { computed, reactive, ref } from "vue";
    import { useGameStore } from "../stores/gameStore";
    import PlayerListWaiting from "../components/PlayerListWaiting.vue";

    const gameStore = useGameStore();
</script>


<style scoped>
</style>
