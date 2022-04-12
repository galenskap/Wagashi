<template>
  <main :class="className">
    <div class="container">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </div>
  </main>
</template>

<script setup>
import { ref, watch } from "@vue/runtime-core";
import { useRoute } from "vue-router";

    const className = ref('');
    const route = useRoute( );

    watch(() => route.meta.class, () => {
            className.value = route.meta.class;
        }
    );
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
