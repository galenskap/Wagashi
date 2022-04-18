<template>
    <span v-html="augmentedQuestion()"></span>
</template>

<script setup>
    const props = defineProps(['question', 'answers']);

    const augmentedQuestion = () => {
        let augmentedQuestion = props.question;
        // loop through answers
        props.answers.forEach(answer => {
            // replace first hole in the question
            augmentedQuestion = augmentedQuestion.replace("##", `<span class="dark">${answer.text}</span>`);
        });
        return augmentedQuestion;
    }
</script>

<style scoped>

</style>
