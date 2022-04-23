<template>
    <div>
        <h2>Oups, l'assiette est vide !</h2>
        <div class="plate404">
            <span>4</span>
            <img :src="Plate" alt="Assiette formant le 0 de 404">
            <span>4</span>
        </div>
        <p>Retrouve plein d'autres délicieux desserts en revenant sur la page d'accueil.</p>
        <router-link to="/">
            <button class="button blush">
                Retourner à l'accueil
            </button>
        </router-link>
    </div>
</template>

<script setup>
    import Plate from "../../img/plate.svg";
</script>

<style scoped>
    h2 {
        color: var(--champagne-pink);
        text-align: center;
    }
    .plate404 {
        color: var(--champagne-pink);
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 3rem;
        max-width: 100%;
        font-weight: bold;
    }
    .plate404 img {
        width: 8rem;
        height: 8rem;
    }
    p{
        margin: 2rem auto;
        font-size: 1.2rem;
    }

</style>
