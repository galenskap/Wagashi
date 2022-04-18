<template>
    <template v-if="step == 'proposition'">
        <h2><span>{{ gameStore.getCurrentDealer.pseudo }}</span> dit :</h2>

        <div class="question-card empty card" v-html="gameStore.getHTMLQuestion">
        </div>

        <h2>Choisis <span>{{ gameStore.countQuestionHoles }}</span> {{ cardWord }} :</h2>

        <div class="answers">
            <ul>
                <li class="card answer-card" :class="{ selected : selectedCards.includes(answer)}" v-for="(answer, index) in playerStore.answers" @click="addOrRemoveCard(answer)" :key="index">
                    <span>{{ answer.text }}</span>
                    <span class="number" v-if="selectedCards.includes(answer)">{{ selectedCards.indexOf(answer) +1 }}.</span>
                </li>
            </ul>
        </div>
        <button  class="button green" @click="sendProposition" :disabled="disabled || loading">Valider ma réponse</button>
    </template>
    <template v-if="step == 'waiting'">
        <h2>Merci d'avoir répondu !</h2>
        <p class="sub">Voici une pâtisserie pour patienter.</p>
        <div class="wagashi">
            <img :src="Mochi" alt="">
        </div>
        <h2>On attend qui ?</h2>
        <player-list-waiting></player-list-waiting>
    </template>
</template>

<script setup>
    import { computed, reactive, ref } from "vue";
    import { useGameStore } from "../stores/gameStore";
    import { usePlayerStore } from "../stores/playerStore";
    import PlayerListWaiting from "../components/PlayerListWaiting.vue";
    // TODO randomize the wagashi
    import Mochi from "../../img/mochi.svg";

    /**
     * State
     */
    const selectedCards = reactive([]);
    const step = ref('proposition');
    const gameStore = useGameStore();
    const playerStore = usePlayerStore();
    let loading = ref(false);


    /**
     * Setup
     */

    if(gameStore.playersHavingPropositions.includes(playerStore.id)) {
        step.value = 'waiting';
    }


    /**
     * Computed
     */
    const cardWord = computed(() => {
        return (gameStore.countQuestionHoles == 1) ? "carte" : "cartes";
    });

    const disabled = computed(() => {
        return (selectedCards.length < gameStore.countQuestionHoles );
    });

    /**
     * Methods
     */
    const getSelectedPropositionsIds = () => {
        return selectedCards.map(card => card.id);
    };

    function addOrRemoveCard(answer) {
        if (selectedCards.includes(answer)) {
            selectedCards.splice(selectedCards.indexOf(answer), 1);
        } else if(gameStore.countQuestionHoles > selectedCards.length) {
            selectedCards.push(answer);
        }
    }

    function sendProposition() {
        loading.value = true;
        axios.post(process.env.MIX_API_URL + 'send-proposition', {
            answers: getSelectedPropositionsIds(),
        }, {
            headers: {
                Authorization: token,
            },
        })
        .then(function (response) {
            step.value = 'waiting';
        loading.value = false;

        })
        .catch(function (errors) {
            loading.value = false;
            toast(errors.response.data.message);
        });
    }

</script>


<style scoped>
.wagashi {
    width: 12rem;
    margin: 2rem auto;
}
.sub {
    font-size: 1.7rem;
}
</style>
