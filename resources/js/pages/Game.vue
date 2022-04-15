<template>
    <div class="game">
        <Header color="dark" :title="headTitle"></Header>
        <template v-if="gameStore.current_dealer == 0">
            <lobby></lobby>
        </template>
        <template v-else>
            <propositions v-if="Object.keys(gameStore.propositions).length != 0">
            </propositions>
            <template v-else>
                <dealer v-if="gameStore.current_dealer == playerStore.id">
                </dealer>
                <player v-if="gameStore.current_dealer != playerStore.id">
                </player>
            </template>
        </template>
    </div>
</template>

<script setup>
import { computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import Header from "../components/Header.vue";
import { useGameStore } from "../stores/gameStore";
import { usePlayerStore } from "../stores/playerStore";
import Lobby from "../components/Lobby.vue";
import Player from "../components/Player.vue";
import Dealer from "../components/Dealer.vue";
import Propositions from "../components/Propositions.vue";

    const route = useRoute();
    const router = useRouter();
    const gameStore = useGameStore();
    const playerStore = usePlayerStore();
    const token = `Bearer ${localStorage.getItem('token')}`;

    onMounted(() => {

        // if no token, save the slug and redirect to join-game page
        if(!localStorage.getItem("token")) {
            gameStore.slug = route.params.gameslug;
            router.push("/join-game");

        // if token, get game data and player informations
        } else {
            axios.get(process.env.MIX_API_URL + 'get-data', {
                headers: {
                    Authorization: token,
                },
            })
            .then(function (response) {
                // put all game data into the gamestore
                console.log(response.data);
                gameStore.id = response.data.game.id;
                gameStore.lobby_owner = response.data.game.lobby_owner;
                gameStore.current_dealer = response.data.game.current_dealer ? response.data.game.current_dealer.id : 0;
                gameStore.current_question = response.data.game.current_question ? response.data.game.current_question.text : '';
                gameStore.players = response.data.game.players;
                gameStore.propositions = response.data.game.propositions || {};

                // put all player data into the playerstore
                playerStore.id = response.data.player.id;
                playerStore.answers = response.data.player.answers;
            })
            .catch(function (errors) {
                // for each error in response.data.errors, display error message
                for(let error in errors.response.data.errors) {
                    toast(errors.response.data.errors[error][0]);
                }
            });
        }
    });

    const headTitle = computed(() => {
        return (gameStore.current_dealer == 0) ? "Lobby" : null;
    });

</script>
