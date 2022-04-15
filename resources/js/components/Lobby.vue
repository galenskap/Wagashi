<template>
    <player-list :score="false"></player-list>
    <button class="button green" :disabled="disabled" @click="launchGame">Lancer la partie</button>
    <form @submit.prevent="createGame">
        <div class="form-group">
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
    import { useGameStore } from "../stores/gameStore";
    import { usePlayerStore } from "../stores/playerStore";

    const gameStore = useGameStore();
    const playerStore = usePlayerStore();
    const shareLink = ref(null);
    const link = window.location.href;
    const token = `Bearer ${localStorage.getItem('token')}`;

    const disabled = computed(() => {
        return (gameStore.players.length < 3 || playerStore.id != gameStore.lobby_owner) ? true : false;
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
            // redirect to game view
            gameStore.slug = response.data.slug;
            router.push("/game/" + gameStore.slug);
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
</style>
