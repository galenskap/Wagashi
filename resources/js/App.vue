<template>
  <main>
    <div class="container">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
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

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}


.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
