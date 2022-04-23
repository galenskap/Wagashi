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
                    <button class="button green" @click="sendPlayerRegistration">C'est parti !</button>
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
    import { useRouter } from "vue-router";
    import { watch } from 'vue';

    /**
     * State
     */
    let gameslug = ref('');
    // automatically remove caps
    watch(gameslug, async(newGS) => {
        gameslug.value = newGS.toLowerCase();
    });

    const name = ref('');
    const step = ref(1);
    const gameStore = useGameStore();
    const router = useRouter();

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
            // check if player already has a token in localstorage
            if (localStorage.getItem("token")) {
                router.push("/game/" + gameStore.slug);
            } else {
                // if not, go to next step (player registration)
                step.value++;
            }
        })
        .catch(function (errors) {
            // for each error in response.data.errors, display error message
            console.log(errors);
            if (errors.response.data.errors) {
                for(let error in errors.response.data.errors) {
                    toast(errors.response.data.errors[error][0]);
                }
            } else {
                toast(errors.response.data.message);
            }
        });
    };

    const sendPlayerRegistration = () => {
        axios.post(process.env.MIX_API_URL + 'new-player', {
            pseudo: name.value,
            gameslug: gameStore.slug
        })
        .then(function (response) {
            // save token to localstorage
            localStorage.setItem("token", response.data.token);

            // save token to global variable
            token = `Bearer ${response.data.token}`;

            // redirect to lobby
            router.push("/game/" + gameStore.slug);
        })
        .catch(function (errors) {
            // for each error in response.data.errors, display error message
            for(let error in errors.response.data.errors) {
                toast(errors.response.data.errors[error][0]);
            }
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
