<template>
    <h2 v-if="playerStore.id == gameStore.current_dealer">Choisis une proposition</h2>
    <h2 v-else><span>{{ gameStore.getCurrentDealer.pseudo }}</span> doit choisir une proposition</h2>

    <div class="answers">
        <ul>
            <li class="card question-card" v-for="(answer, index) in gameStore.propositions" :key="index">
                <span v-html="augmentedQuestion(answer)"></span>
                <button class="button green" v-if="playerStore.id == gameStore.current_dealer" @click="chooseProposition">Choisir</button>
            </li>
        </ul>
    </div>
</template>

<script setup>
    import { computed, ref } from "vue";
    import { useGameStore } from "../stores/gameStore";
    import { usePlayerStore } from "../stores/playerStore";

    const gameStore = useGameStore();
    const playerStore = usePlayerStore();

    const augmentedQuestion = (answers) => {
        let augmentedQuestion = "";
        // loop through answers
        answers.forEach(answer => {
            // replace first hole in the question
            augmentedQuestion = gameStore.current_question.replace("##", `<span class="dark">${answer.text}</span>`);
        });
        return augmentedQuestion;
    }
</script>


<style scoped>
</style>
