<template>
  <div class="home">
    <div class="logo">
      <img class="home-logo" :src="LogoImage" alt="" />
      <logo-text></logo-text>
    </div>
    <nav>
      <router-link class="button blush" to="/new-game">Nouvelle partie</router-link>
      <router-link class="button blush" to="/join-game">Rejoindre</router-link>
    </nav>
  </div>
</template>

<script setup>
import LogoText from "../components/LogoText.vue";
import LogoImage from "../../img/logo.svg";
</script>


<style scoped>
.logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.home-logo {
    max-width: 9rem;
    margin: 4rem auto 2rem;
}
.logo-text {
    max-width: 14rem;
}
nav {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    justify-content: center;
    margin-top: 4rem;
}

.button {
    font-size: 1.7rem;
    margin-bottom: 1rem;
}
</style>
