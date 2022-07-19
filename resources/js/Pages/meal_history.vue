<template>
  <div class="mt-10 w-1/2 mx-auto">
    <Calendar />
    <DatePicker is-dark v-model="date" />
  </div>
</template>

<script>
import { Calendar, DatePicker } from "v-calendar";

export default {
  components: {
    Calendar,
    DatePicker,
  },
  data() {
    return {
      date: new Date(),
    };
  },
};
</script>



<style>
</style>
