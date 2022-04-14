<template>
    <div class="join-game">
        <Header title="Rejoindre"></Header>
        <div class="form">
            <form @submit.prevent="joinGame">
                <template v-if="step == 1" >
                <div class="form-group">
                    <label for="name">Code de jeu</label>
                    <input placeholder="" type="text" id="gameslug" v-model="gameslug" required>
                </div>
                <div class="form-group">
                    <button type="button" @click="nextStep" class="button green">Suivant</button>
                </div>
                </template>
                <template v-if="step == 2" >
                <div class="form-group">
                    <label for="name">Ton pseudo</label>
                    <input placeholder="Noël Flantier" type="text" id="name" v-model="name" required>
                </div>
                <div class="form-group">
                    <button class="button green">C'est parti !</button>
                </div>
                </template>
            </form>
        </div>
    </div>
</template>


<script setup>
    import { ref } from "@vue/reactivity";
    import { onMounted } from "@vue/runtime-core";
    import Header from "../components/Header.vue";
    import { useGameStore } from "../stores/gameStore";

    /**
     * State
     */
    const gameslug = ref('');
    const name = ref('');
    const step = ref(1);
    const gameStore = useGameStore();

    /**
     * Methods
     */
    const nextStep = () => {
        checkGameSlug(gameslug.value);
    };

    onMounted(() => {
        if(gameStore.slug.length > 0) {
            checkGameSlug();
        }
    });

    const checkGameSlug = (gameslug = false) => {
         axios.post(process.env.MIX_API_URL + 'check-game', {
                gameslug: gameslug || gameStore.slug
            })
            .then(function (response) {
                gameStore.slug = gameslug || gameStore.slug;
                step.value++;
            })
            .catch(function (error) {
                // TODO handle error
                // console.log(error.response.data.errors);
            });
    };
</script>

<style scoped>
.join-game {
    color: var(--blush);
}
header {
    margin-bottom: 3rem;
}
.button {
    margin-top: 2rem;
}
.button:hover {
    box-shadow:inset 0 0 0 2px  var(--mint-green);
}
</style>
