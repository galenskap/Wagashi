<template>
    <div class="game">
        <Header color="dark" title="Lobby"></Header>

    </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useRoute } from "vue-router";
import Header from "../components/Header.vue";

    const route = useRoute( );

    onMounted(() => {
        // console.log(route.params.gameslug);
        // TODO : get game slug from route and get game data
    });

</script>
