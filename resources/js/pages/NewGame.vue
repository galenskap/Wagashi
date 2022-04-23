<template>
    <div class="new-game">
        <Header title="Nouvelle partie"></Header>
        <div class="form">
            <form @submit.prevent="createGame">
                <div class="form-group">
                    <label for="name">Ton pseudo</label>
                    <input placeholder="Noël Flantier" type="text" id="name" v-model="name" required>
                </div>
                <div class="form-group">
                    <label for="name">Pack de cartes</label>
                    <div class="select-wrapper">
                        <select v-model="selectedPack" required>
                            <option v-for="pack in packs" :key="pack" :value="pack">{{pack}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">Points pour gagner</label>
                    <input type="number" id="points" v-model="points" min="1" max="20" required>
                </div>
                <div class="form-group">
                    <button class="button green" @click="sendGameRegistration">C'est parti !</button>
                </div>
            </form>
        </div>
    </div>
</template>


<script setup>
    import { ref } from "@vue/reactivity";
    import { onMounted } from "@vue/runtime-core";
    import Header from "../components/Header.vue";
    import { useRouter } from "vue-router";

    /**
     * State
     */
    const name = ref('');
    const selectedPack = ref('');
    const points = ref(5);
    const packs = ref([]);
    const router = useRouter();

    onMounted(() => {
         // Get the packs from API
        axios.get(process.env.MIX_API_URL + 'cardpacks')
        .then(function (response) {
            // add response to local store
            packs.value.push(...response.data);
        })
        .catch(function (errors) {
            // for each error in response.data.errors, display error message
            for(let error in errors.response.data.errors) {
                toast(errors.response.data.errors[error][0]);
            }
        });
    });

    const sendGameRegistration = () => {
        axios.post(process.env.MIX_API_URL + 'new-game', {
            owner_pseudo: name.value,
            cardpack: selectedPack.value,
            score_goal: points.value
        })
        .then(function (response) {
            // save token to localstorage
            localStorage.setItem("token", response.data.token);

            // Save the token in the global variable
            token = `Bearer ${response.data.token}`;

            // redirect to lobby
            router.push("/game/" + response.data.slug);
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
.new-game {
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
.select-wrapper {
    position: relative;
}
.select-wrapper::after {
    content: '';
    width: 2rem;
    height: 2rem;
    background-image: url(../../img/next.svg);
    position: absolute;
    display: block;
    transform: rotate(90deg);
    background-repeat: no-repeat;
    right: 1rem;
    top: 1rem;
    pointer-events: none;
}
</style>
