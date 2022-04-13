<template>
    <player-list :score="true"></player-list>
    <button class="button green" :disabled="disabled">Lancer la partie</button>
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

    const gameStore = useGameStore();
    const shareLink = ref(null);
    const link = window.location.href;

    const disabled = computed(() => {
        return (gameStore.players.length < 3) ? true : false;
    });

    const selectLink = () => {
        shareLink.value.select();
        navigator.clipboard.writeText(shareLink.value.value);
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
