<template>
    <header>
        <div class="title-and-logo">
            <router-link to="/">
                <logo-line :color=color></logo-line>
            </router-link>
            <logo-text v-if="!title" :color=color></logo-text>
            <h1 v-if="title"  :class=color>{{title}}</h1>
        </div>
    </header>
</template>

<script setup>
import LogoText from "../components/LogoText.vue";
import LogoLine from "../components/LogoLine.vue";

const props = defineProps(['color', 'title']);

</script>


<style scoped>
.title-and-logo {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    height: 3rem;
}
.title-and-logo a {
    width: 3rem;
    margin-right: .75rem;
}

.logo-text {
    width: 7rem;
}
</style>
