<template>
    <player-list :score="false"></player-list>
    <button v-if="playerStore.id == gameStore.lobby_owner" class="button green" :disabled="disabled" @click="launchGame">Lancer la partie</button>
    <p class="waiting" v-else>On attend que <span class="blush">{{ gameStore.getLobbyOwner.pseudo }}</span> démarre la partie !</p>
    <form @submit.prevent="createGame">
        <div  class="form-group">
            <input ref="shareLink"  type="text" id="name" :value="link">
        </div>
        <div class="form-group">
            <button @click="selectLink" class="button blush">Copier l'invitation</button>
        </div>
    </form>
</template>

<script setup>
    import PlayerList from "../components/PlayerList.vue";
    import { computed, ref } from "vue";
    import { useRouter } from "vue-router";
    import { useGameStore } from "../stores/gameStore";
    import { usePlayerStore } from "../stores/playerStore";

    const gameStore = useGameStore();
    const playerStore = usePlayerStore();
    const shareLink = ref(null);
    const router = useRouter();
    const link = window.location.href;
    const token = `Bearer ${localStorage.getItem('token')}`;

    const disabled = computed(() => {
        return (gameStore.players.length < 3) ? true : false;
    });

    const selectLink = () => {
        shareLink.value.select();
        navigator.clipboard.writeText(shareLink.value.value);
    };

    const launchGame = () => {
        axios.get(process.env.MIX_API_URL + 'launch-game', {
            headers: {
                Authorization: token,
            },
        })
        .then(function (response) {
            // set current_dealer and current_question
            gameStore.current_dealer = response.data.dealer.id;
            gameStore.current_question = response.data.question.text;
        })
        .catch(function (errors) {
            toast(errors.response.data.message);
        });
    };
</script>


<style scoped>
.button.green {
    margin-top: 2rem;
}
form {
    margin-top: 2rem;
}
.waiting {
    font-size: 1.5rem;
    margin-top: 3rem;
}
</style>
