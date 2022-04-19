<template>
    <div class="popin">
        <div class="close" v-if="!gameStore.game_winner" @click="close"></div>
        <template v-if="gameStore.result_popin_round">

            <h2>La carte choisie est :</h2>

            <div class="question-card  card">
                <augmented-question :question="gameStore.previous_turn.question" :answers="gameStore.previous_turn.answers"></augmented-question>
            </div>

            <h2 v-if="gameStore.game_winner"><span>{{ gameStore.getWinnerPlayer?.pseudo }}</span> gagne la partie !</h2>
            <!--  TODO: Mochi animation ? -->
            <h2 v-else-if="gameStore.result_popin_round"><span>{{ gameStore.getWinnerPlayer?.pseudo }}</span> gagne la point !</h2>
        </template>

        <h2>Scores</h2>

        <player-list :score="true"></player-list>

        <button v-if="!gameStore.game_winner" class="button dark" @click="close">
            Fermer
        </button>
        <button v-else class="button blush" @click="resetAll">
            Une autre partie ?
        </button>
    </div>

</template>

<script setup>
    import { computed, reactive, ref } from "vue";
    import { useGameStore } from "../stores/gameStore";
    import PlayerList from "../components/PlayerList.vue";
    import AugmentedQuestion from "../components/AugmentedQuestion.vue";
    import { usePlayerStore } from "../stores/playerStore";
    import { useRouter } from "vue-router";


    const gameStore = useGameStore();
    const playerStore = usePlayerStore();
    const router = useRouter();

    const close = () => {
        gameStore.result_popin = false;
        gameStore.result_popin_round = false;
    };

    const resetAll = () => {
        playerStore.$reset();
        gameStore.$reset();
        localStorage.removeItem('token');
        router.push("/");

    };
</script>


<style scoped>
.close {
    height: 2rem;
    background: url(../../img/cross.svg) no-repeat right center;
    cursor: pointer;
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
.card {
    flex-shrink: 0;
}
</style>
