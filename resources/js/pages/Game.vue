<template>
    <div class="game">
        <Header color="dark" :title="headTitle"></Header>
        <template v-if="loading">
            <!-- TODO: animation -->
            Préparation de la pâte de riz...
        </template>
        <template v-else>
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
        </template>
        <transition name="slide">
            <score-panel v-if="gameStore.result_popin"></score-panel>
        </transition>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import Header from "../components/Header.vue";
import { useGameStore } from "../stores/gameStore";
import { usePlayerStore } from "../stores/playerStore";
import Lobby from "../components/Lobby.vue";
import Player from "../components/Player.vue";
import Dealer from "../components/Dealer.vue";
import Propositions from "../components/Propositions.vue";
import ScorePanel from "../components/ScorePanel.vue";
import { connectGeneral, connectPlayer, setupBroadcast } from "../broadcasting";

    const route = useRoute();
    const router = useRouter();
    const gameStore = useGameStore();
    const playerStore = usePlayerStore();
    const loading = ref(true);

    onMounted(() => {

        // if no token, save the slug and redirect to join-game page
        if(!localStorage.getItem("token")) {
            gameStore.slug = route.params.gameslug;
            router.push("/join-game");

        // if token, get game data and player informations
        } else  {
            // Check if the token matches the game, and if the game is still running
            axios.get(process.env.MIX_API_URL + 'check-game/' + route.params.gameslug,
            {
                headers: {
                    Authorization: token,
                }
            }).then(function (response) {
                getGameData();
            }).catch(function (errors) {
                // if game is over, remove token and go to the homepage (with error display)
                if(errors.response.status == 410) {
                    localStorage.removeItem("token");
                    router.push("/");
                    toast(errors.response.data.message);
                }
                // if token does not match the game requested in the url, redirect to the join page game
                // (no error display)
                else if(errors.response.status == 403) {
                    localStorage.removeItem("token");
                    gameStore.slug = route.params.gameslug;
                    router.push("/join-game");
                }
            });
        }
    });

    const getGameData = () =>  {
        axios.get(process.env.MIX_API_URL + 'get-data', {
            headers: {
                Authorization: token,
            },
        })
        .then(function (response) {
            // put all game data into the gamestore
            gameStore.id = response.data.game.id;
            gameStore.lobby_owner = response.data.game.lobby_owner;
            gameStore.current_dealer = response.data.game.current_dealer ? response.data.game.current_dealer.id : 0;
            gameStore.current_question = response.data.game.current_question ? response.data.game.current_question.text : '';
            gameStore.players = response.data.game.players;
            gameStore.propositions = response.data.propositions || {};
            gameStore.playersHavingPropositions = response.data.playersHavingPropositions || [];

            // put all player data into the playerstore
            playerStore.id = response.data.player.id;
            playerStore.answers = response.data.player.answers;

            // Connect to websocket
            setupBroadcast();
            connectGeneral(gameStore.id);
            connectPlayer(playerStore.id);

            // set loading to false
            loading.value = false;

        })
        .catch(function (errors) {
            toast(errors.response.data.message);
        });
    }
    const headTitle = computed(() => {
        return (gameStore.current_dealer == 0 && !loading.value) ? "Lobby" : null;
    });

</script>
