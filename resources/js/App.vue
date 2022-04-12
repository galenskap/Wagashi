<template>
  <main>
    <div class="container">
      <router-view v-slot="{ Component }">
        <transition name="slide" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </div>
  </main>
</template>

<script>
export default {
  name: "App",
  components: {
    // MainComponent
  },
};
</script>
