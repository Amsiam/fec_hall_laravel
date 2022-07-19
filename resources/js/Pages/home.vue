<template>
  <div class="w-1/2 mx-auto mt-52 pl-10">
    <div
      v-if="form.hasErrors"
      class="
        flex
        p-4
        mb-4
        text-sm text-red-700
        bg-red-100
        rounded-lg
        dark:bg-red-200 dark:text-red-800
      "
      role="alert"
    >
      <svg
        class="inline flex-shrink-0 mr-3 w-5 h-5"
        fill="currentColor"
        viewBox="0 0 20 20"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          fill-rule="evenodd"
          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
          clip-rule="evenodd"
        ></path>
      </svg>
      <div>
        {{ form.errors.meal_status }}
      </div>
    </div>
    <h1 class="text-2xl dark:text-white mb-2">{{ props.date }}</h1>
    <label
      for="default-toggle"
      class="relative inline-flex items-center mb-4 cursor-pointer"
    >
      <input
        type="checkbox"
        v-model="value"
        id="default-toggle"
        class="sr-only peer"
        :checked="props.meal_status"
      />
      <div
        class="
          w-11
          h-6
          bg-gray-200
          rounded-full
          peer
          peer-focus:ring-4 peer-focus:ring-blue-300
          dark:peer-focus:ring-blue-800 dark:bg-gray-700
          peer-checked:after:translate-x-full peer-checked:after:border-white
          after:content-['']
          after:absolute
          after:top-0.5
          after:left-[2px]
          after:bg-white
          after:border-gray-300
          after:border
          after:rounded-full
          after:h-5
          after:w-5
          after:transition-all
          dark:border-gray-600
          peer-checked:bg-blue-600
        "
      ></div>
      <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
        Meal Status
      </span>
    </label>
  </div>
</template>

<script setup>
import { ref, watch, defineProps } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

const props = defineProps({
  meal_status: Number,
  date: String,
});

const value = ref();

const form = useForm({
  meal_status: props.meal_status,
});

watch(value, (newVal) => {
  form.meal_status = newVal ? 1 : 0;

  form.post("/toggle", {
    onError: (errors) => {
      if (newVal) {
        value.value = false;
      }
    },
  });
});

// defineProps({ test });
</script>

<style></style>;
