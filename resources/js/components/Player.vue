<template>
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
    <button class="button green">Valider ma réponse</button>
</template>

<script setup>
    import { computed, reactive, ref } from "vue";
    import { useGameStore } from "../stores/gameStore";
    import { usePlayerStore } from "../stores/playerStore";

    const selectedCards = reactive([]);

    const gameStore = useGameStore();
    const playerStore = usePlayerStore();

    const cardWord = computed(() => {
        return (gameStore.countQuestionHoles == 1) ? "carte" : "cartes";
    });


    function addOrRemoveCard(answer) {
        if (selectedCards.includes(answer)) {
            selectedCards.splice(selectedCards.indexOf(answer), 1);
        } else if(gameStore.countQuestionHoles > selectedCards.length) {
            selectedCards.push(answer);
        }
    }

</script>


<style scoped>
</style>
