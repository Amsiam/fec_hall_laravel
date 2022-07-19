<template>
  <Navbar />
  <div class="container dark:bg-black">
    <slot />
  </div>
</template>

<script>
import Navbar from "./Navbar.vue";
export default {
  components: {
    Navbar,
  },
};
</script>

<style>
</style>
