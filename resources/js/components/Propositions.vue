<template>
    <h2 v-if="playerStore.id == gameStore.current_dealer">Choisis une proposition</h2>
    <h2 v-else><span>{{ gameStore.getCurrentDealer.pseudo }}</span> doit choisir une proposition</h2>

    <div class="answers">
        <swiper
    :slides-per-view="1"
    :modules="modules"
    :space-between="10"
    :navigation="{hideOnClick: true}"
    :pagination="{ clickable: true }"
    @slideChange="onSlideChange"
    >
            <swiper-slide class="card question-card" v-for="(answer, index) in gameStore.propositions" :key="index">
                <span v-html="augmentedQuestion(answer)"></span>
                <button class="button green" v-if="playerStore.id == gameStore.current_dealer" @click="chooseProposition(answer)">Choisir</button>
            </swiper-slide>
        </swiper>
    </div>
</template>

<script setup>
    import { computed, ref } from "vue";
    import { useGameStore } from "../stores/gameStore";
    import { usePlayerStore } from "../stores/playerStore";

    import { Navigation, Pagination, Scrollbar, A11y } from 'swiper';
    // Import Swiper Vue.js components
    import { Swiper, SwiperSlide } from 'swiper/vue';
    // Import Swiper styles
    import 'swiper/css';
    import 'swiper/css/navigation';
    import 'swiper/css/pagination';

    const gameStore = useGameStore();
    const playerStore = usePlayerStore();
    const modules = [Navigation, Pagination, A11y];



    const augmentedQuestion = (answers) => {
        let augmentedQuestion = "";
        // loop through answers
        answers.forEach(answer => {
            // replace first hole in the question
            augmentedQuestion = gameStore.current_question.replace("##", `<span class="dark">${answer.text}</span>`);
        });
        return augmentedQuestion;
    }
    // Delete  navigation after slide
    const onSlideChange = (swiper) => {
        if(typeof swiper.navigation.nextEl.outerHTML !== '') {
            swiper.navigation.nextEl.classList.add('hidden');
        }
    }

    // Send dealer choice to the server
    const chooseProposition = (answer) => {
        axios.post(process.env.MIX_API_URL + 'send-dealer-choice', {
            player_id: answer[0].player_id,
        }, {
            headers: {
                Authorization: token,
            },
        })
        .then(function (response) {
            // console.log("working");
        })
        .catch(function (errors) {
            toast(errors.response.data.message);
        });
    };
</script>


<style scoped>

</style>
