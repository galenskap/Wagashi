<template>
    <ol>
        <li v-for="(player, index) in gameStore.getPlayersByScore" :key="index">
            <span :class="{ blush : playerStore.id == player.id, disconnected: player.connected == undefined || player.connected == false }">{{player.pseudo}}</span>
            <span v-if="player.id == gameStore.lobby_owner"><img class="crown" :src="Crown" alt="" /></span>
            <span class="score" v-if="score"> - {{player.current_score}}pts</span>
            <span class="kick" @click="displayModal(player)" v-if="kick && playerStore.id == gameStore.lobby_owner && player.id != playerStore.id"><img :src="Cross" alt="Bannir"></span>
        </li>
    </ol>
    <Modal :title="modalTitle" v-if="modal" @close="modal = false" @confirm="kickPlayer"></Modal>
</template>

<script setup>
    import { useGameStore } from "../stores/gameStore";
    import { usePlayerStore } from "../stores/playerStore";
    import Modal from "../components/Modal.vue";
    import Crown from "../../img/crown.svg";
    import Cross from "../../img/cross.svg";
import { ref } from "@vue/reactivity";

    const gameStore = useGameStore();
    const playerStore = usePlayerStore();

    const props = defineProps(['score', 'kick']);
    const modal = ref(false);
    const playerToKick = ref(null);
    const modalTitle = ref('');

    const displayModal = (player) => {
        playerToKick.value = player;
        modalTitle.value = `Bannir ${player.pseudo} ?`;
        modal.value = true;
    };

    const kickPlayer = () => {
        axios.post(process.env.MIX_API_URL + 'kick', {
            player_id: playerToKick.value.id,
        }, {
            headers: {
                Authorization: token,
            },
        })
        .catch(function (errors) {
            toast(errors.response.data.message);
        })
        .then(function (response) {
            modal.value = false;
        });
    };

</script>

<style scoped>
ol li {
  counter-increment: counter;
  font-weight: bold;
  font-size: 1.9rem;
  margin-bottom: 0.5rem;
}
ol li:before {
  content: counter(counter) ". ";
  color: var(--blush);
  width: 2rem;
  display: inline-block;
}
ol {
  list-style: none;
  counter-reset: counter;
}
.crown {
    width: 1.7rem;
    margin-left: 0.5rem;
}
.score {
    color: var(--blush);
}
.kick {
    display: inline-block;
    width: 1rem;
    margin-left: 0.5em;
    cursor: pointer;
}
.disconnected {
    color: var(--nickel);
}
</style>
