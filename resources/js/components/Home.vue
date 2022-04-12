<template>
    <div class="container">
        <div class="logo"></div>
        <nav>
            <router-link to="/new-game">Créer une partie</router-link>
            <router-link to="/join-game">Rejoindre une partie</router-link>
        </nav>
    </div>
</template>

<script>

export default {
  name: 'home',
}
</script>
