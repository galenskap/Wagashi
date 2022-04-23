<template>
    <div class="popin" :class="{winner: gameStore.game_winner}">
        <div class="close" v-if="!gameStore.game_winner" @click="close"></div>
        <template v-if="gameStore.result_popin_round">

            <div class="winner_announce" v-if="gameStore.game_winner"><span>{{ gameStore.getWinnerPlayer?.pseudo }}</span> gagne la partie !</div>
            <img v-if="gameStore.game_winner" class="victory" :src="Victory" alt="Mochi heureux d'avoir gagné">
            <h2>La carte choisie est :</h2>

            <div class="question-card  card">
                <augmented-question :question="gameStore.previous_turn.question" :answers="gameStore.previous_turn.answers"></augmented-question>
            </div>


            <!--  TODO: Mochi animation ? -->
            <h2 v-if="gameStore.result_popin_round && !gameStore.game_winner"><span>{{ gameStore.getWinnerPlayer?.pseudo }}</span> gagne le point !</h2>
        </template>

        <h2>Scores</h2>

        <player-list :score="true" :kick="true"></player-list>

        <div class="bottom-actions">
            <span v-if="!gameStore.result_popin_round && !gameStore.result_game_winner" class="quit" @click="disconnect">Quitter la partie &times;</span>

            <button v-if="!gameStore.game_winner" class="button dark" @click="close">
                Fermer
            </button>
            <button v-else class="button blush" @click="resetAll">
                Une autre partie ?
            </button>
        </div>
    </div>

</template>

<script setup>
    import { computed, reactive, ref } from "vue";
    import { useGameStore } from "../stores/gameStore";
    import PlayerList from "../components/PlayerList.vue";
    import AugmentedQuestion from "../components/AugmentedQuestion.vue";
    import { usePlayerStore } from "../stores/playerStore";
    import { useRouter } from "vue-router";
    import { resetAll } from "../stores/helper";
    import Victory from "../../img/victory.svg";

    const gameStore = useGameStore();
    const playerStore = usePlayerStore();
    const router = useRouter();

    const close = () => {
        gameStore.result_popin = false;
        gameStore.result_popin_round = false;
    };


    const disconnect = () => {
        // disconnect from the game
        axios.get(process.env.MIX_API_URL + 'disconnect',
        {
            headers: {
                Authorization: token
            }
        }).then(function (response) {
            resetAll();
        }).catch(function (errors) {
            console.log(errors);
        });
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
.popin.winner {
    background: var(--champagne-pink);
}
.winner_announce {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: var(--champagne-pink);
    background: var(--raisin-black);
    padding: 2rem 1rem;
    border-radius: .25rem;
}

.winner_announce span {
    color: var(--mint-green);
}
.bottom-actions {
    margin-top: auto;
}
.quit {
    text-align: center;
    display: inline-block;
    width: 100%;
    padding-bottom: 1rem;
    text-decoration: underline;
    font-size: 1.2rem;
    cursor: pointer;
}
.card {
    flex-shrink: 0;
}
.victory {
    max-width: 400px;
    margin-bottom: 1rem;
}
</style>
