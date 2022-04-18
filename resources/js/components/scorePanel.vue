<template>
    <div class="popin">
        <div class="close" @click="gameStore.result_popin = false"></div>
        <template v-if="round_score">

            <h2>La carte choisie est :</h2>

            <div class="question-card  card">
                <augmented-question :question="gameStore.previous_turn.question" :answers="gameStore.previous_turn.answers"></augmented-question>
            </div>

            <h2><span>{{ gameStore.getWinnerPlayer?.pseudo }}</span> gagne le point !</h2>
        </template>

        <h2>Scores</h2>

        <player-list :score="true"></player-list>

        <button class="button dark" @click="gameStore.result_popin = false">
            Fermer
        </button>
    </div>

</template>

<script setup>
    import { computed, reactive, ref } from "vue";
    import { useGameStore } from "../stores/gameStore";
    import PlayerList from "../components/PlayerList.vue";
    import AugmentedQuestion from "../components/AugmentedQuestion.vue";

    const props = defineProps({
        round_score: {
            type: Boolean,
            default: false,
        },
    });

    const gameStore = useGameStore();
</script>


<style scoped>
.close {
    height: 2rem;
    background: url(../../img/cross.svg) no-repeat right center;
}
.popin {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: var(--mint-green);
    z-index: 9999;
    padding: 1rem;
    overflow-y: scroll;
    bottom: 0;
    display: flex;
    flex-direction: column;
}
.button {
    margin-top: auto;
}
</style>
