<template>
    <div class="wagashi">
        <!-- <img :src="Wagashi1" alt=""> -->
        <img :src="images[nb].default" alt="">
    </div>
</template>

<script setup>
    // list all wagashi files
    const listFiles = require.context('../../img/wagashi/', true, /.svg$/);
    const images = importAll(listFiles);
    // pick a random wagashi
    const nb = Math.floor(Math.random() * Object.keys(images).length);

    function importAll(r) {
        return r.keys().map(r);
    }
</script>

<style scoped>

</style>
